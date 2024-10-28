<?php
namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;

class InventoryHistoryController extends Controller
{
    // Display all inventory history
    public function index()
    {
        // Eager load the merk relationship
        $inventory_histories = InventoryHistory::with('merkDetail')->get(); // Use 'merkDetail' to match the method name
        return view('assets.history', compact('inventory_histories'));
    }

    public function historyAssetModal(Request $request)
    {
        $tagging = $request->input('tagging'); // Get tagging from request
        \Log::info('Fetching history for tagging: ' . $tagging); // Log the tagging value
    
        $history = DB::table('asset_history')
            ->leftJoin('inventory', 'asset_history.asset_tagging_old', '=', 'inventory.id')
            ->leftJoin('merk', 'asset_history.merk_old', '=', 'merk.id')
            ->leftJoin('customer as old_customer', 'asset_history.nama_old', '=', 'old_customer.id')
            ->leftJoin('customer as new_customer', 'asset_history.nama_new', '=', 'new_customer.id')
            ->select(
                'asset_history.asset_id',
                'inventory.tagging as asset_tagging',
                'merk.name as merk', // Ensure you are selecting the correct field for merk
                'asset_history.jenis_aset_old',
                'old_customer.name as nama_old',
                'new_customer.name as nama_new',
                'asset_history.changed_at',
                'asset_history.action',
                'asset_history.keterangan',
                'asset_history.note',
                'asset_history.documentation_old',
                'asset_history.documentation_new'
            )
            ->where('inventory.tagging', $tagging)
            ->whereIn('asset_history.action', ['CREATE', 'UPDATE', 'DELETE'])
            ->orderBy('asset_history.changed_at', 'DESC')
            ->get();
    
        \Log::info('History data: ', $history->toArray()); // Log the history data
    
        return response()->json($history);
    }
}
