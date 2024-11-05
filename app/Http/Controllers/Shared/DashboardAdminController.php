<?php
namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return $this->showSummary();
    }
    public function indexUser()
    {

        $totalAssets = DB::table('assets')->count();
        $distinctLocations = DB::table('transactions')
            ->where('approval_status', 'Approved') // Filter by approval status
            ->distinct()
            ->count('location'); // Count distinct locations
        $distinctAssetTypes = DB::table('assets')->distinct()->count('category');
        $assetData = DB::table('assets')
            ->select('category as category_asset', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
        $locationData = DB::table('transactions')
            ->select('location', DB::raw('count(*) as total'))
            ->groupBy('location')
            ->get();

        $countMaintenanceNeeded = DB::table('assets')
            ->whereNotNull('last_maintenance')
            ->whereRaw('TIMESTAMPDIFF(MONTH, last_maintenance, NOW()) >= scheduling_maintenance')
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

        $assetData = DB::table('assets')
            ->select('category as category_asset', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
        $locationData = DB::table('transactions')
            ->select('location', DB::raw('count(*) as total'))
            ->groupBy('location')
            ->get();
        $assetsSummary = DB::table('transactions as a')
            ->leftJoin('merk as m', 'a.merk', '=', 'm.id')
            ->select(
                'a.category_asset as asset_name',
                'm.name as merk_name',
                'a.location as location',
                DB::raw('SUM(CASE WHEN a.status = "Inventory" THEN 1 ELSE 0 END) as assets_count')
            )
            ->where(function ($query) {
                $query->where('a.approval_status', '<>', 'Approved')
                    ->orWhere(function ($query) {
                        $query->where('a.status', 'Operations')
                            ->where('a.approval_status', '<>', 'Approved');
                    });
            })
            ->groupBy('a.category_asset', 'm.name', 'a.location')
            ->get();

        $assetsNeedingMaintenance = DB::table('assets')
            ->select('id', 'code', 'last_maintenance', 'scheduling_maintenance', 'entry_date')
            ->get()
            ->filter(function ($asset) {
                $tanggalMaintenance = $asset->last_maintenance ?? $asset->entry_date;
                [$intervalValue, $intervalUnit] = explode(' ', $asset->scheduling_maintenance);
                $nextMaintenanceDate = Carbon::parse($tanggalMaintenance);
                switch (strtolower($intervalUnit)) {
                    case 'weeks':
                        $nextMaintenanceDate->addWeeks($intervalValue);
                        break;
                    case 'months':
                        $nextMaintenanceDate->addMonths($intervalValue);
                        break;
                    case 'years':
                        $nextMaintenanceDate->addYears($intervalValue);
                        break;
                    default:
                        return false;
                }
                return $nextMaintenanceDate->isPast();
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

        $operationSummaryData = DB::table('transactions as a')
            ->join('assets as i', 'a.asset_code', '=', 'i.id')
            ->join('merk as m', 'a.merk', '=', 'm.id')
            ->select(
                'a.location',
                'a.category_asset',
                'm.name AS merk',
                DB::raw('GROUP_CONCAT(i.code ORDER BY i.code ASC SEPARATOR ", ") AS asset_tagging'), // Pakai separator koma
                DB::raw('COUNT(a.id) AS total_transactions')
            )
            ->where('a.approval_status', 'Approved')
            ->groupBy('a.location', 'a.category_asset', 'm.name')
            ->orderBy('a.location')
            ->orderBy('a.category_asset')
            ->orderBy('m.name')
            ->get();

        // Additional query to display asset quantities by location and type
        $data = DB::table('transactions')
            ->select('location', 'category_asset', DB::raw('COUNT(*) as jumlah_aset'))
            ->groupBy('location', 'category_asset')
            ->orderBy('location')
            ->orderBy('category_asset')
            ->get(); // Ensure this returns results
            
        return view('shared.dashboard', [
            'totalAssets' => DB::table('assets')->count(),
            'distinctLocations' => DB::table('transactions')->distinct()->count('location'),
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
