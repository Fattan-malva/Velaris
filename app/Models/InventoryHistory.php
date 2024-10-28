<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    use HasFactory;

    protected $table = 'inventory_history'; // Specify the table name

    protected $fillable = [
        'inventory_id',
        'action',
        'tagging',
        'asets',
        'merk',
        'seri',
        'tanggalmasuk',
        'type',
        'kondisi',
        'status',
        'lokasi',
        'tanggal_diterima',
        'documentation',
    ];
    public function merkDetail()
    {
        return $this->belongsTo(Merk::class, 'merk'); // Adjust 'merk' to the column in the inventory_history table
    }
    public $timestamps = false;
}
