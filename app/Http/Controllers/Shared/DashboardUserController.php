<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Events\DataUpdated; // Pastikan event diimpor

class DashboardUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        // Retrieve user ID from session
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'User is not logged in.');
        }

        // Fetch approved transactions related to the logged-in user and join related tables
        $transactions = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.name_holder', '=', 'customer.id')
            ->join('assets', 'transactions.asset_code', '=', 'assets.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'assets.code as tagging'
            )
            ->where('transactions.name_holder', $userId) // Filter b\y user ID
            ->where('transactions.approval_status', 'Approved') // Only get approved transactions
            ->get();

        // Fetch pending transactions related to the logged-in user and join related tables
        $pendingAssets = DB::table('transactions')
            ->join('merk', 'transactions.merk', '=', 'merk.id')
            ->join('customer', 'transactions.name_holder', '=', 'customer.id')
            ->join('assets', 'transactions.asset_code', '=', 'assets.id')
            ->select(
                'transactions.*',
                'merk.name as merk_name',
                'customer.name as customer_name',
                'assets.code as tagging'
            )
            ->where('transactions.name_holder', $userId) // Filter by user ID
            ->where('transactions.approval_status', 'Pending') // Only get pending transactions
            ->get();

      
        // Return view with the fetched data
        return response(view('shared.homeUser', compact('transactions', 'pendingAssets')));
    }
}
