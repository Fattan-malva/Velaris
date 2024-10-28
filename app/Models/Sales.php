<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales'; // Sesuaikan dengan nama tabel yang ada di database

    protected $fillable = [
        'nama',
        'departement',
        'lokasi',
        'nama_asset',
        'status',
        'description',
    ];
    protected $dates = ['created_at'];
    public $timestamps = false;
}
