<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Events\DataUpdated; // Pastikan event diimpor

class HomeSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        // Retrieve user ID from session
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Sales is not logged in.');
        }

        // Fetch approved assets related to the logged-in user and join related tables
        $assets = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->join('customer', 'assets.nama', '=', 'customer.id')
            ->join('inventory', 'assets.asset_tagging', '=', 'inventory.id')
            ->select(
                'assets.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'inventory.tagging as tagging'
            )
            ->where('assets.nama', $userId) // Filter by user ID
            ->where('assets.approval_status', 'Approved') // Only get approved assets
            ->get();

        // Fetch pending assets related to the logged-in user and join related tables
        $pendingAssets = DB::table('assets')
            ->join('merk', 'assets.merk', '=', 'merk.id')
            ->join('customer', 'assets.nama', '=', 'customer.id')
            ->join('inventory', 'assets.asset_tagging', '=', 'inventory.id')
            ->select(
                'assets.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'inventory.tagging as tagging'
            )
            ->where('assets.nama', $userId) // Filter by user ID
            ->where('assets.approval_status', 'Pending') // Only get pending assets
            ->get();

        // Memicu event setelah data diambil
        event(new DataUpdated(['assets' => $assets, 'pendingAssets' => $pendingAssets]));

        // Return view with the fetched data
        return response(view('shared.homeSales', compact('assets', 'pendingAssets')));
    }
}
