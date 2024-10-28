<?php
namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class InventoryLocationController extends Controller
{
    public function mapping()
    {
        // Run the SQL query
        $data = DB::table('assets')
            ->select('lokasi', 'jenis_aset', DB::raw('COUNT(*) as jumlah_aset'))
            ->groupBy('lokasi', 'jenis_aset')
            ->orderBy('lokasi')
            ->orderBy('jenis_aset')
            ->get();

        // Pass the data to the view
        return view('assets.mapping', ['data' => $data]);
    }
}
