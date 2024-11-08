<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    protected $table = 'merk'; // Sesuaikan dengan nama tabel yang ada di database

    protected $fillable = [
        'name',
    ];

    // Relasi dengan Inventory
    public function inventorys()
    {
        return $this->hasMany(Assets::class);
    }

    // Relasi deHistory
    public function inventoryHistories()
    {
        return $this->hasMany(AssetsHistory::class, 'merk'); // Pastikan 'merk_id' sesuai dengan nama kolom di tabel asset_histories
    }

    public $timestamps = false; // Jika tabel tidak menggunakan timestamps
}
