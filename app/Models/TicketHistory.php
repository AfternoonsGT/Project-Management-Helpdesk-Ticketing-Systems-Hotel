<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    protected $fillable = [
        'ticket_id', 'user_id', 'status', 'note'
    ];
    // Relasi ke tabel Users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
