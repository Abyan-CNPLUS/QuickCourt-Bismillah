<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $venues = [
            [
                'name' => 'Haji Putra Mini Socc Jl. Sport Center No.',
                'address' => 'Haji Putra Mini Socc Jl. Sport Center No.',
                'capacity' => 100,
                'price' => 1000000,
                'status' => 'available',
                'category_id' => 1,
                'city_id' => 1,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'GOR Futsal Bandung Barat',
                'address' => 'Jl. Raya Lembang No.5, Bandung Barat',
                'capacity' => 80,
                'price' => 850000,
                'status' => 'available',
                'category_id' => 1,
                'city_id' => 1,
                'open_time' => '07:00:00',
                'close_time' => '23:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Arena Mini Soccer Depok',
                'address' => 'Jl. Margonda Raya No.88, Depok',
                'capacity' => 120,
                'price' => 950000,
                'status' => 'available',
                'category_id' => 2,
                'city_id' => 2,
                'open_time' => '06:00:00',
                'close_time' => '21:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Lapangan Mega Soccer Jakarta Timur',
                'address' => 'Jl. Pemuda No.17, Jakarta Timur',
                'capacity' => 150,
                'price' => 1250000,
                'status' => 'available',
                'category_id' => 2,
                'city_id' => 3,
                'open_time' => '08:00:00',
                'close_time' => '00:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Soccer Dome Bekasi',
                'address' => 'Jl. Kalimalang No.33, Bekasi',
                'capacity' => 90,
                'price' => 900000,
                'status' => 'available',
                'category_id' => 1,
                'city_id' => 4,
                'open_time' => '09:00:00',
                'close_time' => '22:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('venues')->insert($venues);
    }
}
