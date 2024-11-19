<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['task', 'completed', 'ticket_id'];

    // Relationship with Ticket (assuming each todo belongs to a ticket)
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
