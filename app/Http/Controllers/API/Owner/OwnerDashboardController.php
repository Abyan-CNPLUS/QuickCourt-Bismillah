<?php

namespace App\Http\Controllers\API\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        $perluDiproses = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $user->id)
            ->where('bookings.status', 'pending')
            ->count();


        $kendala = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $user->id)
            ->where('bookings.status', 'rejected')
            ->count();


        $dibatalkan = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $user->id)
            ->where('bookings.status', 'cancelled')
            ->count();


        $totalBookings = DB::table('bookings')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $user->id)
            ->count();


        $totalTransactions = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $user->id)
            ->where('payments.status', 'confirmed')
            ->count();


        $totalBalance = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('venues', 'bookings.venue_id', '=', 'venues.id')
            ->where('venues.user_id', $user->id)
            ->where('payments.status', 'confirmed')
            ->sum('payments.amount');

        return response()->json([
            'perluDiproses' => $perluDiproses,
            'kendala' => $kendala,
            'dibatalkan' => $dibatalkan,
            'totalBookings' => $totalBookings,
            'totalTransactions' => $totalTransactions,
            'totalBalance' => $totalBalance
        ]);
    }
}
