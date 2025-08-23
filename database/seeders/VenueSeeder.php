<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('venues')->insert([
            [
                'user_id'     => 1, // asumsi user_id = 1
                'name'       => 'GOR Bima Sakti',
                'address'    => 'Jl. Merdeka No.10, Jakarta',
                'capacity'   => 200,
                'price'      => '150000',
                'status'     => 'available',
                'category_id'=> 1, // asumsi ada category futsal/basket
                'city_id'    => 1, // asumsi city_id = 1
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'open_time'  => '08:00:00',
                'close_time' => '22:00:00',
                'deskripsi'  => 'Venue olahraga indoor dengan fasilitas lengkap.',
                'rules'      => 'Dilarang merokok di dalam arena.',
            ],
            [
                'user_id'     => 1,
                'name'       => 'Lapangan Outdoor Cempaka',
                'address'    => 'Jl. Cempaka Raya No.5, Bandung',
                'capacity'   => 100,
                'price'      => '100000',
                'status'     => 'available',
                'category_id'=> 2, 
                'city_id'    => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'open_time'  => '07:00:00',
                'close_time' => '21:00:00',
                'deskripsi'  => 'Lapangan outdoor cocok untuk badminton & futsal.',
                'rules'      => 'Bawa peralatan olahraga sendiri.',
            ],
            [
                'user_id'     => 2,
                'name'       => 'Arena Basket Galaxy',
                'address'    => 'Jl. Galaxy Barat No.22, Surabaya',
                'capacity'   => 150,
                'price'      => '200000',
                'status'     => 'available',
                'category_id'=> 3, // misal kategori basket
                'city_id'    => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'open_time'  => '09:00:00',
                'close_time' => '23:00:00',
                'deskripsi'  => 'Arena basket standar nasional.',
                'rules'      => 'Gunakan sepatu basket khusus.',
            ],
        ]);
    }
}
