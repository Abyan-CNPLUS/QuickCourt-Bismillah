<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\VenueHoliday;
use App\Models\Booking;
use App\Models\Venues;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class VenueAvailableTimeController extends Controller
{
    public function index(Request $request, $venueId)
    {
        $date = $request->query('date');
        if (!$date) {
            return response()->json(['message' => 'Date is required'], 400);
        }

        $venue = Venues::findOrFail($venueId);


        $isHoliday = VenueHoliday::where('venue_id', $venueId)
            ->where('date', $date)
            ->exists();

        if ($isHoliday) {
            return response()->json([
                'date' => $date,
                'available_times' => [],
                'message' => 'Closed for holiday',
            ]);
        }


        $open = Carbon::createFromTimeString($venue->open_time);
        $close = Carbon::createFromTimeString($venue->close_time);


        $slots = [];
        for ($time = $open->copy(); $time < $close; $time->addHour()) {
            $slotTime = $time->format('H:i');

            $start = $time->format('H:i:s'); // waktu mulai slot
            $end = $time->copy()->addHour()->format('H:i:s'); // waktu selesai slot

            $isBooked = Booking::where('venue_id', $venueId)
                ->whereDate('booking_date', $date)
                ->where('start_time', '<', $end)
                ->where('end_time', '>', $start)
                ->exists();

            $slots[] = [
                'start_time' => $slotTime,
                'end_time' => $time->copy()->addHour()->format('H:i'),
                'is_booked' => $isBooked,
            ];
        }

        return response()->json([
            'debug' => [
                'open_time' => $venue->open_time,
                'close_time' => $venue->close_time,
                'diff_hours' => $open->diffInHours($close),
                'is_holiday' => $isHoliday,
            ],
            'slots' => $slots,
        ]);
    }
}

