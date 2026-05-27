<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Memanggil model User
use Illuminate\Support\Facades\Hash; // Untuk enkripsi password

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Admin Engineering',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Teknisi
        User::create([
            'name' => 'Budi Teknisi',
            'email' => 'teknisi@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'technician',
            'phone' => '0681270180203', // Contoh nomor telepon
        ]);

        // 3. Akun Staff (Pelapor)
        User::create([
            'name' => 'Siti Housekeeping',
            'email' => 'staff@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'phone' => '0681270180203', // Contoh nomor telepon
        ]);
    }
}
