<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Mengizinkan kolom-kolom ini diisi data secara massal
    protected $fillable = ['key', 'value', 'description'];
}