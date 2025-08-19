<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::now()->toDateString();

        Booking::insert([
            [
                'user_id' => 5,
                'venue_id' => 1,
                'contact_number' => '082260761244',
                'booking_date' => $today,
                'time' => '10:00',
                'status' => 'confirmed',
            ],
            [
                'venue_id' => 1,
                'user_id' => 5,
                'date' => $today,
                'time' => '13:00',
                'status' => 'confirmed',
            ],
        ]);
    }
}

