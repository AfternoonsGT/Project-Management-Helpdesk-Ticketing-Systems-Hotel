<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'hotel_name',
                'value' => 'Hotel Pangeran',
                'description' => 'Nama resmi hotel'
            ],
            [
                'key' => 'fonnte_token',
                'value' => 'kRxPz3oZEvvCUaK1zEA1', 
                'description' => 'Token API WhatsApp Fonnte'
            ],
            [
                'key' => 'contact_email',
                'value' => 'admin@hotelpangeran.com',
                'description' => 'Email tim IT/Engineering'
            ],
            [
                'key' => 'is_maintenance',
                'value' => '0',
                'description' => '1 = Maintenance, 0 = Normal'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}