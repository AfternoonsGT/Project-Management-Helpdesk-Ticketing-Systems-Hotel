<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
protected $fillable = [
        'ticket_number', 'reporter_id', 'technician_id', 'category_id',
        'location', 'title', 'description', 'image_before', 'image_after',
        'priority', 'status'
    ];
}
