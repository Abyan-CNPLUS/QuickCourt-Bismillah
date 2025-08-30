<?php

namespace App\Http\Controllers\API;

use App\Models\Venues;
use App\Models\VenuePromo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //
    public function index()
    {
        try {
            $venues = Venues::with(['category', 'city', 'primaryImage'])
                ->orderBy('created_at', 'asc')
                ->take(3)
                ->get();

            $venues->map(function ($venue) {
                $venue->thumbnail = $venue->primaryImage
                    ? asset('storage/' . $venue->primaryImage->image_url)
                    : null;
                return $venue;
            });

            // Ambil 4 promo aktif terbaru
            $now = now();
            $promos = VenuePromo::where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            // Tambah URL lengkap untuk image_url pada promos
            $promos->map(function ($promo) {
                if ($promo->image_url) {
                    $promo->image_url = asset('storage/' . $promo->image_url);
                }
                return $promo;
            });

            return response()->json([
                'venues' => $venues,
                'promos' => $promos,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
