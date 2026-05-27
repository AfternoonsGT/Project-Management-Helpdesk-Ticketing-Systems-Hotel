<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number', 
        'reporter_id', 
        'technician_id', 
        'category_id',
        'service_type', 
        'floor',
        'location', 
        'title', 
        'description', 
        'image_before', 
        'image_after',
        'priority', 
        'status',
        'completed_at' // <--- WAJIB TAMBAHKAN INI!
    ];

    /**
     * Agar Laravel memperlakukan kolom ini sebagai objek Carbon (Tanggal)
     * sehingga bisa langsung pakai fungsi ->format() di Blade/PDF
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relasi ke Staff (Pelapor)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Relasi ke Teknisi
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    // Jembatan ke tabel Categories
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}