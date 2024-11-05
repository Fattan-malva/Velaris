<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Assets;
use App\Models\Merk;
use App\Models\AssetsHistory;
use App\Models\MaintenanceHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class AssetsController extends Controller
{
    public function index()
    {
        // Ambil data assets, merk name, dan customer name
        $assetss = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id') // Gabungkan dengan tabel merk untuk mendapatkan nama merk
            ->leftJoin('customer', 'assets.name_holder', '=', 'customer.id') // Gabungkan dengan tabel customer untuk mendapatkan nama pemegang aset
            ->select(
                'assets.*',
                'merk.name as merk_name',          // Ambil kolom name dari tabel merk sebagai merk_name
                'customer.name as customer_name'    // Ambil kolom name dari tabel customer sebagai customer_name
            )
            ->get();

        return view('assets.index', compact('assetss'));
    }


    public function create()
    {
        $merkes = Merk::all(); // Fetch all Merk records
        return view('assets.add-asset', compact('merkes')); // Pass 'merkes' to the view
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'code' => 'required|string|max:255|unique:assets,code',
            'category' => 'required|string|max:255',
            'merk' => 'required|exists:merk,id',
            'serial_number' => 'required|string|max:255',
            'entry_date' => 'required|date',
            'scheduling_maintenance_value' => 'required|numeric',
            'scheduling_maintenance_unit' => 'required|string|in:Weeks,Months,Years',
            'spesification' => 'required|string|max:255',
            'condition' => 'required|in:Good,Exception,Bad,New',
            'documentation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Convert entry date to standard format
        $formattedDate = Carbon::parse($request->entry_date)->format('Y-m-d');

        // Calculate next maintenance date
        $maintenanceInterval = $request->scheduling_maintenance_value;
        $maintenanceUnit = $request->scheduling_maintenance_unit;

        // Initialize next maintenance date
        $nextMaintenanceDate = null;

        switch ($maintenanceUnit) {
            case 'Weeks':
                $nextMaintenanceDate = Carbon::parse($formattedDate)->addDays($maintenanceInterval * 7);
                break;
            case 'Months':
                $nextMaintenanceDate = Carbon::parse($formattedDate)->addMonths($maintenanceInterval);
                break;
            case 'Years':
                $nextMaintenanceDate = Carbon::parse($formattedDate)->addYears($maintenanceInterval);
                break;
            default:
                // Leave nextMaintenanceDate as null
                break;
        }

        // Ensure nextMaintenanceDate is a valid date
        $nextMaintenanceDateFormatted = $nextMaintenanceDate ? $nextMaintenanceDate->format('Y-m-d') : null;

        // Save asset data to the database
        $assets = Assets::create([
            'category' => $request->category,
            'merk' => $request->merk,
            'code' => $request->code,
            'serial_number' => $request->serial_number,
            'entry_date' => $formattedDate,
            'scheduling_maintenance' => $maintenanceInterval . ' ' . $maintenanceUnit,
            'next_maintenance' => $nextMaintenanceDateFormatted, // Store the calculated next maintenance date
            'spesification' => $request->spesification,
            'condition' => $request->condition,
            'note_maintenance' => '',
        ]);



        // Handle the uploaded documentation file
        $documentationPath = null;
        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $documentationPath = 'documentation/' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documentation'), $documentationPath);
        }

        // Save asset history
        AssetsHistory::create([
            'assets_id' => $assets->id,
            'action' => 'INSERT',
            'code' => $assets->code,
            'category' => $assets->category,
            'merk' => $assets->merk,
            'serial_number' => $assets->serial_number,
            'entry_date' => $assets->entry_date,
            'spesification' => $assets->spesification,
            'condition' => $assets->condition,
            'documentation' => $documentationPath,
        ]);

        // Redirect to the index page with success message
        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }





    public function update(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'last_maintenance' => 'required|date',
            'condition' => 'required|string',
            'note_maintenance' => 'required|string',
        ]);

        foreach ($request->ids as $id) {
            $asset = Assets::find($id);

            if ($asset) {
                // Update last maintenance, condition, and note maintenance
                $asset->last_maintenance = $request->last_maintenance;
                $asset->condition = $request->condition;
                $asset->note_maintenance = $request->note_maintenance;

                // Calculate and update next maintenance based on scheduling interval
                list($intervalValue, $intervalUnit) = explode(' ', $asset->scheduling_maintenance);
                $nextMaintenanceDate = \Carbon\Carbon::parse($request->last_maintenance);

                switch (strtolower($intervalUnit)) {
                    case 'weeks':
                        $nextMaintenanceDate->addWeeks($intervalValue);
                        break;
                    case 'months':
                        $nextMaintenanceDate->addMonths($intervalValue);
                        break;
                    case 'years':
                        $nextMaintenanceDate->addYears($intervalValue);
                        break;
                    default:
                        $nextMaintenanceDate = null; // If scheduling unit is invalid, set to null
                        break;
                }

                // Update next maintenance date
                $asset->next_maintenance = $nextMaintenanceDate ? $nextMaintenanceDate->format('Y-m-d') : null;
                $asset->save();

                // Record the maintenance history
                MaintenanceHistory::create([
                    'assets_id' => $asset->id,
                    'code' => $asset->code,
                    'last_maintenance' => $request->last_maintenance,
                    'condition' => $request->condition,
                    'note_maintenance' => $request->note_maintenance,
                ]);
            }
        }

        return redirect()->route('assets.index')->with('success', 'Maintenance updated successfully.');
    }

    public function edit()
    {
        // Fetch all inventories with their merk names for the form
        $assetss = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->select('assets.id', 'assets.code', 'merk.name as merk_name')
            ->get();
        if ($assetss->isEmpty()) {
            return redirect()->route('assets.index')->with('error', 'No assets available for maintenance.');
        }

        // Fetch all merk names for the form
        $merks = DB::table('merk')->pluck('name', 'id');

        return view('assets.maintenance', compact('asetsss', 'merks'));
    }

    public function showEditForm()
    {
        // Fetch all assets items for display in the select dropdown
        $assetss = Assets::all();
        // Fetch all merk items
        $merks = Merk::pluck('name', 'id'); // Fetch merk names with their corresponding IDs

        // Return the view with the assets and merk data
        return view('assets.maintenance', compact('assetss', 'merks'));
    }


    public function destroy(Request $request)
    {
        $ids = $request->input('ids');  // Get the IDs from the request
        $documentationPath = null;

        // Handle the uploaded documentation file
        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $documentationPath = 'documentation/' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documentation'), $documentationPath);
        }

        foreach ($ids as $id) {
            // Find the assets by its ID
            $assets = Assets::findOrFail($id);

            // Check the status before attempting to delete
            if ($assets->status === 'Operation') {
                return redirect()->route('assets.index')->with('error', 'Cannot scrap assets because assets are still operational, please make a return first.');
            }
            $assets->delete();
            AssetsHistory::create([
                'assets_id' => $id,
                'action' => 'DELETE',
                'code' => $assets->code,
                'category' => $assets->category,
                'merk' => $assets->merk,
                'serial_number' => $assets->serial_number,
                'entry_date' => $assets->entry_date,
                'spesification' => $assets->spesification,
                'condition' => $assets->condition,
                'status' => $assets->status,
                'location' => $assets->location,
                'handover_date' => ($assets->handover_date === '0000-00-00 00:00:00') ? null : $assets->handover_date,
                'documentation' => $documentationPath,
            ]);

        }

        return redirect()->route('assets.index')->with('success', 'Assets scrapped successfully.');
    }

    public function showScrapForm()
    {
        // Fetch all assets items for display in the select dropdown
        $inventories = Assets::all();

        // Return the scrap view with the assets data
        return view('assets.scrap', compact('inventories'));
    }

    public function show($id)
    {
        $assets = Assets::findOrFail($id);
        return view('assets.show', compact('assets'));
    }


}
