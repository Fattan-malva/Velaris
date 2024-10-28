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
        return $this->hasMany(Inventory::class);
    }

    // Relasi dengan InventoryHistory
    public function inventoryHistories()
    {
        return $this->hasMany(InventoryHistory::class, 'merk'); // Pastikan 'merk_id' sesuai dengan nama kolom di tabel inventory_histories
    }

    public $timestamps = false; // Jika tabel tidak menggunakan timestamps
}
