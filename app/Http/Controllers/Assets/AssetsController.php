<?php
namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Assets;
use App\Models\Depreciation;
use App\Models\Merk;
use App\Models\AssetsHistory;
use App\Models\MaintenanceHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class AssetsController extends Controller
{
    // API Get Assets
    public function apiIndex()
    {
        $assetss = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->leftJoin('customer', 'assets.name_holder', '=', 'customer.id')
            ->leftJoin('depreciation', 'assets.code', '=', 'depreciation.asset_code')
            ->select(
                'assets.id',
                'assets.code',
                'assets.name_holder',
                'assets.merk',
                'assets.status',
                'assets.serial_number',
                'assets.asset_age',
                'assets.category',
                'assets.spesification',
                'assets.next_maintenance',
                'assets.condition',
                'assets.entry_date',
                'assets.scheduling_maintenance',
                'assets.last_maintenance',
                'assets.location',
                'merk.name as merk_name',
                'customer.name as customer_name',
                DB::raw('
                COALESCE(
                    (SELECT depreciation_price 
                     FROM depreciation 
                     WHERE depreciation.asset_code = assets.code 
                     AND depreciation.date = CURDATE() 
                     LIMIT 1), 
                     
                    (SELECT depreciation_price 
                     FROM depreciation 
                     WHERE depreciation.asset_code = assets.code 
                     AND depreciation.date < CURDATE() 
                     ORDER BY depreciation.date DESC 
                     LIMIT 1),
                     
                    (SELECT depreciation_price 
                     FROM depreciation 
                     WHERE depreciation.asset_code = assets.code 
                     AND depreciation.date > CURDATE() 
                     ORDER BY depreciation.date ASC 
                     LIMIT 1)
                ) as depreciation_price'
                ),
                DB::raw('MAX(depreciation.date) as depreciation_date')
            )
            ->groupBy(
                'assets.id',
                'assets.code',
                'assets.name_holder',
                'assets.merk',
                'assets.status',
                'assets.serial_number',
                'assets.asset_age',
                'assets.next_maintenance',
                'assets.spesification',
                'assets.condition',
                'assets.category',
                'assets.scheduling_maintenance',
                'assets.last_maintenance',
                'assets.entry_date',
                'assets.location',
                'merk.name',
                'customer.name'
            )
            ->get();

        // Add calculated time remaining in seconds for JavaScript
        $assetss = $assetss->map(function ($asset) {
            // Calculate how much time has passed since entry date
            $entryDate = Carbon::parse($asset->entry_date);
            $now = Carbon::now();
            $timeDifferenceInSeconds = $entryDate->diffInSeconds($now);

            // Store the computed time difference and whether it is expired
            $asset->time_remaining_seconds = $timeDifferenceInSeconds;
            $asset->is_expired = $now->greaterThan($entryDate->copy()->addYears($asset->asset_age));

            return $asset;
        });
        return response()->json($assetss);
    }

    public function index()
    {
        $assetss = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->leftJoin('customer', 'assets.name_holder', '=', 'customer.id')
            ->leftJoin('depreciation', 'assets.code', '=', 'depreciation.asset_code')
            ->select(
                'assets.id',
                'assets.code',
                'assets.name_holder',
                'assets.merk',
                'assets.status',
                'assets.serial_number',
                'assets.asset_age',
                'assets.category',
                'assets.spesification',
                'assets.next_maintenance',
                'assets.condition',
                'assets.entry_date',
                'assets.scheduling_maintenance',
                'assets.last_maintenance',
                'assets.location',
                'merk.name as merk_name',
                'customer.name as customer_name',
                DB::raw('
                    COALESCE(
                        (SELECT depreciation_price 
                         FROM depreciation 
                         WHERE depreciation.asset_code = assets.code 
                         AND depreciation.date = CURDATE() 
                         LIMIT 1), 
                         
                        (SELECT depreciation_price 
                         FROM depreciation 
                         WHERE depreciation.asset_code = assets.code 
                         AND depreciation.date < CURDATE() 
                         ORDER BY depreciation.date DESC 
                         LIMIT 1),
                         
                        (SELECT depreciation_price 
                         FROM depreciation 
                         WHERE depreciation.asset_code = assets.code 
                         AND depreciation.date > CURDATE() 
                         ORDER BY depreciation.date ASC 
                         LIMIT 1)
                    ) as depreciation_price'
                ),
                DB::raw('MAX(depreciation.date) as depreciation_date')
            )
            ->groupBy(
                'assets.id',
                'assets.code',
                'assets.name_holder',
                'assets.merk',
                'assets.status',
                'assets.serial_number',
                'assets.asset_age',
                'assets.next_maintenance',
                'assets.spesification',
                'assets.condition',
                'assets.category',
                'assets.scheduling_maintenance',
                'assets.last_maintenance',
                'assets.entry_date',
                'assets.location',
                'merk.name',
                'customer.name'
            )
            ->get();

        // Add calculated time remaining in seconds for JavaScript
        $assetss = $assetss->map(function ($asset) {
            // Calculate how much time has passed since entry date
            $entryDate = Carbon::parse($asset->entry_date);
            $now = Carbon::now();
            $timeDifferenceInSeconds = $entryDate->diffInSeconds($now);

            // Store the computed time difference and whether it is expired
            $asset->time_remaining_seconds = $timeDifferenceInSeconds;
            $asset->is_expired = $now->greaterThan($entryDate->copy()->addYears($asset->asset_age));

            return $asset;
        });

        return view('assets.index', compact('assetss'));
    }

    public function getDepreciation($asset_code)
    {
        $depreciations = DB::table('depreciation')
            ->where('asset_code', $asset_code)
            ->orderBy('date', 'desc')
            ->get(['depreciation_price', 'date']); // Anda dapat memilih field lainnya sesuai kebutuhan

        return response()->json($depreciations); // Mengembalikan data dalam format JSON
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
            'serial_number' => 'required|string|max:255|unique:assets,serial_number',
            'entry_date' => 'required|date',
            'scheduling_maintenance_value' => 'required|numeric',
            'scheduling_maintenance_unit' => 'required|string|in:Weeks,Months,Years',
            'spesification' => 'required|string|max:255',
            'condition' => 'required|in:Good,Exception,Bad,New',
            'asset_age_value' => 'required|numeric',
            'asset_age_unit' => 'required|string|in:Weeks,Months,Years',
            'starting_price' => 'required',
            'documentation' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);
        // Remove dots from starting_price to store as decimal


        // Convert entry date to standard format
        $formattedDate = Carbon::parse($request->entry_date)->format('Y-m-d');
        // Calculate next maintenance date
        $maintenanceInterval = $request->scheduling_maintenance_value;
        $maintenanceUnit = $request->scheduling_maintenance_unit;
        $assetAgeInterval = $request->asset_age_value;
        $assetAgeUnit = $request->asset_age_unit;

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
                break;
        }

        $nextMaintenanceDateFormatted = $nextMaintenanceDate ? $nextMaintenanceDate->format('Y-m-d') : null;
        $startingPrice = str_replace('.', '', $request->starting_price);
        // Create the asset record
        $assets = Assets::create([
            'category' => $request->category,
            'merk' => $request->merk,
            'code' => $request->code,
            'serial_number' => $request->serial_number,
            'entry_date' => $formattedDate,
            'scheduling_maintenance' => $maintenanceInterval . ' ' . $maintenanceUnit,
            'asset_age' => $assetAgeInterval . ' ' . $assetAgeUnit,
            'starting_price' => $startingPrice,
            'next_maintenance' => $nextMaintenanceDateFormatted,
            'spesification' => $request->spesification,
            'condition' => $request->condition,
            'note_maintenance' => '',
        ]);



        // Documentation upload
        $documentationPath = null;
        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $documentationPath = 'documentation/' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documentation'), $documentationPath);
        }

        // Asset history creation
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

        // Calculate depreciation
        $totalMonths = ($assetAgeUnit === 'Years') ? ($assetAgeInterval * 12) :
            (($assetAgeUnit === 'Months') ? $assetAgeInterval : 0);
        $monthlyDepreciation = $totalMonths > 0 ? $startingPrice / $totalMonths : 0;
        $depreciationPrice = $startingPrice;
        $depreciationDate = Carbon::parse($formattedDate);

        while ($depreciationPrice > 0) {
            Depreciation::create([
                'asset_code' => $assets->code,
                'date' => $depreciationDate->format('Y-m-d'),
                'depreciation_price' => max(0, $depreciationPrice)
            ]);

            $depreciationPrice -= $monthlyDepreciation;
            $depreciationDate->addMonth();
        }

        return redirect()->route('assets.add-asset')->with('success', 'Asset created successfully.');
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
                        $nextMaintenanceDate = null;
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
