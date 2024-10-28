<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable; // Trait untuk autentikasi

class Customer extends Model implements AuthenticatableContract
{
    use Authenticatable; // Trait untuk autentikasi

    // Table name
    protected $table = 'customer'; 

    // Fillable fields
    protected $fillable = [
        'username',
        'password',
        'role',
        'nrp', 
        'name', 
        'mapping',
        'login_method',
    ];

    // Optionally define the primary key if it's not 'id'
    // protected $primaryKey = 'your_primary_key_name';

    // Relationships
    public function assets()
    {
        return $this->hasMany(Assets::class, 'nama'); // Sesuaikan jika perlu
    }

    // Optional: If you don't use timestamps
    public $timestamps = false;
}
