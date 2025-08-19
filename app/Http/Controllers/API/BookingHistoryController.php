<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class BookingHistoryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $bookings = Booking::with([
            'venue.city',
            'venue.primaryImage'
        ])
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($bookings);
    }
}
