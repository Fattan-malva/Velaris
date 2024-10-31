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
        // Fetch assets with merk name
        $assetss = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->select('assets.*', 'merk.name as merk_name')
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
        // Validasi input
        $request->validate([
            'code' => 'required|string|max:255|unique:assets,code',
            'category' => 'required|string|max:255',
            'merk' => 'required|exists:merk,id',
            'serial_number' => 'required|string|max:255',
            'entry_date' => 'required|date',
            'scheduling_maintenance' => 'required|string',
            'spesification' => 'required|string|max:255',
            'condition' => 'required|in:Good,Exception,Bad,New',
            'documentation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048' // Validasi file dokumentasi
        ]);

        // Konversi tanggal ke format yang diinginkan (jika perlu)
        $formattedDate = Carbon::parse($request->entry_date)->format('Y-m-d'); // Simpan dalam format tanggal yang standar

        // Simpan data ke database
        $assets = Assets::create([
            'category' => $request->category,
            'merk' => $request->merk,
            'code' => $request->code,
            'serial_number' => $request->serial_number,
            'entry_date' => $formattedDate,
            'scheduling_maintenance' => $request->scheduling_maintenance,
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

        // Simpan data ke assets_history
        AssetsHistory::create([
            'assets_id' => $assets->id, // ID dari assets yang baru saja dibuat
            'action' => 'INSERT', // Tindakan yang dilakukan
            'code' => $assets->code,
            'category' => $assets->category,
            'merk' => $assets->merk,
            'serial_number' => $assets->serial_number,
            'entry_date' => $assets->entry_date,
            'spesification' => $assets->spesification,
            'condition' => $assets->condition,
            'documentation' => $documentationPath, // Simpan path dokumentasi
            
        ]);

        // Redirect ke halaman index dengan pesan sukses
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
                $asset->last_maintenance = $request->last_maintenance;
                $asset->condition = $request->condition;
                $asset->note_maintenance = $request->note_maintenance;
                $asset->save();

                MaintenanceHistory::create([
                    'assets_id' => $asset->id,
                    'code' => $asset->code, // Add the asset code here
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
