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
        // Data for user dashboard
        $totalAssets = DB::table('assets')->count();
        $distinctLocations = DB::table('transactions')->distinct()->count('lokasi');
        $distinctAssetTypes = DB::table('assets')->distinct()->count('category');

        // Data for Pie Chart: Jenis Aset from the 'assets' table
        $assetData = DB::table('assets')
            ->select('category as jenis_aset', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        // Data for Pie Chart: Lokasi Mapping from the 'transactions' table
        $locationData = DB::table('transactions')
            ->select('lokasi', DB::raw('count(*) as total'))
            ->groupBy('lokasi')
            ->get();

        // Calculate the count of assets needing maintenance
        $countMaintenanceNeeded = DB::table('assets')
            ->whereNotNull('last_maintenance') // Ensure there is a last maintenance date
            ->whereRaw('TIMESTAMPDIFF(MONTH, last_maintenance, NOW()) >= scheduling_maintenance') // Check if maintenance is due
            ->count();

        return view('shared.dashboardUser', [
            'totalAssets' => $totalAssets,
            'distinctLocations' => $distinctLocations,
            'distinctAssetTypes' => $distinctAssetTypes,
            'assetData' => $assetData,
            'locationData' => $locationData,
            'countMaintenanceNeeded' => $countMaintenanceNeeded,
        ]);
    }



    public function showSummary()
    {
        // Data for Pie Chart: Jenis Aset from 'assets' table
        $assetData = DB::table('assets')
            ->select('category as jenis_aset', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        // Data for Pie Chart: Lokasi Mapping from 'transactions' table
        $locationData = DB::table('transactions')
            ->select('lokasi', DB::raw('count(*) as total'))
            ->groupBy('lokasi')
            ->get();

        // Inventory Summary: Include transactions where approval_status is not 'Approved' 
        // or the status is 'Operations' and not approved
        $assetsSummary = DB::table('transactions as a')
            ->leftJoin('merk as m', 'a.merk', '=', 'm.id')
            ->select(
                'a.jenis_aset as asset_name',
                'm.name as merk_name',
                'a.lokasi as location',
                DB::raw('SUM(CASE WHEN a.status = "Inventory" THEN 1 ELSE 0 END) as assets_count')
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

        // Calculate the count of assets needing maintenance in this summary
        $assetsNeedingMaintenance = DB::table('assets')
        ->select('code AS code', 'last_maintenance', 'scheduling_maintenance', 'entry_date') // Select necessary fields
        ->get()
        ->filter(function ($asset) {
            // Same filtering logic as before
            $tanggalMaintenance = $asset->last_maintenance ?? $asset->entry_date;

            // Convert maintenance schedule to months
            switch ($asset->scheduling_maintenance) {
                case '3 Weeks':
                    $bulanJadwal = 0.75; // 3 weeks in months
                    break;
                case '1 Month':
                    $bulanJadwal = 1;
                    break;
                case '1 Year':
                    $bulanJadwal = 12;
                    break;
                case '5 Years':
                    $bulanJadwal = 60;
                    break;
                default:
                    $bulanJadwal = 0; // No schedule
            }

            // Calculate the elapsed time since the last maintenance
            $bulanSejakAcuan = now()->diffInMonths($tanggalMaintenance);

            // Check if maintenance is needed
            return $bulanSejakAcuan >= $bulanJadwal;
        });

    // Count and extract asset codes
    $countMaintenanceNeeded = $assetsNeedingMaintenance->count();
    $assetCodes = $assetsNeedingMaintenance->pluck('code')->toArray();

        // Additional query to get specific assets data based on your provided query
        $assetsData = DB::table('assets')
            ->select(
                'code AS asset_tagging',
                'category AS asset',
                DB::raw('(SELECT name FROM merk WHERE id = assets.merk) AS merk_name'),
                'condition'
            )
            ->where('status', 'Inventory')
            ->get(); // Ensure this returns results

        // Operation Summary Data: Include only approved transactions
        $operationSummaryData = DB::table('transactions as a')
            ->join('assets as i', 'a.asset_tagging', '=', 'i.id')
            ->join('merk as m', 'a.merk', '=', 'm.id')
            ->select(
                'a.lokasi',
                'a.jenis_aset',
                'm.name AS merk',
                DB::raw('GROUP_CONCAT(i.code ORDER BY i.code ASC SEPARATOR ", ") AS asset_tagging'), // Pakai separator koma
                DB::raw('COUNT(a.id) AS total_transactions')
            )
            ->where('a.approval_status', 'Approved')
            ->groupBy('a.lokasi', 'a.jenis_aset', 'm.name')
            ->orderBy('a.lokasi')
            ->orderBy('a.jenis_aset')
            ->orderBy('m.name')
            ->get();

        // Additional query to display asset quantities by location and type
        $data = DB::table('transactions')
            ->select('lokasi', 'jenis_aset', DB::raw('COUNT(*) as jumlah_aset'))
            ->groupBy('lokasi', 'jenis_aset')
            ->orderBy('lokasi')
            ->orderBy('jenis_aset')
            ->get(); // Ensure this returns results

        return view('shared.dashboard', [
            'totalAssets' => DB::table('assets')->count(),
            'distinctLocations' => DB::table('transactions')->distinct()->count('lokasi'),
            'distinctAssetTypes' => DB::table('assets')->distinct()->count('category'),
            'assetData' => $assetData,
            'locationData' => $locationData,
            'summary' => $assetsSummary,
            'assetsData' => $assetsData,
            'operationSummaryData' => $operationSummaryData,
            'assetQuantitiesByLocation' => $data, // Pass the new data to the view
            'countMaintenanceNeeded' => $countMaintenanceNeeded,
            'assetCodes' => $assetCodes, // Pass the maintenance count here too
        ]);
    }






}
