<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $facilities = [
            'Parkir Luas',
            'Toilet',
            'Ruang Ganti',
            'Mushola',
            'Warung Makan',
            'WiFi Gratis',
            'Penerangan Malam',
            'Tribun Penonton',
            'Loker Penyimpanan',
            'Air Minum Gratis',
        ];

        foreach ($facilities as $name) {
            Facility::create(['name' => $name]);
        }
    }
}
