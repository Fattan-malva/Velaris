<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use App\Models\Merk;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class InventoryController extends Controller
{
    public function index()
    {
        // Fetch inventory with merk name
        $inventorys = DB::table('inventory')
            ->join('merk', 'inventory.merk', '=', 'merk.id')
            ->select('inventory.*', 'merk.name as merk_name')
            ->get();

        return view('assets.index', compact('inventorys'));
    }

    public function create()
    {
        $merkes = Merk::all(); // Fetch all Merk records
        return view('assets.create', compact('merkes')); // Pass 'merkes' to the view
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tagging' => 'required|string|max:255|unique:inventory,tagging',
            'asets' => 'required|string|max:255',
            'merk' => 'required|exists:merk,id',
            'seri' => 'required|string|max:255',
            'tanggalmasuk' => 'required|date',  // Validasi sebagai tanggal
            'type' => 'required|string|max:255',
            'kondisi' => 'required|in:Good,Exception,Bad,New',
            'documentation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048' // Validasi file dokumentasi
        ]);

        // Konversi tanggal ke format yang diinginkan (jika perlu)
        $formattedDate = Carbon::parse($request->tanggalmasuk)->format('Y-m-d'); // Simpan dalam format tanggal yang standar

        // Simpan data ke database
        $inventory = Inventory::create([
            'asets' => $request->asets,
            'merk' => $request->merk,
            'tagging' => $request->tagging,
            'seri' => $request->seri,
            'tanggalmasuk' => $formattedDate, // Menyimpan dalam format tanggal
            'type' => $request->type,
            'kondisi' => $request->kondisi,
        ]);

        // Handle the uploaded documentation file
        $documentationPath = null;
        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $documentationPath = 'documentation/' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('documentation'), $documentationPath);
        }

        // Simpan data ke inventory_history
        InventoryHistory::create([
            'inventory_id' => $inventory->id, // ID dari inventory yang baru saja dibuat
            'action' => 'INSERT', // Tindakan yang dilakukan
            'tagging' => $inventory->tagging,
            'asets' => $inventory->asets,
            'merk' => $inventory->merk,
            'seri' => $inventory->seri,
            'tanggalmasuk' => $inventory->tanggalmasuk,
            'type' => $inventory->type,
            'kondisi' => $inventory->kondisi,
            'documentation' => $documentationPath, // Simpan path dokumentasi
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }




    public function edit()
    {
        // Fetch all inventories with their merk names for the form
        $inventories = DB::table('inventory')
            ->join('merk', 'inventory.merk', '=', 'merk.id')
            ->select('inventory.id', 'inventory.tagging', 'merk.name as merk_name')
            ->get();
        if ($inventories->isEmpty()) {
            return redirect()->route('assets.index')->with('error', 'No assets available for maintenance.');
        }

        // Fetch all merk names for the form
        $merks = DB::table('merk')->pluck('name', 'id');

        return view('assets.edit', compact('inventories', 'merks'));
    }

    public function update(Request $request)
    {
        // Validate that at least one asset is selected
        $request->validate([
            'ids' => 'required|array', // Ensure that it's an array of IDs
            'ids.*' => 'exists:inventory,id', // Ensure each ID exists in the inventory table
            // 'asets' => 'required|string|max:255',
            // 'merk' => 'required|exists:merk,id',
            // 'seri' => 'required|string|max:255',
            // 'type' => 'required|string|max:255',
            'maintenance' => 'required|string|max:255',
            'kondisi' => 'required|in:Good,Exception,Bad,New',
        ]);

        // Loop through each selected asset ID and update
        foreach ($request->input('ids') as $id) {
            $inventory = Inventory::findOrFail($id); // Find the asset by ID

            // $inventory->asets = $request->input('asets');
            // $inventory->merk = $request->input('merk');

            // Check if tagging is provided in the request
            if ($request->has('tagging')) {
                $inventory->tagging = $request->input('tagging');
            } else {
                // If tagging is not provided, keep the existing tagging or generate one
                $inventory->tagging = $inventory->tagging ?? $this->generateTagging($inventory); // Adjust to your logic
            }

            // $inventory->seri = $request->input('seri');
            // $inventory->type = $request->input('type');
            $inventory->kondisi = $request->input('kondisi');
            $inventory->maintenance = $request->input('maintenance');
            $inventory->save(); // Save each asset
        }

        // Redirect with success message after all updates
        return redirect()->route('assets.index')->with('success', 'Selected assets maintained successfully');
    }

    // Example function for tagging generation (customize according to your needs)
    private function generateTagging($inventory)
    {
        // Generate the tagging value based on your own logic
        return strtoupper(substr($inventory->asets, 0, 3)) . '-' . strtoupper(substr($inventory->merk, 0, 2)) . '-' . str_pad($inventory->id, 3, '0', STR_PAD_LEFT);
    }

    public function showEditForm()
    {
        // Fetch all inventory items for display in the select dropdown
        $inventories = Inventory::all();
        // Fetch all merk items
        $merks = Merk::pluck('name', 'id'); // Fetch merk names with their corresponding IDs

        // Return the view with the inventory and merk data
        return view('assets.edit', compact('inventories', 'merks'));
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
            // Find the inventory by its ID
            $inventory = Inventory::findOrFail($id);

            // Check the status before attempting to delete
            if ($inventory->status === 'Operation') {
                return redirect()->route('assets.index')->with('error', 'Cannot delete assets because assets are still operational, please make a return first.');
            }

            // Now delete the inventory from the database if status is Inventory
            $inventory->delete();

            // Create a record in the inventory_history table after successful deletion
            // Create a record in the inventory_history table after successful deletion
            InventoryHistory::create([
                'inventory_id' => $id,
                'action' => 'DELETE',
                'tagging' => $inventory->tagging,
                'asets' => $inventory->asets,
                'merk' => $inventory->merk,
                'seri' => $inventory->seri,
                'tanggalmasuk' => $inventory->tanggalmasuk,
                'type' => $inventory->type,
                'kondisi' => $inventory->kondisi,
                'status' => $inventory->status,
                'lokasi' => $inventory->lokasi,
                'tanggal_diterima' => ($inventory->tanggal_diterima === '0000-00-00 00:00:00') ? null : $inventory->tanggal_diterima,
                'documentation' => $documentationPath,  // Save the documentation path
            ]);

        }

        return redirect()->route('assets.index')->with('success', 'Assets scrapped successfully.');
    }

    public function showScrapForm()
    {
        // Fetch all inventory items for display in the select dropdown
        $inventories = Inventory::all();

        // Return the scrap view with the inventory data
        return view('assets.scrap', compact('inventories'));
    }

    public function show($id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('inventory.show', compact('inventory'));
    }


}
