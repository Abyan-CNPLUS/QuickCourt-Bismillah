<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\venues;
use App\Models\Booking;
use App\Models\category;
use App\Models\Fnb_menu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // Fetch the count of venues from the database
        $venueCount = venues::count();
        $bookingCount = Booking::count();
        $userCount = User::count();
        $foodcount = Fnb_menu::count();

        $bookings = Booking::selectRaw('MONTH(booking_date) as month, COUNT(*) as total')
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
         $categories = Category::latest()->get();

        return view('admin.dashboard', compact(
            'venueCount',
            'bookingCount',
            'userCount',
            'foodcount',
            'labels',
            'data',
            'categories',
        ));
    }


}
