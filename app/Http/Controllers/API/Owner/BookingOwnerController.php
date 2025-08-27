<?php

namespace App\Http\Controllers\API\Owner;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BookingOwnerController extends Controller
{
    //
    public function index($venueId)
    {
        $bookings = Booking::with([
            'user',
            'venue.city',
            'venue.primaryImage'
        ])
            ->where('venue_id', $venueId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'user' => [
                        'name' => $booking->user->name ?? 'Unknown',
                        'id' => $booking->user->id ?? null,
                        'photo_url' => $booking->user->photo_url ?? null,
                    ],
                    'venue' => [
                        'name' => $booking->venue->name ?? '',
                        'city' => [
                            'name' => $booking->venue->city->name ?? '',
                        ],
                        'primary_image' => $booking->venue->primaryImage ? [
                            'image_url' => $booking->venue->primaryImage->image_url,
                        ] : null,
                    ],
                    'status' => $booking->status,
                    'booking_date' => $booking->booking_date,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'total_price' => $booking->total_price,
                ];
            });

        return response()->json([
            'status' => 'success',
            'bookings' => $bookings,
        ]);
    }



    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,cancelled,rejected,completed',
        ]);

        // $user = Auth::user();


        // if (!$user->venues()->where('id', $booking->venue_id)->exists()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized'
        //     ], 403);
        // }

        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Booking status updated',
        ]);
    }
}
