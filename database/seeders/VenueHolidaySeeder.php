<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VenueHoliday;

class VenueHolidaySeeder extends Seeder
{
    public function run(): void
    {
        VenueHoliday::create([
            'venue_id' => 1,
            'date' => '2025-08-17',
            'reason' => 'Hari Kemerdekaan',
        ]);
    }
}
