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

        // Fetch approved transactions related to the logged-in user and join related tables
        $transactions = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->join('inventory', 'transactions.asset_tagging', '=', 'inventory.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'inventory.tagging as tagging'
            )
            ->where('transactions.nama', $userId) // Filter by user ID
            ->where('transactions.approval_status', 'Approved') // Only get approved transactions
            ->get();

        // Fetch pending transactions related to the logged-in user and join related tables
        $pendingAssets = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->join('inventory', 'transactions.asset_tagging', '=', 'inventory.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'inventory.tagging as tagging'
            )
            ->where('transactions.nama', $userId) // Filter by user ID
            ->where('transactions.approval_status', 'Pending') // Only get pending transactions
            ->get();

        return view('transactions.assetuser', compact('transactions', 'pendingAssets'));
    }

    public function serahterima($ids)
    {
        $idsArray = explode(',', $ids); // Convert the comma-separated string to an array

        $transactions = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->select('transactions.*', 'merk.name as merk_name', 'customer.name as customer_name')
            ->whereIn('transactions.id', $idsArray)
            ->get(); // Use get() to retrieve multiple records

        $merks = Merk::all();
        $customers = Customer::all();
        $inventories = Inventory::all();

        return view('transactions.serahterima', compact('transactions', 'merks', 'customers', 'inventories'));
    }


    public function updateserahterima(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'transactions' => 'required|array',
            'transactions.*' => 'exists:transactions,id',
            'documentation' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048' // Single file for all transactions
        ]);

        try {
            // Process documentation if present
            if (isset($validatedData['documentation'])) {
                $file = $validatedData['documentation'];
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('public/uploads/documentation', $filename);

                foreach ($validatedData['transactions'] as $id) {
                    $asset = Assets::findOrFail($id);

                    // Delete old documentation if exists
                    if ($asset->documentation && \Storage::exists('public/' . $asset->documentation)) {
                        \Storage::delete('public/' . $asset->documentation);
                    }

                    // Save new documentation
                    $asset->documentation = 'uploads/documentation/' . $filename;
                    $asset->approval_status = 'Approved';
                    $asset->save();
                }
            } else {
                // If no new documentation is provided, just approve the transactions
                foreach ($validatedData['transactions'] as $id) {
                    $asset = Assets::findOrFail($id);
                    $asset->approval_status = 'Approved';
                    $asset->save();
                }
            }

            // Redirect with success message
            return redirect()->route('shared.homeUser')->with('success', 'Assets approved successfully.');

        } catch (\Exception $e) {
            \Log::error('Failed to update transactions:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('Failed to approve transactions. Please try again.');
        }
    }

    // return submit all tapi up doc satu satu

    // public function returnMultiple(Request $request)
// {
//     $assetIds = $request->input('transactions', []);  // Retrieve array of asset IDs
//     $documentations = $request->file('documentation', []);  // Retrieve file uploads

    //     foreach ($assetIds as $key => $assetId) {
//         $asset = Assets::findOrFail($assetId);

    //         // Save documentation if uploaded
//         if (isset($documentations[$key])) {
//             $path = $documentations[$key]->store('transactions/documentation', 'public');
//             $asset->documentation = $path;
//         }

    //         $asset->delete();  // Delete the asset (since we're returning it)
//     }

    //     return redirect()->route('shared.homeUser')->with('success', 'All selected transactions have been returned successfully.');
// }


public function returnMultiple(Request $request)
{
    // Validate the input and ensure a single documentation file is provided
    $validatedData = $request->validate([
        'transactions' => 'required|array',
        'transactions.*' => 'exists:transactions,id', // Ensure each transaction is a valid asset ID
        'documentation' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048', // Single documentation file
    ]);

    // Retrieve the file and store it once
    $file = $validatedData['documentation'];
    $filePath = $file->store('assets/documentation', 'public');

    foreach ($validatedData['transactions'] as $assetId) {
        $asset = Assets::findOrFail($assetId);

        // Update the documentation path and status for each asset
        $asset->documentation = $filePath;
        // $asset->approval_status = 'Approved';
        $asset->delete();
    }

    // Redirect with a success message
    return redirect()->route('shared.homeUser')->with('success', 'All selected transactions have been returned successfully.');
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
