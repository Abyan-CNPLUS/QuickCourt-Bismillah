<?php

namespace App\Http\Controllers\API;

use App\Models\Venues;
use App\Models\Booking;
use App\Helpers\FcmHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Helpers\FcmNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->with('venue')->latest()->get();

        return response()->json([
            'message' => 'List of bookings',
            'data' => $bookings,
        ]);
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        if (auth()->user()->role === 'guest') {
            return response()->json([
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
        ]);


        $venue = Venues::findOrFail($request->venue_id);


        if (
            $request->start_time < $venue->open_time || $request->end_time > $venue->close_time
        ) {
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
            })
            ->exists();

        if ($isBooked) {
            return response()->json([
                'success' => false,
                'message' => 'Venue is already booked in this time',
            ], 409);
        }


        $booking = Booking::create([
            'user_id' => $user->id,
            'venue_id' => $request->venue_id,
            'contact_number' => $request->contact_number,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking successfully',
            'data' => $booking,
        ], 201);

        // $fcmToken = $user->fcm_token;
        // $title = 'Booking Berhasil';
        // $body = 'Booking kamu di ' . $venue->name . ' sudah dikonfirmasi.';

        // $fcm = new FcmHelper();
        // $fcm->sendNotification($fcmToken, $title, $body);

    }



    public function show(string $id)
    {
        $booking = Booking::with('venue')->find($id);

        if (!$booking || $booking->user_id != Auth::id()) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json([
            'message' => 'Detail booking',
            'data' => $booking,
        ]);
    }


    public function update(Request $request, string $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);


        if ($request->status === 'cancelled') {
            if ($booking->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } elseif (!Auth::user()->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $booking->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status already updated',
            'data' => $booking,
        ]);
    }


    public function destroy(string $id)
    {
        $booking = Booking::find($id);

        if (!$booking || $booking->user_id != Auth::id()) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();

        return response()->json([
            'message' => 'Booking deleted'
        ]);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'booking_date' => 'required|date',
        ]);

        $bookedSlots = Booking::where('venue_id', $request->venue_id)
            ->whereDate('booking_date', $request->booking_date)
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time']);

        return response()->json([
            'message' => 'Booked time slots',
            'data' => $bookedSlots,
        ]);
    }


}
