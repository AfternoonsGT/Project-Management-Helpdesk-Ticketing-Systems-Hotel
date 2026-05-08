<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // 👇 Panggil DB di sini

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'AC & Pendingin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Listrik & Lampu', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Air & Pipa (Plumbing)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Furniture & Interior', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Elektronik & TV', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bangunan & Sipil', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
