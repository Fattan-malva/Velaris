<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function summaryReport()
    {
        // Mengambil data detail dari tabel assets untuk status Operation
        $operationSummary = DB::table('assets as a')
            ->join('merk as m', 'a.merk', '=', 'm.id')
            ->select(
                'a.jenis_aset as asset_name',
                'm.name as merk_name',
                DB::raw('COUNT(*) as operation_count')
            )
            ->where('a.status', '=', 'Operation')
            ->groupBy('a.jenis_aset', 'm.name')
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->asset_name . '|' . $item->merk_name;
                return [$key => [
                    'operation_count' => $item->operation_count
                ]];
            })
            ->toArray();

        // Mengambil data kondisi dari tabel inventory
        $conditionSummary = DB::table('inventory as i')
            ->join('merk as m', 'i.merk', '=', 'm.id')
            ->select(
                'i.asets as asset_name',
                'm.name as merk_name',
                DB::raw('SUM(CASE WHEN i.kondisi = "Good" THEN 1 ELSE 0 END) as good_count'),
                DB::raw('SUM(CASE WHEN i.kondisi = "Exception" THEN 1 ELSE 0 END) as exception_count'),
                DB::raw('SUM(CASE WHEN i.kondisi = "Bad" THEN 1 ELSE 0 END) as bad_count')
            )
            ->where('i.status', '=', 'Inventory')
            ->groupBy('i.asets', 'm.name')
            ->get()
            ->mapWithKeys(function ($item) {
                $key = $item->asset_name . '|' . $item->merk_name;
                return [$key => [
                    'good_count' => $item->good_count,
                    'exception_count' => $item->exception_count,
                    'bad_count' => $item->bad_count
                ]];
            })
            ->toArray();

        // Mengambil data detail dari tabel assets untuk status Inventory
        $inventorySummary = DB::table('assets as a')
            ->leftJoin('merk as m', 'a.merk', '=', 'm.id')
            ->select(
                'a.jenis_aset as asset_name',
                'm.name as merk_name',
                'a.lokasi as location',
                DB::raw('SUM(CASE WHEN a.status = "Inventory" THEN 1 ELSE 0 END) as inventory_count')
            )
            ->groupBy('a.jenis_aset', 'm.name', 'a.lokasi')
            ->get()
            ->map(function ($item) use ($conditionSummary, $operationSummary) {
                $assetName = $item->asset_name;
                $merkName = $item->merk_name;
                $inventoryKey = $assetName . '|' . $merkName;
                $conditionCounts = $conditionSummary[$inventoryKey] ?? [
                    'good_count' => 0,
                    'exception_count' => 0,
                    'bad_count' => 0
                ];
                $operationCount = $operationSummary[$inventoryKey]['operation_count'] ?? 0;

                return [
                    'asset_name' => $assetName,
                    'merk_name' => $merkName,
                    'locations' => [
                        [
                            'location' => $item->location ?: "-",
                            'operation_count' => $operationCount,
                            'inventory_count' => $item->inventory_count ?: "-"
                        ]
                    ],
                    'total_quantity' => $operationCount + $item->inventory_count,
                    'inventory_GSI' => $conditionCounts['good_count'] + $conditionCounts['exception_count'] + $conditionCounts['bad_count'] - $item->inventory_count,
                    'good_count' => $conditionCounts['good_count'],
                    'exception_count' => $conditionCounts['exception_count'],
                    'bad_count' => $conditionCounts['bad_count']
                ];
            });

        // Jika tidak ada data di tabel assets, pastikan data inventory tetap ditampilkan
        $summary = DB::table('inventory as i')
            ->leftJoin('merk as m', 'i.merk', '=', 'm.id')
            ->select(
                'i.asets as asset_name',
                'm.name as merk_name',
                DB::raw('COALESCE(a.lokasi, "-") as location'),  // Menggunakan COALESCE untuk lokasi
                DB::raw('COALESCE(SUM(CASE WHEN a.status = "Operation" THEN 1 ELSE 0 END), 0) as operation_count'),
                DB::raw('COALESCE(SUM(CASE WHEN a.status = "Inventory" THEN 1 ELSE 0 END), 0) as inventory_count')
            )
            ->leftJoin('assets as a', function ($join) {
                $join->on('i.asets', '=', 'a.jenis_aset')
                     ->on('i.merk', '=', 'a.merk');
            })
            ->groupBy('i.asets', 'm.name', 'a.lokasi')
            ->get()
            ->map(function ($item) use ($conditionSummary, $operationSummary) {
                $assetName = $item->asset_name;
                $merkName = $item->merk_name;
                $inventoryKey = $assetName . '|' . $merkName;
                $operationCount = $operationSummary[$inventoryKey]['operation_count'] ?? 0;

                $conditionCounts = $conditionSummary[$inventoryKey] ?? [
                    'good_count' => 0,
                    'exception_count' => 0,
                    'bad_count' => 0
                ];

                return [
                    'asset_name' => $assetName,
                    'merk_name' => $merkName,
                    'locations' => [
                        [
                            'location' => $item->location ?: "-",
                            'operation_count' => $operationCount,
                            'inventory_count' => $item->inventory_count ?: "-"
                        ]
                    ],
                    'total_quantity' => $operationCount + $item->inventory_count,
                    'inventory_GSI' => $conditionCounts['good_count'] + $conditionCounts['exception_count'] + $conditionCounts['bad_count'] - $item->inventory_count,
                    'good_count' => $conditionCounts['good_count'],
                    'exception_count' => $conditionCounts['exception_count'],
                    'bad_count' => $conditionCounts['bad_count']
                ];
            });

        return view('reports.summary', ['summary' => $summary]);
    }
}
