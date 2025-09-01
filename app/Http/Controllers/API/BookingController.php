<?php

namespace App\Http\Controllers\API;

use App\Models\Venues;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->with('venue')->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    public function create()
    {
        $venues = Venues::all();
        return response()->json([
            'success' => true,
            'data' => $venues,
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'guest') {
            return response()->json([
                'success' => false,
                'message' => 'Guest users are not allowed to make bookings.'
            ], 403);
        }

        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'contact_number' => 'required|string',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            $venue = Venues::findOrFail($request->venue_id);

            if ($request->start_time < $venue->open_time || $request->end_time > $venue->close_time) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking time must be within venue operating hours (' . $venue->open_time . ' - ' . $venue->close_time . ')',
                ], 422);
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
                return response()->json([
                    'success' => false,
                    'message' => 'Venue is already booked in this time',
                ], 422);
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

            
            $payment = Payment::create([
                'order_id' => 'ORD-' . strtoupper(Str::random(10)),
                'booking_id' => $booking->id,
                'amount' => $request->total_price,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat, silakan lakukan pembayaran.',
                'data' => [
                    'booking' => $booking,
                    'payment' => $payment,
                ],
            ], 201);
        });
    }

    public function show($id)
    {
        $booking = Booking::with('venue')->find($id);

        if (!$booking || $booking->user_id != Auth::id()) {
            abort(404, 'Booking not found');
        }

        return response()->json([
            'success' => true,
            'data' => $booking,
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking || $booking->user_id != Auth::id()) {
            abort(404, 'Booking not found');
        }

        $booking->delete();


        return response()->json([
            'success' => true,
            'message' => 'Booking deleted'
        ]);
    }
}
