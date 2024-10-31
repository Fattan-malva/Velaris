<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceHistory;
use Illuminate\Http\Request;

class MaintenanceHistoryController extends Controller
{
    public function index()
    {
        $history = MaintenanceHistory::all();
        return view('assets.maintenancehistory', compact('history'));
    }

}
