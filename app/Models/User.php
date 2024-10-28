<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';

    // app/Models/User.php

    protected $fillable = [
        'username',
        'password',
        'role',
        'nrp',
        'mapping',
        'nama',
        'nohp',
        'alamat',
    ];


    public $timestamps = false;
}
