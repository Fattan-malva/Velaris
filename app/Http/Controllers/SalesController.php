<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Merk;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Assets;
use Illuminate\Support\Facades\DB;


class SalesController extends Controller
{
    public function salesserahterima($id)
    {
        $asset = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->join('customer', 'assets.nama', '=', 'customer.id')
            ->select('assets.*', 'merk.name as merk_name', 'customer.name as customer_name')
            ->where('assets.id', $id)
            ->first();

        $merks = Merk::all();
        $customers = Customer::all();
        $inventories = Inventory::all();

        return view('sales.salesserahterima', compact('asset', 'merks', 'customers', 'inventories'));
    }
    public function updateserahterimaSales(Request $request, $id)
    {
        // Validate input
        $validatedData = $request->validate([
            'asset_tagging' => 'required|exists:inventory,id',
            'nama' => 'required|exists:customer,id',
            'status' => 'required|string',
            'o365' => 'required|string',
            'kondisi' => 'required|in:Good,Exception,Bad,New',
            'lokasi' => 'nullable|string',
            'documentation' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Find the asset by ID
            $asset = Assets::findOrFail($id);
            $inventory = Inventory::findOrFail($validatedData['asset_tagging']);
            $customer = Customer::findOrFail($validatedData['nama']);

            // Prepare data for update
            $assetData = [
                'asset_tagging' => $validatedData['asset_tagging'],
                'jenis_aset' => $inventory->asets,
                'merk' => $inventory->merk,
                'type' => $inventory->type,
                'serial_number' => $inventory->seri,
                'nama' => $validatedData['nama'],
                'mapping' => $customer->mapping,
                'o365' => $validatedData['o365'],
                'lokasi' => $validatedData['lokasi'] ?? '',
                'status' => $validatedData['status'],
                'kondisi' => $validatedData['kondisi'],
                'approval_status' => $request->input('approval_status', ''),
            ];

            // Handle documentation file if present
            if ($request->hasFile('documentation')) {
                // Delete old documentation if exists
                if ($asset->documentation && \Storage::exists('public/' . $asset->documentation)) {
                    \Storage::delete('public/' . $asset->documentation);
                }

                // Save new documentation
                $file = $request->file('documentation');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('public/uploads/documentation', $filename);
                $assetData['documentation'] = 'uploads/documentation/' . $filename;
            }

            // Update the asset with new data
            $asset->update($assetData);

            // Redirect with success message
            return redirect()->route('shared.homeSales')->with('success', 'Asset Approved successfully.');

        } catch (\Exception $e) {
            // Log error and redirect with error message
            \Log::error('Failed to update asset:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('Failed to approve asset. Please try again.');
        }
    }

    public function index()
    {
        $saleses = Sales::all();  // Fetch sales data
        $inventories = Inventory::all();  // Fetch inventories data
        $customers = Customer::all();  // Fetch customers data

        return view('sales.index', compact('saleses', 'inventories', 'customers'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'departement' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'nama_asset' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        try {
            Sales::create($validatedData);

            // Return JSON response for success
            return response()->json(['success' => true, 'message' => 'Sales created successfully.']);
        } catch (\Exception $e) {
            // Return JSON response for error with detailed message
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $sales = Sales::findOrFail($id);
        return response()->json($sales);
    }

    public function update(Request $request, $id)
{
    $sales = Sales::find($id);
    if ($sales) {
        $sales->status = 'Approved'; // Set the status to 'Approved'
        // Update other fields if needed
        $sales->save();
        
        return redirect()->route('sales.index')->with('success', 'Status updated successfully!');
    }

    return redirect()->route('sales.index')->with('error', 'Sales record not found.');
}

    public function destroy($id)
    {
        try {
            $sales = Sales::findOrFail($id);
            $sales->delete();

            return redirect()->route('sales.index')->with('success', 'Sales deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch the foreign key constraint exception
            return redirect()->route('sales.index')->with('error', 'Unable to delete Sales. It is still referenced in other records.');
        }
    }

}
