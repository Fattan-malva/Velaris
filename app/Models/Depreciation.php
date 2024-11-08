<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depreciation extends Model
{
    protected $table = 'depreciation'; // Sesuaikan dengan nama tabel yang ada di database

    protected $fillable = [
        'asset_code','date','depreciation_price'
    ];
    public $timestamps = false; // Jika tabel tidak menggunakan timestamps
}
