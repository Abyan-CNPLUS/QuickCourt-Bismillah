<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Venues;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MemesanController extends Controller
{
    public function create($venueId)
    {
        $venue = Venues::with('city')->findOrFail($venueId);

        // 7 hari ke depan
        $dates = collect(range(0,6))->map(fn($i) => Carbon::now()->addDays($i));

        // Jam 08.00 - 22.00
        $times = [];
        for ($i = 8; $i <= 23; $i++) {
            $times[] = sprintf('%02d:00', $i);
        }

        // Ambil slot yang sudah dibooking
        $bookings = Booking::where('venue_id', $venueId)
            ->whereBetween('booking_date', [Carbon::now()->toDateString(), Carbon::now()->addDays(6)->toDateString()])
            ->get();

        $bookedTimes = $bookings->pluck('start_time')->toArray();

        return view('booking', compact('venue','dates','times','bookedTimes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'total_price' => 'required|numeric',
            'contact_number' => 'required|string|max:20',
        ]);

        // Simpan booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'venue_id' => $request->venue_id,
            'contact_number' => $request->contact_number,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        // Buat payment pending
        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $request->total_price,
            'payment_method' => 'qris',
            'status' => 'pending',
            'payment_date' => now(), // tambahkan ini
        ]);

        // Redirect ke halaman pembayaran
        return redirect()->route('bookings.payment', $booking->id);
    }
}
