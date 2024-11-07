<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionsHistory extends Model
{
    use HasFactory;

    protected $table = 'transaction_history'; // Specify the table name

    protected $fillable = [
        'assets_code',
        'category_asset',
        'merk',
        'spesification',
        'serial_number',
        'name_holder',
        'position',
        '0365',
        'location',
        'status',
        'asset_condition',
        'type_transactions',
        'documentation',
        'reason',
        'note',



    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'name_holder'); // 'name_holder' is the foreign key
    }

    // Define the relationship with the Merk model
    public function merk()
    {
        return $this->belongsTo(Merk::class);
    }
 
    public $timestamps = false;
}
