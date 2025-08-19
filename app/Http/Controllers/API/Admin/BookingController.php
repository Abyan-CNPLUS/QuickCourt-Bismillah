<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    //
    public function index()
    {
        $bookings = Booking::with(['user', 'venue'])->orderBy('booking_date', 'desc')->get();
        return response()->json($bookings);
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'venue'])->findOrFail($id);
        return response()->json($booking);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,cancelled,completed',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'message' => 'Status booking berhasil diperbarui',
            'data' => $booking
        ]);
    }
}
