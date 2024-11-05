<?php
namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class AssetsLocationController extends Controller
{
    public function mapping()
    {
        // Run the SQL query
        $data = DB::table('transactions')
            ->select('location', 'category_asset', DB::raw('COUNT(*) as jumlah_aset'))
            ->groupBy('location', 'category_asset')
            ->orderBy('location')
            ->orderBy('category_asset')
            ->get();

        // Pass the data to the view
        return view('assets.location', ['data' => $data]);
    }
}
