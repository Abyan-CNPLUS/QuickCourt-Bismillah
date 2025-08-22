<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Venues;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->with('venue')->latest()->get();
        return view('booking', compact('bookings'));
    }

    public function create()
    {
        $venues = Venues::all();
        return view('booking', compact('venues'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'guest') {
            return redirect()->back()->with('error', 'Guest users are not allowed to make bookings.');
        }

        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'contact_number' => 'required|string',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'total_price' => 'required|numeric',
        ]);

        $venue = Venues::findOrFail($request->venue_id);

        if ($request->start_time < $venue->open_time || $request->end_time > $venue->close_time) {
            return redirect()->back()->withErrors([
                'time' => 'Booking time must be within venue operating hours (' . $venue->open_time . ' - ' . $venue->close_time . ')',
            ])->withInput();
        }

        $isBooked = Booking::where('venue_id', $request->venue_id)
            ->whereDate('booking_date', $request->booking_date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
                });
            })->exists();

        if ($isBooked) {
            return redirect()->back()->withErrors([
                'booking' => 'Venue is already booked in this time',
            ])->withInput();
        }

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

        return redirect()->route('booking.show', $booking->id)->with('success', 'Booking successfully created!');
    }

    public function show($id)
    {
        $booking = Booking::with('venue')->find($id);

        if (!$booking || $booking->user_id != Auth::id()) {
            abort(404, 'Booking not found');
        }

        return view('booking.show', compact('booking'));
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking || $booking->user_id != Auth::id()) {
            abort(404, 'Booking not found');
        }

        $booking->delete();

        return redirect()->route('booking.index')->with('success', 'Booking deleted');
    }
}
