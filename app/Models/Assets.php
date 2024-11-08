<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    protected $table = 'assets';
    protected $fillable = ['code', 'category', 'merk', 'serial_number', 'entry_date', 'spesification', 'condition', 'last_maintenance', 'scheduling_maintenance', 'note_maintenance', 'next_maintenance', 'handover_date', 'status', 'name_holder', 'location','starting_price','asset_age'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'name_holder');
    }
    public function merk()
    {
        return $this->belongsTo(Merk::class);
    }

    public $timestamps = false;
}
?>