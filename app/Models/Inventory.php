<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $fillable = ['tagging','asets', 'merk', 'seri','tanggalmasuk', 'type', 'kondisi','maintenance'];

    public function merk()
    {
        return $this->belongsTo(Merk::class);
    }
    public $timestamps = false;
}
?>