<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venues;

class AdminController extends Controller
{
    // Menampilkan dashboard admin
    public function dashboard()
{
    // Hitung total venue pending
    $pendingCount = Venues::where('status', 'pending')->count();

    // Variabel lain bisa tetap dikirim
    $venueCount = Venues::count();
    $bookingCount = Booking::count();
    $userCount = User::count();
    $foodcount = FoodMenu::count();

    return view('admin.dashboard', compact(
        'venueCount', 'bookingCount', 'userCount', 'foodcount', 'pendingCount'
    ));
}


    // Menampilkan daftar venue pending untuk disetujui admin
    public function venueApproval()
    {
        $venues = Venues::with('owner')->where('status', 'pending')->get();
        return view('admin.venue-approval', compact('venues'));
    }

    // Update status venue (accept / reject)
    public function updateVenueStatus(Request $request, Venues $venue)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $venue->status = $request->status;
        $venue->save();

        return redirect()->back()->with('success', 'Status venue berhasil diupdate.');
    }
}
