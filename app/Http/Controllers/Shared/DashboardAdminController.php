<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return $this->showSummary();
    }
    public function indexUser()
    {
        // Data untuk dashboard pengguna
        $totalAssets = DB::table('inventory')->count();
        $distinctLocations = DB::table('assets')->distinct()->count('lokasi');
        $distinctAssetTypes = DB::table('inventory')->distinct()->count('asets');

        // Data untuk Pie Chart: Jenis Aset dari tabel 'inventory'
        $assetData = DB::table('inventory')
            ->select('asets as jenis_aset', DB::raw('count(*) as total'))
            ->groupBy('asets')
            ->get();

        // Data untuk Pie Chart: Lokasi Mapping dari tabel 'assets'
        $locationData = DB::table('assets')
            ->select('lokasi', DB::raw('count(*) as total'))
            ->groupBy('lokasi')
            ->get();

        return view('shared.dashboardUser', [
            'totalAssets' => $totalAssets,
            'distinctLocations' => $distinctLocations,
            'distinctAssetTypes' => $distinctAssetTypes,
            'assetData' => $assetData,
            'locationData' => $locationData
        ]);
    }



    public function showSummary()
    {
        // Data for Pie Chart: Jenis Aset from 'inventory' table
        $assetData = DB::table('inventory')
            ->select('asets as jenis_aset', DB::raw('count(*) as total'))
            ->groupBy('asets')
            ->get();

        // Data for Pie Chart: Lokasi Mapping from 'assets' table
        $locationData = DB::table('assets')
            ->select('lokasi', DB::raw('count(*) as total'))
            ->groupBy('lokasi')
            ->get();

        // Inventory Summary: Include assets where approval_status is not 'Approved' 
        // or the status is 'Operations' and not approved
        $inventorySummary = DB::table('assets as a')
            ->leftJoin('merk as m', 'a.merk', '=', 'm.id')
            ->select(
                'a.jenis_aset as asset_name',
                'm.name as merk_name',
                'a.lokasi as location',
                DB::raw('SUM(CASE WHEN a.status = "Inventory" THEN 1 ELSE 0 END) as inventory_count')
            )
            ->where(function ($query) {
                $query->where('a.approval_status', '<>', 'Approved')
                    ->orWhere(function ($query) {
                        $query->where('a.status', 'Operations')
                            ->where('a.approval_status', '<>', 'Approved');
                    });
            })
            ->groupBy('a.jenis_aset', 'm.name', 'a.lokasi')
            ->get(); // Ensure this returns results

        // Additional query to get specific inventory data based on your provided query
        $inventoryData = DB::table('inventory')
            ->select(
                'tagging AS asset_tagging',
                'asets AS asset',
                DB::raw('(SELECT name FROM merk WHERE id = inventory.merk) AS merk_name'),
                'kondisi'
            )
            ->where('status', 'Inventory')
            ->get(); // Ensure this returns results

        // Operation Summary Data: Include only approved assets
        $operationSummaryData = DB::table('assets as a')
            ->join('inventory as i', 'a.asset_tagging', '=', 'i.id')
            ->join('merk as m', 'a.merk', '=', 'm.id')
            ->select(
                'a.lokasi',
                'a.jenis_aset',
                'm.name AS merk',
                DB::raw('GROUP_CONCAT(i.tagging ORDER BY i.tagging ASC SEPARATOR ", ") AS asset_tagging'), // Pakai separator koma
                DB::raw('COUNT(a.id) AS total_assets')
            )
            ->where('a.approval_status', 'Approved')
            ->groupBy('a.lokasi', 'a.jenis_aset', 'm.name')
            ->orderBy('a.lokasi')
            ->orderBy('a.jenis_aset')
            ->orderBy('m.name')
            ->get();


        // Additional query to display asset quantities by location and type
        $data = DB::table('assets')
            ->select('lokasi', 'jenis_aset', DB::raw('COUNT(*) as jumlah_aset'))
            ->groupBy('lokasi', 'jenis_aset')
            ->orderBy('lokasi')
            ->orderBy('jenis_aset')
            ->get(); // Ensure this returns results

        return view('shared.dashboard', [
            'totalAssets' => DB::table('inventory')->count(),
            'distinctLocations' => DB::table('assets')->distinct()->count('lokasi'),
            'distinctAssetTypes' => DB::table('inventory')->distinct()->count('asets'),
            'assetData' => $assetData,
            'locationData' => $locationData,
            'summary' => $inventorySummary,
            'inventoryData' => $inventoryData,
            'operationSummaryData' => $operationSummaryData,
            'assetQuantitiesByLocation' => $data // Pass the new data to the view
        ]);
    }





}
