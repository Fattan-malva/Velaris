<?php
namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\AssetsHistory;
use Illuminate\Http\Request;

class AssetsHistoryController extends Controller
{
    // Display all assets history
    public function index()
    {
        // Eager load the merk relationship
        $asset_histories = AssetsHistory::with('merkDetail')->get(); // Use 'merkDetail' to match the method name
        return view('assets.history', compact('asset_histories'));
    }

    public function historyByAssetCode($assetCode)
    {
        // Retrieve transaction history for the specified asset code
        $history = DB::table('transaction_history')
            ->leftJoin('assets', 'transaction_history.asset_code', '=', 'assets.id')
            ->leftJoin('merk', 'transaction_history.merk', '=', 'merk.id')
            ->leftJoin('customer', 'transaction_history.name_holder', '=', 'customer.id')
            ->select('transaction_history.*', 'merk.name as merk_name', 'customer.name as name_holder', 'assets.code as asset_code')
            ->where('assets.code', $assetCode)
            ->orderBy('transaction_history.created_at', 'DESC')
            ->get();

        return response()->json($history);
    }

    public function depreciationByAssetCode($assetCode)
    {
        // Ambil data depresiasi berdasarkan asset_code
        $depreciation = DB::table('depreciation')
            ->where('asset_code', $assetCode) // Pastikan untuk mencocokkan asset_code dari depreciation
            ->orderBy('date', 'ASC') // Urutkan berdasarkan tanggal pembuatan
            ->get(['date', 'depreciation_price']); // Ambil hanya kolom yang dibutuhkan
    
        // Kembalikan data dalam format JSON
        return response()->json($depreciation);
    }
    



}
