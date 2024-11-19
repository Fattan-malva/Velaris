<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'subject',
        'status',
        'is_read',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'ticket_id');
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id'); // Replace 'customer_id' with the correct foreign key column
    }
}
