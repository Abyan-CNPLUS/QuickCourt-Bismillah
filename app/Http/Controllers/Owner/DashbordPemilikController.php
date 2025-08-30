<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Fnb_menu;

class DashbordPemilikController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $venueCount = $user->venues()->count();

        $bookingCount = Booking::whereHas('venue', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $foodcount = Fnb_menu::whereHas('venue', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $bookings = Booking::whereHas('venue', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->selectRaw('MONTH(booking_date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = date('M', mktime(0, 0, 0, $i, 1));
            $found = $bookings->firstWhere('month', $i);
            $data[] = $found ? $found->total : 0;
        }

        // ✅ Tambahin categories di sini
        $categories = Category::latest()->get();

        return view('Owner.Dashbord', compact(
            'venueCount',
            'bookingCount',
            'foodcount',
            'labels',
            'data',
            'categories' // dikirim ke view
        ));
    }
}
