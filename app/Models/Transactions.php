<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    // Table name
    protected $table = 'transactions';

    // Fillable fields
    protected $fillable = [
        'type_transactions',
        'asset_code',
        'category_asset',
        'merk',
        'spesification',
        'serial_number',
        'name_holder',
        'position',
        'location',
        'latitude',
        'longitude',
        'status',
        'approval_status',
        'condition',
        'documentation',
        'previous_customer_name',
        'reason',
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
        return $this->belongsTo(Assets::class, 'code');
    }


    // Optional: If you have timestamps
    public $timestamps = true;
}
