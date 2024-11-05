<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Transactions;
use App\Models\TransactionsHistory;
use App\Models\Assets;
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
            ->join('customer', 'transactions.name_holder', '=', 'customer.id')
            ->join('inventory', 'transactions.asset_tagging', '=', 'inventory.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'inventory.tagging as tagging'
            )
            ->where('transactions.name_holder', $userId) // Filter by user ID
            ->where('transactions.approval_status', 'Approved') // Only get approved transactions
            ->get();

        // Fetch pending transactions related to the logged-in user and join related tables
        $pendingTransactions = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.name_holder', '=', 'customer.id')
            ->join('inventory', 'transactions.asset_tagging', '=', 'inventory.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'inventory.tagging as tagging'
            )
            ->where('transactions.name_holder', $userId) // Filter by user ID
            ->where('transactions.approval_status', 'Pending') // Only get pending transactions
            ->get();

        return view('transactions.assetuser', compact('transactions', 'pendingTransactions'));
    }

    public function serahterima($ids)
    {
        $idsArray = explode(',', $ids); // Convert the comma-separated string to an array

        $transactions = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.name_holder', '=', 'customer.id')
            ->select('transactions.*', 'merk.name as merk_name', 'customer.name as customer_name')
            ->whereIn('transactions.id', $idsArray)
            ->get(); // Use get() to retrieve multiple records

        $merks = Merk::all();
        $customers = Customer::all();
        $inventories = Assets::all();

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
            $filePath = null;
            if ($request->hasFile('documentation')) {
                $file = $validatedData['documentation'];
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('public/uploads/documentation', $filename);
                $relativePath = 'uploads/documentation/' . $filename;
            }

            foreach ($validatedData['transactions'] as $id) {
                $transaction = Transactions::findOrFail($id);

                // Update or delete existing documentation if a new one is provided
                if ($filePath) {
                    if ($transaction->documentation && \Storage::exists('public/' . $transaction->documentation)) {
                        \Storage::delete('public/' . $transaction->documentation);
                    }
                    $transaction->documentation = $relativePath;
                }

                // Update transaction approval status
                $transaction->approval_status = 'Approved';
                $transaction->save();

                // Add entry to transaction_history with additional fields
                \DB::table('transaction_history')->insert([
                    'transaction_id' => $transaction->id,
                    'type_transactions' => $transaction->type_transactions,  // Fetch from transaction
                    'asset_code' => $transaction->asset_code,                // Fetch from transaction
                    'category_asset' => $transaction->category_asset,        // Fetch from transaction
                    'merk' => $transaction->merk,                            // Fetch from transaction
                    'specification' => $transaction->spesification,          // Fetch from transaction
                    'serial_number' => $transaction->serial_number,          // Fetch from transaction
                    'name_holder' => $transaction->name_holder,              // Fetch from transaction
                    'position' => $transaction->position,                    // Fetch from transaction
                    'location' => $transaction->location,                    // Fetch from transaction
                    'status' => $transaction->status,                        // Fetch from transaction
                    'asset_condition' => $transaction->condition,                  // Fetch from transaction
                    'documentation' => $relativePath,
                    'reason' => $transaction->reason,                        // Fetch from transaction
                    'note' => $transaction->note,                            // Fetch from transaction
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Redirect with success message
            return redirect()->route('shared.homeUser')->with('success', 'Transactions approved successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Transaction not found:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('Transaction not found. Please check the IDs.');
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
//         $asset = Transactions::findOrFail($assetId);

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
            $assetTransaction = Transactions::findOrFail($assetId);

            // Update the documentation path in the transactions table
            $assetTransaction->documentation = $filePath;

            // Save the transaction data before deleting
            $transactionData = [
                'transaction_id' => $assetTransaction->id,
                'type_transactions' => $assetTransaction->type_transactions,
                'asset_code' => $assetTransaction->asset_code,
                'category_asset' => $assetTransaction->category_asset,
                'merk' => $assetTransaction->merk,
                'specification' => $assetTransaction->specification,
                'serial_number' => $assetTransaction->serial_number,
                'name_holder' => $assetTransaction->name_holder,
                'position' => $assetTransaction->position,
                'location' => $assetTransaction->location,
                'status' => $assetTransaction->status,
                'asset_condition' => $assetTransaction->condition,
                'documentation' => $filePath,
                'reason' => $assetTransaction->reason, // Fetch from transaction
                'note' => $assetTransaction->note,     // Fetch from transaction
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert the transaction data into transaction_history
            \DB::table('transaction_history')->insert($transactionData);

            // Delete the asset transaction after recording
            $assetTransaction->delete();
        }

        // Redirect with a success message
        return redirect()->route('shared.homeUser')->with('success', 'All selected transactions have been returned successfully.');
    }




    // TransactionsController.php
// TransactionsController.php
    public function returnAsset($id)
    {
        $asset = Transactions::findOrFail($id);

        // Assuming `user` is a relationship method on the Asset model
        $asset->user()->delete();

        return redirect()->back()->with('success', 'Asset returned successfully.');
    }


}
