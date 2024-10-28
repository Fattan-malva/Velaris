<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Assets;
use App\Models\Inventory;
use App\Models\Merk;
use App\Models\Customer;
use Illuminate\Http\Request;

class TransactionsUserController extends Controller
{
    public function indexuser()
    {
        // Retrieve user ID from session
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('shared.home')->with('error', 'User is not logged in.');
        }

        // Fetch approved assets related to the logged-in user and join related tables
        $assets = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->join('customer', 'assets.nama', '=', 'customer.id')
            ->join('inventory', 'assets.asset_tagging', '=', 'inventory.id')
            ->select(
                'assets.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'inventory.tagging as tagging'
            )
            ->where('assets.nama', $userId) // Filter by user ID
            ->where('assets.approval_status', 'Approved') // Only get approved assets
            ->get();

        // Fetch pending assets related to the logged-in user and join related tables
        $pendingAssets = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->join('customer', 'assets.nama', '=', 'customer.id')
            ->join('inventory', 'assets.asset_tagging', '=', 'inventory.id')
            ->select(
                'assets.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'inventory.tagging as tagging'
            )
            ->where('assets.nama', $userId) // Filter by user ID
            ->where('assets.approval_status', 'Pending') // Only get pending assets
            ->get();

        return view('transactions.assetuser', compact('assets', 'pendingAssets'));
    }

    public function serahterima($ids)
    {
        $idsArray = explode(',', $ids); // Convert the comma-separated string to an array
    
        $assets = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->join('customer', 'assets.nama', '=', 'customer.id')
            ->select('assets.*', 'merk.name as merk_name', 'customer.name as customer_name')
            ->whereIn('assets.id', $idsArray)
            ->get(); // Use get() to retrieve multiple records
    
        $merks = Merk::all();
        $customers = Customer::all();
        $inventories = Inventory::all();
    
        return view('transactions.serahterima', compact('assets', 'merks', 'customers', 'inventories'));
    }
    
    
    public function updateserahterima(Request $request)
{
    // Validate input
    $validatedData = $request->validate([
        'assets' => 'required|array',
        'assets.*' => 'exists:assets,id',
        'documentation' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048' // Single file for all assets
    ]);

    try {
        // Process documentation if present
        if ($validatedData['documentation']) {
            foreach ($validatedData['assets'] as $id) {
                // Find the asset by ID
                $asset = Assets::findOrFail($id);
                
                // Delete old documentation if exists
                if ($asset->documentation && \Storage::exists('public/' . $asset->documentation)) {
                    \Storage::delete('public/' . $asset->documentation);
                }

                // Save new documentation
                $file = $validatedData['documentation'];
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('public/uploads/documentation', $filename);
                $asset->documentation = 'uploads/documentation/' . $filename; // Update asset documentation path
                $asset->approval_status = 'Approved'; // Set approval status
                $asset->save(); // Save updated asset
            }
        } else {
            // If no new documentation is provided, just approve the assets
            foreach ($validatedData['assets'] as $id) {
                $asset = Assets::findOrFail($id);
                $asset->approval_status = 'Approved';
                $asset->save();
            }
        }

        // Redirect with success message
        return redirect()->route('shared.homeUser')->with('success', 'Assets approved successfully.');

    } catch (\Exception $e) {
        // Log error and redirect with error message
        \Log::error('Failed to update assets:', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors('Failed to approve assets. Please try again.');
    }
}

// return submit all tapi up doc satu satu
    
// public function returnMultiple(Request $request)
// {
//     $assetIds = $request->input('assets', []);  // Retrieve array of asset IDs
//     $documentations = $request->file('documentation', []);  // Retrieve file uploads

//     foreach ($assetIds as $key => $assetId) {
//         $asset = Assets::findOrFail($assetId);
        
//         // Save documentation if uploaded
//         if (isset($documentations[$key])) {
//             $path = $documentations[$key]->store('assets/documentation', 'public');
//             $asset->documentation = $path;
//         }

//         $asset->delete();  // Delete the asset (since we're returning it)
//     }

//     return redirect()->route('shared.homeUser')->with('success', 'All selected assets have been returned successfully.');
// }


public function returnMultiple(Request $request)
{
    $assetIds = $request->input('assets', []);  // Ambil array ID asset
    $documentations = $request->file('documentation', []);  // Ambil array file yang diunggah

    foreach ($assetIds as $key => $assetId) {
        $asset = Assets::findOrFail($assetId);

        // Cek apakah ada file yang diunggah untuk asset ini
        if (isset($documentations[$key])) {
            // Simpan file ke storage
            $path = $documentations[$key]->store('assets/documentation', 'public');
            $asset->documentation = $path;
        }

        // Hapus asset setelah dokumentasi disimpan
        $asset->delete();
    }

    return redirect()->route('shared.homeUser')->with('success', 'All selected assets have been returned successfully.');
}

    // AssetsController.php
// AssetsController.php
    public function returnAsset($id)
    {
        $asset = Assets::findOrFail($id);

        // Assuming `user` is a relationship method on the Asset model
        $asset->user()->delete();

        return redirect()->back()->with('success', 'Asset returned successfully.');
    }


}
