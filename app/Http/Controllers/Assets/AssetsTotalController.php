<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use App\Models\Merk;
use Illuminate\Http\Request;

class AssetsTotalController extends Controller
{
    // Existing methods...

    public function summary()
    {
        // Fetch summary of assets with total quantity
        $assetsSummary = DB::table('assets as i')
            ->join('merk as m', 'i.merk', '=', 'm.id')
            ->select('i.category', 'm.name as merk', DB::raw('COUNT(*) as total_quantity'))
            ->groupBy('i.category', 'm.name')
            ->orderBy('i.category')
            ->orderBy('m.name')
            ->get();
        return view('assets.total', compact('assetsSummary'));
    }
}
