<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsHistory extends Model
{
    use HasFactory;

    protected $table = 'assets_history'; // Specify the table name

    protected $fillable = [
        'asset_id',
        'action',
        'code',
        'category',
        'merk',
        'serial_number',
        'handover_date',
        'spesification',
        'condition',
        'status',
        'location',
        'entry_date',
        'documentation',
    ];
    public function merkDetail()
    {
        return $this->belongsTo(Merk::class, 'merk'); // Adjust 'merk' to the column in the inventory_history table
    }
    public $timestamps = false;
}
