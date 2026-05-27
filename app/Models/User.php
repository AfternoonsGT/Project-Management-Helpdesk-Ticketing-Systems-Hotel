<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: Satu Staff bisa melaporkan BANYAK Tiket (One-to-Many)
     */
    public function reportedTickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'reporter_id');
    }

    /**
     * Relasi: Satu Teknisi bisa ditugaskan di BANYAK Tiket (One-to-Many)
     */
    public function assignedTickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'technician_id');
    }
}
