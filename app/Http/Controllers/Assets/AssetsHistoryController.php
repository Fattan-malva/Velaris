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

    public function historyAssetModal(Request $request)
    {
        $tagging = $request->input('code'); // Get tagging from request
        \Log::info('Fetching history for tagging: ' . $tagging); // Log the tagging value

        $history = DB::table('transactions_history')
            ->leftJoin('assets', 'transactions_history.asset_tagging_old', '=', 'assets.id')
            ->leftJoin('merk', 'transactions_history.merk_old', '=', 'merk.id')
            ->leftJoin('customer as old_customer', 'transactions_history.nama_old', '=', 'old_customer.id')
            ->leftJoin('customer as new_customer', 'transactions_history.nama_new', '=', 'new_customer.id')
            ->select(
                'transactions_history.asset_id',
                'assets.code as asset_tagging',
                'merk.name as merk', // Ensure you are selecting the correct field for merk
                'transactions_history.jenis_aset_old',
                'old_customer.name as nama_old',
                'new_customer.name as nama_new',
                'transactions_history.changed_at',
                'transactions_history.action',
                'transactions_history.keterangan',
                'transactions_history.note',
                'transactions_history.documentation_old',
                'transactions_history.documentation_new'
            )
            ->where('assets.code', $tagging)
            ->whereIn('transactions_history.action', ['CREATE', 'UPDATE', 'DELETE'])
            ->orderBy('transactions_history.changed_at', 'DESC')
            ->get();

        \Log::info('History data: ', $history->toArray()); // Log the history data

        return response()->json($history);
    }
}
