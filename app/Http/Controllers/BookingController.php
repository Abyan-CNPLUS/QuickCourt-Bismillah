<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dateplay;
use App\Models\Time_avalaible;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    // TAMPILKAN FORM BOOKING
    public function index(Request $request)
    {
        
    }

    // SIMPAN DATA BOOKING
    public function store(Request $request)
{
    $request->validate([
        'jam' => 'required|date_format:H:i'
    ]);

    // Ambil semua tanggal di dateplay
    $dates = Dateplay::where('Tanggal', '>=', now())
                     ->orderBy('Tanggal', 'asc')
                     ->take(7)
                     ->get();

    foreach ($dates as $date) {
        Time_avalaible::create([
            'tanggal' => $date->Tanggal,
            'jam' => $request->jam
        ]);
    }

    return redirect()->back()->with('success', 'Jam berhasil dibuat untuk semua tanggal!');
}
}
