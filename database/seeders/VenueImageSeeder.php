<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VenueImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = now();

        DB::table('venue_images')->insert([
            [
                'venue_id' => 1,
                'image_url' => 'venues/venue1.jpg',
                'is_primary' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'venue_id' => 2,
                'image_url' => 'venues/venue2.jpg',
                'is_primary' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'venue_id' => 3,
                'image_url' => 'venues/venue1_2.jpg',
                'is_primary' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
