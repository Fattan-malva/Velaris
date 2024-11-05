<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Transactions;
use App\Models\Assets;
use App\Models\Merk;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Events\TransactionsDataChanged;

class TransactionsAdminController extends Controller
{
    public function index()
    {
        $transactions = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.name_holder', '=', 'customer.id')
            ->join('assets', 'transactions.asset_code', '=', 'assets.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'customer.mapping as customer_mapping',
                'assets.code as tagging'
            )
            ->get();

        return view('transactions.index', compact('transactions'));
    }
    public function indexmutasi(Request $request)
    {

        $query = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->join('inventory', 'transactions.asset_tagging', '=', 'inventory.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'customer.mapping as customer_mapping',
                'inventory.tagging as tagging'
            )
            ->where('transactions.approval_status', 'Approved');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('inventory.tagging', 'like', "%$search%")
                    ->orWhere('transactions.jenis_aset', 'like', "%$search%")
                    ->orWhere('merk.name', 'like', "%$search%")
                    ->orWhere('customer.name', 'like', "%$search%");
            });
        }

        $transactions = $query->get();
        return view('transactions.indexmutasi', compact('transactions'));
    }

    //HANDOVER

    public function create()
    {

        $customers = Customer::where('role', '!=', 'Admin')->get();
        $merks = Merk::all();
        $usedAssetTaggings = DB::table('transactions')->pluck('asset_code')->toArray();
        $assetss = Assets::whereNotIn('id', $usedAssetTaggings)->get();
        $assetTaggingAvailable = $assetss->isNotEmpty();
        $namesAvailable = $customers->isNotEmpty();

        return view('transactions.handover', compact('merks', 'customers', 'assetss', 'assetTaggingAvailable', 'namesAvailable'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'type_transactions' => 'required|string',
            'approval_status' => 'required|string',
            'asset_code' => 'required|array',
            'name_holder' => 'required|exists:customer,id',
            'location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'documentation' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare data for each asset in asset_code
        foreach ($request->input('asset_code') as $assetCode) {
            // Find the asset and customer details
            $asset = Assets::findOrFail($assetCode);
            $customer = Customer::findOrFail($request->input('name_holder'));

            // Handle the documentation upload
            $documentationPath = null;
            if ($request->hasFile('documentation')) {
                $documentationPath = $request->file('documentation')->store('documentation', 'public');
            }

            Transactions::create([
                'type_transactions' => 'Handover',
                'asset_code' => $assetCode,
                'merk' => $asset->merk,
                'category_asset' => $asset->category,
                'spesification' => $asset->spesification,
                'serial_number' => $asset->serial_number,
                'name_holder' => $customer->id,
                'position' => $customer->mapping,
                'location' => $request->input('location'),
                'status' => $request->input('status', 'Operation'),
                'condition' => $request->input('condition', 'New'),
                'approval_status' => $request->input('approval_status'),
                'previous_customer_name' => $customer->id,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'reason' => $request->input('reason', ''),
                'note' => $request->input('note', ''),
                'documentation' => $documentationPath,
            ]);
            $asset->update([
                'handover_date' => now(), // Set to the current date and time
                'name_holder' => $customer->id,
                'status' => $validatedData['status'] ?? 'Operation',
                'location' => $validatedData['location'],
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    //RETURN
    public function indexreturn(Request $request)
    {
        $query = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.name_holder', '=', 'customer.id')
            ->join('assets', 'transactions.asset_code', '=', 'assets.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'customer.mapping as customer_mapping',
                'assets.code as tagging'
            )
            ->where('transactions.approval_status', 'Approved');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('assets.code', 'like', "%$search%")
                    ->orWhere('transactions.category_asset', 'like', "%$search%")
                    ->orWhere('merk.name', 'like', "%$search%")
                    ->orWhere('customer.name', 'like', "%$search%");
            });
        }
        $transactions = $query->get();
        return view('transactions.indexreturn', compact('transactions'));
    }
    public function returnAsset($id)
    {
        // Fetch the asset details including related merk and customer
        $asset = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.name_holder', '=', 'customer.id')
            ->select('transactions.*', 'merk.name as merk_name', 'customer.name as customer_name')
            ->where('transactions.id', $id)
            ->first();

        // If the asset is not found, abort with a 404 error
        if (!$asset) {
            abort(404);
        }

        // If the approval status is "Pending," show the return form
        $merks = Merk::all();
        $customers = Customer::all();
        $inventories = Assets::all();

        return view('transactions.returnform', compact('asset', 'merks', 'customers', 'inventories'));
    }


    public function returnUpdate(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name_holder' => 'required|exists:customer,id',
            'location' => 'required|string',
            'reason' => 'required|string|max:255', // Ensure max length if needed
            'note' => 'nullable|string|max:1000', // Ensure max length if needed
            'documentation' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Find the transaction asset
        $asset = Transactions::findOrFail($id);
        $customer = Customer::find($request->input('name_holder'));

        // Determine the most recent approved customer
        $latestApprovedAsset = Transactions::where('approval_status', 'Approved')
            ->orderBy('updated_at', 'desc')
            ->first();

        $previousCustomerName = null;
        if ($latestApprovedAsset) {
            // Find the customer name based on the name_holder ID
            $previousCustomer = Customer::find($latestApprovedAsset->name_holder);
            $previousCustomerName = $previousCustomer ? $previousCustomer->name : null; // Assuming you want the name
        }

        // Prepare data for update in Transactions table
        $assetData = [
            'previous_customer_name' => $previousCustomerName, // Store the name of the latest approved customer
            'name_holder' => $request->input('name_holder'),
            'mapping' => $customer->mapping,
            'location' => $request->input('location'),
            'approval_status' => 'Pending', // Status set to "Pending"
            'type_transactions' => 'Return',
            'reason' => $request->input('reason'), // Ensure this is correctly assigned
            'note' => $request->input('note'),
        ];

        // Handle documentation file
        if ($request->hasFile('documentation')) {
            // Delete old documentation file if exists
            if ($asset->documentation && \Storage::exists('public/' . $asset->documentation)) {
                \Storage::delete('public/' . $asset->documentation);
            }

            $file = $request->file('documentation');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/documents', $filename);
            $assetData['documentation'] = str_replace('public/', '', $filePath); // Save relative path
        } else {
            // If no file uploaded, retain the existing documentation file if it exists
            $assetData['documentation'] = $asset->documentation;
        }

        // Update the transaction in the Transactions table
        $asset->update($assetData);

        // Update related data in the Assets table
        $assetToUpdate = Assets::find($asset->asset_code); // Adjusted to use find directly
        if ($assetToUpdate) {
            $assetToUpdate->update([
                'name_holder' => 'Not Yet Handover',
                'location' => 'In Inventory',
                'status' => 'Inventory',
                'handover_date' => null,
            ]);
        }

        // Redirect to the index page with a success message
        return redirect()->route('transactions.indexreturn')->with('success', 'The asset return request has been successfully submitted and is awaiting approval.');
    }





    public function edit($id)
    {
        $asset = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->select('transactions.*', 'merk.name as merk_name', 'customer.name as customer_name')
            ->where('transactions.id', $id)
            ->first();

        // Mengambil semua merk
        $merks = Merk::all();

        // Mengambil semua pelanggan dan memfilter yang sedang dipilih
        $customers = Customer::all()->filter(function ($customer) use ($asset) {
            return $customer->id != $asset->nama;
        });

        // Mengambil semua inventaris
        $inventories = Assets::all();

        // Mengirim data ke view
        return view('transactions.edit', compact('asset', 'merks', 'customers', 'inventories'));
    }


    public function pindah($id)
    {
        $asset = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->select('transactions.*', 'merk.name as merk_name', 'customer.name as customer_name')
            ->where('transactions.id', $id)
            ->first();

        $merks = Merk::all();
        $customers = Customer::where('role', '!=', 'Admin')->get();
        $inventories = Assets::all();

        return view('transactions.pindahtangan', compact('asset', 'merks', 'customers', 'inventories'));
    }

    public function pindahUpdate(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'nama' => 'required|exists:customer,id',
            'lokasi' => 'required|string',
            'documentation' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Find the asset
        $asset = Transactions::findOrFail($id);
        $customer = Customer::find($request->input('nama'));

        // Determine the most recent approved customer
        $latestApprovedAsset = Transactions::where('approval_status', 'Approved')
            ->orderBy('updated_at', 'desc')
            ->first();

        $previousCustomerName = null;
        if ($latestApprovedAsset) {
            $previousCustomerName = $latestApprovedAsset->nama; // Get the name of the last approved asset
        }

        // Prepare data for update
        $assetData = [
            'previous_customer_name' => $previousCustomerName, // Set to the latest approved customer name
            'nama' => $request->input('nama'),
            'mapping' => $customer->mapping,
            'lokasi' => $request->input('lokasi'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'keterangan' => $request->input('keterangan'),
            'note' => $request->input('note'),
            'approval_status' => $request->input('approval_status', ''),
            'aksi' => $request->input('aksi', ''),
        ];

        // Handle documentation file
        if ($request->hasFile('documentation')) {
            // Delete old documentation file if exists
            if ($asset->documentation && \Storage::exists($asset->documentation)) {
                \Storage::delete($asset->documentation);
            }

            $file = $request->file('documentation');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/uploads/documentation', $filename);
            $assetData['documentation'] = str_replace('public/', '', $filePath); // Save relative path
        }

        // Update the asset
        $asset->update($assetData);

        return redirect()->route('transactions.index')->with('success', 'Asset has been successfully mutated, waiting for user approval.');
    }









    public function update(Request $request, $id)
    {
        $request->validate([
            'asset_tagging' => 'required|exists:inventory,id',
            'nama' => 'required|exists:customer,id',
            'status' => 'required|string',
            'o365' => 'required|string',
            'keterangan' => 'nullable|string',
            'note' => 'nullable|string',
            'kondisi' => 'required|in:Good,Exception,Bad,New',
            'approval_status' => 'nullable|string|in:Pending,Approved', // Ensure valid status
            'documentation' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Updated to nullable
        ]);

        $asset = Transactions::findOrFail($id);
        $inventory = Assets::find($request->input('asset_tagging'));
        $customer = Customer::find($request->input('nama'));

        $assetData = [
            'asset_tagging' => $request->input('asset_tagging'),
            'jenis_aset' => $inventory->asets,
            'merk' => $inventory->merk,
            'type' => $inventory->type,
            'serial_number' => $inventory->seri,
            'nama' => $request->input('nama'),
            'mapping' => $customer->mapping,
            'o365' => $request->input('o365'),
            'lokasi' => $request->input('lokasi', ''),
            'status' => $request->input('status'),
            'keterangan' => $request->input('keterangan'),
            'note' => $request->input('note'),
            'kondisi' => $request->input('kondisi', ''),
            'approval_status' => $request->input('approval_status', ''), // Use input value
        ];

        // Check if documentation file is uploaded
        if ($request->hasFile('documentation')) {
            // Delete old documentation file if exists
            if ($asset->documentation && \Storage::exists($asset->documentation)) {
                \Storage::delete($asset->documentation);
            }

            $file = $request->file('documentation');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/uploads/documentation', $filename);
            $assetData['documentation'] = str_replace('public/', '', $filePath); // Save relative path
        } else {
            // Keep the old file if no new file is uploaded
            $assetData['documentation'] = $asset->documentation;
        }

        $asset->update($assetData);

        return redirect()->route('transactions.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy($id)
    {
        // Temukan transaksi berdasarkan ID
        $transaction = Transactions::findOrFail($id);

        // Cek apakah transaksi memiliki status "Rejected" dan tipe "Handover"
        if ($transaction->approval_status === 'Rejected' && $transaction->type_transactions === 'Handover') {

            // Temukan aset yang terkait dengan transaksi ini
            $asset = Assets::find($transaction->asset_code); // Pastikan ada relasi asset_id di tabel transactions

            if ($asset) {
                // Update status, name_holder, dan lokasi di tabel assets
                $asset->update([
                    'status' => 'Inventory',
                    'name_holder' => 'Not Yet Handover',
                    'handover_date' => null,
                    'location' => 'In inventory',
                ]);
            }
        }

        // Hapus transaksi
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'The transaction has been canceled, and the asset has been updated.');
    }



    public function show($id)
    {
        $asset = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->join('assets', 'transactions.asset_tagging', '=', 'assets.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'customer.mapping as customer_mapping',
                'assets.code as tagging'
            )
            ->where('transactions.id', $id)
            ->first();

        if (!$asset) {
            abort(404);
        }

        return view('transactions.show', compact('asset'));
    }
    public function history()
    {
        // Retrieve all records from transaction_history table with left joins to related tables
        $history = DB::table('transaction_history')
            ->leftJoin('assets', 'transaction_history.asset_code', '=', 'assets.id')
            ->leftJoin('merk', 'transaction_history.merk', '=', 'merk.id')
            ->leftJoin('customer', 'transaction_history.name_holder', '=', 'customer.id')
            ->select('transaction_history.*', 'merk.name as merk_name', 'customer.name as name_holder','assets.code as asset_code' )
            ->orderBy('transaction_history.created_at', 'DESC')
            ->get();

        return view('transactions.history', compact('history'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Transactions::query()
                ->with('customer', 'merk') // Include relationships if needed
                ->select(['id', 'tagging', 'customer_name', 'jenis_aset', 'merk_name', 'lokasi', 'status', 'approval_status']);

            return DataTables::of($transactions)
                ->addColumn('actions', function ($asset) {
                    return view('partials.datatables-actions', compact('asset'));
                })
                ->make(true);
        }
    }


    public function reject($id)
    {
        $asset = Transactions::findOrFail($id);

        // Define the valid actions for rejection
        $validActions = ['Handover', 'Mutasi', 'Return'];

        if (in_array($asset->aksi, $validActions)) {
            $asset->update(['approval_status' => 'Rejected']);
            return redirect()->back()->with('status', 'Asset has been rejected.');
        }

        // Handle invalid or unexpected actions
        return redirect()->back()->with('error', 'Unexpected action type.');
    }
    public function approveMultiple(Request $request)
    {
        $selectedTransactions = $request->input('transactions'); // Get the selected asset IDs

        // Validate that at least one asset was selected
        if (empty($selectedTransactions)) {
            return redirect()->back()->with('error', 'Please select at least one asset to approve.');
        }

        // Ensure the asset IDs exist in the database (optional)
        $transactions = Transactions::whereIn('id', $selectedTransactions)->get();

        if ($transactions->isEmpty()) {
            return redirect()->back()->with('error', 'Selected transactions do not exist.');
        }

        // Redirect to the serahterima view with the selected asset IDs
        return redirect()->route('transactions.serahterima', ['ids' => implode(',', $selectedTransactions)]);
    }
    public function bulkAction(Request $request)
    {
        $selectedTransactions = $request->input('transactions'); // Get the selected asset IDs

        // Validate that at least one asset was selected
        if (empty($selectedTransactions)) {
            return redirect()->back()->with('error', 'Please select at least one asset.');
        }

        // Determine the action (approve or reject)
        if ($request->input('action') === 'approve') {
            // Redirect to serahterima for approval
            return redirect()->route('transactions.serahterima', ['ids' => implode(',', $selectedTransactions)]);
        } elseif ($request->input('action') === 'reject') {
            // Reject the selected transactions
            foreach ($selectedTransactions as $id) {
                $asset = Transactions::find($id);
                if ($asset) {
                    $asset->update(['approval_status' => 'Rejected']);
                }
            }
            return redirect()->back()->with('status', 'Selected transactions have been rejected.');
        }

        return redirect()->back()->with('error', 'Unexpected action.');
    }




    public function rollbackMutasi($id)
    {
        // Temukan transaksi berdasarkan ID
        $transaction = Transactions::findOrFail($id);

        // Cek apakah ada previous_customer_name untuk rollback
        if (!$transaction->previous_customer_name) {
            return redirect()->route('transactions.index')->with('error', 'No previous customer name available for rollback.');
        }

        // Temukan aset terkait berdasarkan asset_id di transaksi
        $asset = Assets::findOrFail($transaction->asset_code); // Ganti 'asset_id' dengan kolom yang sesuai

        // Rollback ke previous customer name
        $transaction->update([
            'name_holder' => $transaction->name_holder,
            'previous_customer_name' => null, // Hapus previous customer name
            'approval_status' => 'Approved', // Atur status sesuai kebutuhan
            'type_transactions' => 'Rollback' // Perbarui tindakan jika diperlukan
        ]);

        // Update data aset terkait
        $asset->update([
            'name_holder' => $transaction->name_holder, // Mengupdate name_holder dengan previous_customer_name
            'location' => $transaction->location, // Menyesuaikan lokasi jika diperlukan
            'status' => 'Operation', // Atur status sesuai kebutuhan
            'handover_date' => now(), // Mengatur tanggal serah terima ke waktu saat ini
        ]);

        return redirect()->route('transactions.index')->with('success', 'Asset name rolled back successfully.');
    }


    public function track($id)
    {
        $asset = Transactions::findOrFail($id);

        return view('transactions.track', compact('asset'));
    }

}





