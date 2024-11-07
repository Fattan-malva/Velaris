<?php

namespace App\Http\Controllers;
use App\Models\TransactionsHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Assets;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;


class PrintController extends Controller
{

    public function handover($id)
    {
        $history = DB::table('transaction_history')
            ->join('customer', 'transaction_history.name_holder', '=', 'customer.id') 
            ->join('assets', 'transaction_history.asset_code', '=', 'assets.id') 
            ->join('merk', 'transaction_history.merk', '=', 'merk.id') 
            ->select(
                'transaction_history.*', 
                'customer.name as customer_name', 
                'customer.nrp as customer_nrp', 
                'merk.name as merk_name', 
                'assets.code as asset_code' 
            )
            ->where('transaction_history.id', $id)
            ->first(); 

     
        $data = [
            'history' => $history,
        ];

        return view('prints.handover', $data);
    }

    // Method to handle Return print request
    public function return($id)
    {
        $history = DB::table('transaction_history')
            ->join('customer', 'transaction_history.name_holder', '=', 'customer.id') 
            ->join('assets', 'transaction_history.asset_code', '=', 'assets.id') 
            ->join('merk', 'transaction_history.merk', '=', 'merk.id') 
            ->select(
                'transaction_history.*', 
                'customer.name as customer_name', 
                'customer.nrp as customer_nrp', 
                'merk.name as merk_name', 
                'assets.code as asset_code' 
            )
            ->where('transaction_history.id', $id)
            ->first(); 

     
        $data = [
            'history' => $history,
        ];

        return view('prints.return', $data);
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

    public function exportToExcel(Request $request)
    {
        $ids = explode(',', $request->query('ids'));

        // Eager load merk and customer relationships
        $assets = Assets::with(['merk', 'customer'])
            ->whereIn('id', $ids)
            ->get();

        return Excel::download(new AssetsExport($assets), 'selected_assets.xlsx');
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
