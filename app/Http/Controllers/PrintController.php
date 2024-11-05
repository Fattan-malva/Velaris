<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Inventory;
use Intervention\Image\Facades\Image;

class PrintController extends Controller
{


    public function handover(Request $request)
    {
        $tagNumber = $request->query('asset_tagging');
        $changedAtDate = $request->query('changed_at'); // Expecting format like '12-09-2024'

        // Convert the date to 'YYYY-MM-DD' format
        $changedAtDate = Carbon::createFromFormat('d-m-Y', $changedAtDate)->format('Y-m-d');

        // Log the converted date to ensure it's correct
        \Log::info('Converted Date: ' . $changedAtDate);

        // Retrieve the inventory record based on the asset_tagging
        $inventory = DB::table('inventory')
            ->where('tagging', $tagNumber)
            ->first();

        if (!$inventory) {
            \Log::error('Inventory not found for tag: ' . $tagNumber);
            return view('prints.handover')->with('error', 'Data not found in inventory');
        }

        // Retrieve the handover record based on the inventory ID and changed_at date
        $handover = DB::table('asset_history')
            ->join('customer', 'asset_history.nama_old', '=', 'customer.id')
            ->join('merk', 'asset_history.merk_old', '=', 'merk.id')
            ->where('asset_history.asset_tagging_new', $inventory->id)
            ->whereDate('asset_history.changed_at', $changedAtDate) // Compare only the date part
            ->select(
                'asset_history.*',
                'customer.name as customer_name',
                'customer.nrp as customer_nrp',
                'customer.mapping as customer_mapping',
                'merk.name as merk_name'
            )
            ->first();

        if (!$handover) {
            \Log::error('Handover data not found for asset tagging: ' . $inventory->id . ' and date: ' . $changedAtDate);
            return view('prints.handover')->with('error', 'Data not found in asset history');
        }

        return view('prints.handover', ['handover' => $handover, 'inventory' => $inventory, 'tagging' => $inventory->tagging]);
    }






    public function mutation(Request $request)
    {
        $tagNumber = $request->query('asset_tagging');
        $changedAtDate = $request->query('changed_at'); // Expecting format like '12-09-2024'

        // Convert the date to 'YYYY-MM-DD' format
        $changedAtDate = Carbon::createFromFormat('d-m-Y', $changedAtDate)->format('Y-m-d');

        // Log the converted date to ensure it's correct
        \Log::info('Converted Date: ' . $changedAtDate);

        // Retrieve inventory record based on asset_tagging
        $inventory = DB::table('inventory')
            ->where('tagging', $tagNumber)
            ->first();

        if (!$inventory) {
            \Log::error('Inventory not found for tag: ' . $tagNumber);
            return view('prints.mutation')->with('error', 'Data not found in inventory');
        }

        // Retrieve the mutation record based on inventory ID and changed_at date
        $mutation = DB::table('asset_history')
            ->join('customer as old_customer', 'asset_history.nama_old', '=', 'old_customer.id')
            ->join('customer as new_customer', 'asset_history.nama_new', '=', 'new_customer.id')
            ->join('merk', 'asset_history.merk_new', '=', 'merk.id')
            ->where('asset_history.asset_tagging_new', $inventory->id)
            ->where('asset_history.action', 'UPDATE')
            ->whereDate('asset_history.changed_at', $changedAtDate) // Compare only the date part
            ->whereColumn('asset_history.nama_old', '!=', 'asset_history.nama_new') // Ensure old and new names are different
            ->orderBy('asset_history.changed_at', 'DESC') // Order by the most recent
            ->select(
                'asset_history.*',
                'old_customer.name as old_customer_name',
                'old_customer.nrp as old_customer_nrp',
                'new_customer.name as new_customer_name',
                'new_customer.nrp as new_customer_nrp',
                'merk.name as merk_name'
            )
            ->first();

        \Log::info('Mutation Data: ', (array) $mutation);

        if (!$mutation) {
            \Log::error('Mutation data not found for asset tagging: ' . $inventory->id . ' and date: ' . $changedAtDate);
            return view('prints.mutation')->with('error', 'Data not found in asset history');
        }

        // Pass data to view
        return view('prints.mutation', [
            'mutation' => $mutation,
            'inventory' => $inventory,
            'tagging' => $inventory->tagging,
            'isDifferent' => $mutation->old_customer_name !== $mutation->new_customer_name
        ]);
    }







    public function return(Request $request)
    {
        $tagNumber = $request->query('asset_tagging');
        $changedAtDate = $request->query('changed_at'); // Expecting format like '12-09-2024'

        // Convert the date to 'YYYY-MM-DD' format
        $changedAtDate = Carbon::createFromFormat('d-m-Y', $changedAtDate)->format('Y-m-d');

        // Log the converted date to ensure it's correct
        \Log::info('Converted Date: ' . $changedAtDate);

        // Retrieve inventory record based on asset_tagging
        $inventory = DB::table('inventory')
            ->where('tagging', $tagNumber)
            ->first();

        if (!$inventory) {
            \Log::error('Inventory not found for tag: ' . $tagNumber);
            return view('prints.return')->with('error', 'Data not found in inventory');
        }

        // Retrieve the return record based on inventory ID and changed_at date
        $return = DB::table('asset_history')
            ->join('customer', 'asset_history.nama_old', '=', 'customer.id')
            ->join('merk', 'asset_history.merk_old', '=', 'merk.id')
            ->where('asset_history.asset_tagging_old', $inventory->id)
            ->where('asset_history.action', 'DELETE')
            ->whereDate('asset_history.changed_at', $changedAtDate) // Compare only the date part
            ->select(
                'asset_history.*',
                'customer.name as customer_name',
                'customer.nrp as customer_nrp',
                'customer.mapping as customer_mapping',
                'merk.name as merk_name'
            )
            ->first();

        \Log::info('Return Data: ', (array) $return);

        if (!$return) {
            \Log::error('Return data not found for asset tagging: ' . $inventory->id . ' and date: ' . $changedAtDate);
            return view('prints.return')->with('error', 'Data not found in asset history');
        }

        // Pass data to view
        return view('prints.return', [
            'return' => $return,
            'inventory' => $inventory,
            'tagging' => $inventory->tagging
        ]);
    }
    public function print(Request $request)
    {
        // Get the IDs from the request
        $ids = explode(',', $request->query('ids'));
    
        // Fetch inventories with their merk names
        $inventories = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->select('assets.*', 'merk.name as merk_name')
            ->whereIn('assets.id', $ids)
            ->get();
    
        // Handle not found case
        if ($inventories->isEmpty()) {
            return redirect()->back()->with('error', 'No assets found for the selected IDs.');
        }
    
        // Generate QR codes for each inventory
        $qrCodes = [];
        foreach ($inventories as $inventory) {
            $url = route('auth.detailQR', ['id' => $inventory->id]); // Adjust route name if necessary
            $qrCodes[] = [
                'inventory' => $inventory,
                'qrCode' => QrCode::size(120)->generate($url), // Generate QR code with 200x200 pixels
            ];
        }
    
        // Return the print view with multiple QR codes
        return view('prints.qr_code', compact('qrCodes'));
    }
    

    // app/Http/Controllers/PrintController.php
    public function showAssetDetail($id)
    {
        // Fetch inventory with merk name
        $inventory = DB::table('inventory')
            ->join('merk', 'inventory.merk', '=', 'merk.id')
            ->select('inventory.*', 'merk.name as merk_name')
            ->where('inventory.id', $id)
            ->first();

        if (!$inventory) {
            abort(404); // Handle not found
        }

        // Return the view with asset details
        return view('auth.detailQR', compact('inventory'));
    }

}
