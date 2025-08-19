<?php

namespace App\Http\Controllers\API;

use App\Models\Venues;
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

            return response()->json([
                'venues' => $venues
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
