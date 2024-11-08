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

        // Use a join to get the merk name, customer name, and asset history data
        $assets = Assets::join('merk', 'assets.merk', '=', 'merk.id') // Join merk table
            ->leftJoin('customer', 'assets.name_holder', '=', 'customer.id') // Join customer table
            ->select(
                'assets.id',
                'assets.code',
                'assets.category',
                'merk.name as merk_name', // Select merk name
                'assets.spesification',
                'assets.condition',
                'assets.status',
                'assets.entry_date',
                'customer.name as holder_name', // Select customer name
                'assets.handover_date',
                'assets.location',
                'assets.scheduling_maintenance',
                'assets.last_maintenance',
                'assets.next_maintenance',
                'assets.note_maintenance',
            )
            ->whereIn('assets.id', $ids)
            ->get();

        return Excel::download(new AssetsExport($assets), 'List Assets.xlsx');
    }

    public function showAssetDetail($id)
    {
        $asset = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id') // Gabungkan dengan tabel merk untuk mendapatkan nama merk
            ->leftJoin('customer', 'assets.name_holder', '=', 'customer.id') // Gabungkan dengan tabel customer untuk mendapatkan nama pemegang aset
            ->leftJoin('depreciation', 'assets.code', '=', 'depreciation.asset_code') // Gabungkan dengan tabel depreciation untuk mendapatkan nilai depresiasi
            ->select(
                'assets.id', // Select specific columns from the assets table
                'assets.code',
                'assets.name_holder',
                'assets.merk',
                'assets.status',
                'assets.serial_number',
                'assets.category',
                'assets.spesification',
                'assets.next_maintenance',
                'assets.condition',
                'assets.last_maintenance',
                'assets.entry_date',
                'assets.scheduling_maintenance',
                'assets.location', // add all columns you want to use
                'merk.name as merk_name',
                'customer.name as customer_name',
                DB::raw('
                    COALESCE(
                        (SELECT depreciation_price 
                         FROM depreciation 
                         WHERE depreciation.asset_code = assets.code 
                         AND depreciation.date = CURDATE() 
                         LIMIT 1), 
                         
                        (SELECT depreciation_price 
                         FROM depreciation 
                         WHERE depreciation.asset_code = assets.code 
                         AND depreciation.date < CURDATE() 
                         ORDER BY depreciation.date DESC 
                         LIMIT 1),
                         
                        (SELECT depreciation_price 
                         FROM depreciation 
                         WHERE depreciation.asset_code = assets.code 
                         AND depreciation.date > CURDATE() 
                         ORDER BY depreciation.date ASC 
                         LIMIT 1)
                    ) as depreciation_price'
                ),
                DB::raw('MAX(depreciation.date) as depreciation_date') // Ambil tanggal depresiasi terbaru
            )
            ->where('assets.id', '=', $id) // Filter by asset ID
            ->groupBy(
                'assets.id',
                'assets.code',
                'assets.name_holder',
                'assets.merk',
                'assets.status',
                'assets.serial_number',
                'assets.next_maintenance',
                'assets.spesification',
                'assets.condition',
                'assets.category',
                'assets.scheduling_maintenance',
                'assets.last_maintenance',
                'assets.entry_date',
                'assets.location', // Include all assets columns
                'merk.name',
                'customer.name' // Include merk and customer columns in GROUP BY
            )
            ->first(); // Use first() instead of get() to return a single asset detail

        // Return the view with asset details
        return view('auth.detailQR', compact('asset'));
    }




}
