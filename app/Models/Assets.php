<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    protected $table = 'assets';
    protected $fillable = ['code','category', 'merk', 'serial_number','entry_date', 'spesification', 'condition','last_maintenance','scheduling_maintenance', 'note_maintenance'];

    public function merk()
    {
        return $this->belongsTo(Merk::class);
    }
    public $timestamps = false;
}
?>