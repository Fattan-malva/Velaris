<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Assets;
use App\Models\Inventory;
use App\Models\Merk;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Events\AssetsDataChanged;

class TransactionsAdminController extends Controller
{
    public function index()
    {
        $transactions = DB::table('transactions')
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
            ->get();

        return view('transactions.index', compact('transactions'));
    }
    public function indexmutasi(Request $request)
    {
        // Initialize the query builder
        $query = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->join('inventory', 'transactions.asset_tagging', '=', 'inventory.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'customer.mapping as customer_mapping', // Select the mapping from customer
                'inventory.tagging as tagging'
            )
            ->where('transactions.approval_status', 'Approved'); // Filter based on status 'Mutasi'

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('inventory.tagging', 'like', "%$search%")
                    ->orWhere('transactions.jenis_aset', 'like', "%$search%")
                    ->orWhere('merk.name', 'like', "%$search%")
                    ->orWhere('customer.name', 'like', "%$search%");
            });
        }

        // Execute the query and get the results
        $transactions = $query->get();

        // Return the view with transactions
        return view('transactions.indexmutasi', compact('transactions'));
    }
    public function indexreturn(Request $request)
    {
        // Initialize the query builder
        $query = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
            ->join('inventory', 'transactions.asset_tagging', '=', 'inventory.id') // Join inventory to get tagging
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'customer.mapping as customer_mapping', // Select the mapping from customer
                'inventory.tagging as tagging'
            )
            ->where('transactions.approval_status', 'Approved'); // Filter based on status 'Mutasi'

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('inventory.tagging', 'like', "%$search%")
                    ->orWhere('transactions.jenis_aset', 'like', "%$search%")
                    ->orWhere('merk.name', 'like', "%$search%")
                    ->orWhere('customer.name', 'like', "%$search%");
            });
        }

        // Execute the query and get the results
        $transactions = $query->get();

        // Return the view with transactions
        return view('transactions.indexreturn', compact('transactions'));
    }




    public function create()
    {
        // Retrieve all customers, no filtering needed
        $customers = Customer::where('role', '!=', 'Admin')->get();

        // Retrieve all merks
        $merks = Merk::all();

        // Retrieve all inventories that have not been used in transactions
        $usedAssetTaggings = DB::table('transactions')->pluck('asset_tagging')->toArray();
        $inventories = Assets::whereNotIn('id', $usedAssetTaggings)->get();

        // Determine availability of asset taggings and names
        $assetTaggingAvailable = $inventories->isNotEmpty();
        $namesAvailable = $customers->isNotEmpty();

        return view('transactions.create', compact('merks', 'customers', 'inventories', 'assetTaggingAvailable', 'namesAvailable'));
    }

    public function edit($id)
    {
        // Mengambil data aset beserta merk dan pelanggan terkait
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
        $inventories = Inventory::all();

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
        $inventories = Inventory::all();

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
        $asset = Assets::findOrFail($id);
        $customer = Customer::find($request->input('nama'));

        // Determine the most recent approved customer
        $latestApprovedAsset = Assets::where('approval_status', 'Approved')
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




    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'asset_tagging' => 'required|array',
            'asset_tagging.*' => 'exists:inventory,id',
            'nama' => 'required|exists:customer,id',
            'status' => 'required|string',
            'o365' => 'required|string',
            'kondisi' => 'required|in:Good,Exception,Bad,New',
            'approval_status' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'documentation' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        // Find the customer by 'nama' input
        $customer = Customer::find($request->input('nama'));

        // Handle documentation upload
        $documentationPath = null;
        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/documents', $filename);
            $documentationPath = 'documents/' . $filename;
        }

        // Loop through each selected asset tagging and create transactions
        foreach ($request->input('asset_tagging') as $assetId) {
            $inventory = Inventory::find($assetId);

            // Prepare asset data
            $assetData = [
                'asset_tagging' => $assetId,
                'jenis_aset' => $inventory->asets,
                'merk' => $inventory->merk,
                'type' => $inventory->type,
                'serial_number' => $inventory->seri,
                'nama' => $request->input('nama'),
                'mapping' => $customer->mapping,
                'o365' => $request->input('o365'),
                'lokasi' => $request->input('lokasi', ''),
                'status' => $request->input('status'),
                'kondisi' => $request->input('kondisi', 'New'),
                'approval_status' => $request->input('approval_status'),
                'aksi' => $request->input('aksi', 'Handover'),
                'previous_customer_name' => $customer->name,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'keterangan' => $request->input('keterangan', ''),
                'note' => $request->input('note', ''),
                'documentation' => $documentationPath,
            ];

            // Create asset record for each tagging
            $asset = Assets::create($assetData);
            // Broadcast the event for each created asset immediately
            // event(new AssetsDataChanged($asset, 'created'));
        }

        // Redirect back to the transactions index with a success message
        return redirect()->route('transactions.index')->with('success', 'Assets have been successfully handed over.');
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

        $asset = Assets::findOrFail($id);
        $inventory = Inventory::find($request->input('asset_tagging'));
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
        $asset = Assets::findOrFail($id);
        $asset->delete();

        return redirect()->route('transactions.index')->with('success', 'The asset has been successfully returned to Inventory');
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
        $history = DB::table('asset_history')
            ->leftJoin('inventory', 'asset_history.asset_tagging_old', '=', 'inventory.id')
            ->leftJoin('merk', 'asset_history.merk_old', '=', 'merk.id')
            ->leftJoin('customer as old_customer', 'asset_history.nama_old', '=', 'old_customer.id')
            ->leftJoin('customer as new_customer', 'asset_history.nama_new', '=', 'new_customer.id')
            ->select(
                'asset_history.asset_id',
                'inventory.tagging as asset_tagging',
                'merk.name as merk',
                'asset_history.jenis_aset_old',
                'old_customer.name as nama_old',
                'new_customer.name as nama_new',
                'asset_history.changed_at',
                'asset_history.action',
                'asset_history.keterangan',
                'asset_history.note',
                'asset_history.documentation_old',
                // Select documentation_new only for UPDATE actions
                DB::raw('(SELECT next.documentation_new 
                FROM asset_history as next 
                WHERE next.asset_tagging_old = asset_history.asset_tagging_old 
                AND next.changed_at < asset_history.changed_at 
                AND next.action = "UPDATE" 
                ORDER BY next.changed_at DESC 
                LIMIT 1) as documentation_new')

            )
            ->whereIn('asset_history.action', ['CREATE', 'UPDATE', 'DELETE'])
            ->orderBy('asset_history.changed_at', 'DESC')
            ->get()
            ->groupBy('asset_tagging')
            ->map(function ($items) {
                // Filter out unchanged updates
                $filteredItems = $items->filter(function ($item) {
                    return $item->action === 'CREATE' ||
                        ($item->action === 'UPDATE' && $item->nama_old !== $item->nama_new) ||
                        ($item->action === 'DELETE' && empty($item->documentation_old));
                });

                // If there are DELETE records with empty documentation_old, get keterangan
                $keterangan = $filteredItems->filter(function ($item) {
                    return $item->action === 'DELETE' && empty($item->documentation_old);
                })->pluck('keterangan')->first();

                $note = $filteredItems->filter(function ($item) {
                    return $item->action === 'DELETE' && empty($item->documentation_old);
                })->pluck('note')->first();

                // Group by changed_at to remove duplicates
                $uniqueItems = $filteredItems->groupBy('changed_at')->map(function ($itemsByTime) {
                    return $itemsByTime->unique(function ($item) {
                        return $item->asset_tagging . '-' . $item->action;
                    })->values();
                })->flatten()->sortBy('changed_at');

                // Add keterangan field from DELETE if available
                $uniqueItems->each(function ($item) use ($keterangan, $note) {
                    if ($item->action === 'DELETE') {
                        $item->keterangan = $keterangan;
                        $item->note = $note;
                    }
                });

                return $uniqueItems;
            });

        return view('transactions.history', compact('history'));
    }



    public function returnAsset($id)
    {
        // Fetch the asset details including related merk and customer
        $asset = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.nama', '=', 'customer.id')
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
        $inventories = Inventory::all();

        return view('transactions.return', compact('asset', 'merks', 'customers', 'inventories'));
    }


    public function returnUpdate(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'nama' => 'required|exists:customer,id',
            'lokasi' => 'required|string',
            'keterangan' => 'required|string',
            'note' => 'nullable|string',
            'documentation' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        // Find the asset
        $asset = Assets::findOrFail($id);
        $customer = Customer::find($request->input('nama'));

        // Determine the most recent approved customer
        $latestApprovedAsset = Assets::where('approval_status', 'Approved')
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
            'approval_status' => 'Pending', // Status set to "Pending"
            'aksi' => $request->input('aksi'),
            'keterangan' => $request->input('keterangan'),
            'note' => $request->input('note'),
        ];

        // Handle documentation file
        if ($request->hasFile('documentation')) {
            // Delete old documentation file if exists
            if ($asset->documentation && \Storage::exists($asset->documentation)) {
                \Storage::delete($asset->documentation);
            }

            $file = $request->file('documentation');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/documents', $filename);
            $assetData['documentation'] = str_replace('public/', '', $filePath); // Save relative path
        } else {
            // If no file uploaded, retain the existing documentation file if it exists
            $assetData['documentation'] = $asset->documentation;
        }

        // Update the asset
        $asset->update($assetData);

        // Redirect to the index page with a success message
        return redirect()->route('transactions.indexreturn')->with('success', 'The asset return request has been successfully submitted and is awaiting approval.');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $transactions = Assets::query()
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
        $asset = Assets::findOrFail($id);

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
        $selectedAssets = $request->input('transactions'); // Get the selected asset IDs

        // Validate that at least one asset was selected
        if (empty($selectedAssets)) {
            return redirect()->back()->with('error', 'Please select at least one asset to approve.');
        }

        // Ensure the asset IDs exist in the database (optional)
        $transactions = Assets::whereIn('id', $selectedAssets)->get();

        if ($transactions->isEmpty()) {
            return redirect()->back()->with('error', 'Selected transactions do not exist.');
        }

        // Redirect to the serahterima view with the selected asset IDs
        return redirect()->route('transactions.serahterima', ['ids' => implode(',', $selectedAssets)]);
    }
    public function bulkAction(Request $request)
    {
        $selectedAssets = $request->input('transactions'); // Get the selected asset IDs

        // Validate that at least one asset was selected
        if (empty($selectedAssets)) {
            return redirect()->back()->with('error', 'Please select at least one asset.');
        }

        // Determine the action (approve or reject)
        if ($request->input('action') === 'approve') {
            // Redirect to serahterima for approval
            return redirect()->route('transactions.serahterima', ['ids' => implode(',', $selectedAssets)]);
        } elseif ($request->input('action') === 'reject') {
            // Reject the selected transactions
            foreach ($selectedAssets as $id) {
                $asset = Assets::find($id);
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
        // Find the asset
        $asset = Assets::findOrFail($id);

        // Check if there is a previous customer name to roll back to
        if (!$asset->previous_customer_name) {
            return redirect()->route('transactions.index')->with('error', 'No previous customer name available for rollback.');
        }

        // Rollback to the previous customer name
        $asset->update([
            'nama' => $asset->previous_customer_name,
            'previous_customer_name' => null, // Clear the previous customer name
            'approval_status' => 'Approved', // Set to Pending or another status as needed
            'aksi' => 'Rollback' // Update the action or keep it as needed
        ]);

        return redirect()->route('transactions.index')->with('success', 'Asset name rolled back successfully.');
    }
    public function track($id)
    {
        $asset = Assets::findOrFail($id);

        return view('transactions.track', compact('asset'));
    }

}





