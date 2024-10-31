<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceHistory extends Model
{
    use HasFactory;

    protected $table = 'maintenance_history'; // Specify the table name

    protected $fillable = [
        'assets_id',
        'code',
        'last_maintenance',
        'condition',
        'note_maintenance',
    ];
    public function merkDetail()
    {
        return $this->belongsTo(Merk::class, 'merk'); // Adjust 'merk' to the column in the inventory_history table
    }
    public $timestamps = false;
}
