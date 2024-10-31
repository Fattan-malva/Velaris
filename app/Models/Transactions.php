<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    // Table name
    protected $table = 'transactions';

    // Fillable fields
    protected $fillable = [
        'asset_tagging',
        'jenis_aset',
        'merk',
        'type',
        'serial_number',
        'nama',
        'mapping',
        'o365',
        'lokasi',
        'status',
        'approval_status',
        'aksi',
        'kondisi',
        'documentation',
        'previous_customer_name',
        'latitude',
        'longitude',
        'keterangan',
        'note'
    ];

    // Relationships

    public function merk()
    {
        return $this->belongsTo(Merk::class, 'merk_id'); // Adjust 'merk_id' to match your actual foreign key name
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'nama'); // Adjust if needed
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'asset_tagging');
    }


    // Optional: If you have timestamps
    public $timestamps = true;
}
