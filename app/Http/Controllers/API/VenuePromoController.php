<?php

namespace App\Http\Controllers\API;

use App\Models\VenuePromo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VenuePromoController extends Controller
{
    //
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $now = now();

        $promos = VenuePromo::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);


        $promos->getCollection()->transform(function ($promo) {
            if ($promo->image_url) {
                $promo->image_url = asset('storage/' . $promo->image_url);
            }
            return $promo;
        });

        return response()->json($promos);
    }


    /**
     * Store a newly created promo in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id'    => 'required|integer',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promo_images', 'public');
            $validated['image_url'] = $path;
        }

        $promo = VenuePromo::create($validated);

        return response()->json([
            'message' => 'Promo berhasil ditambahkan.',
            'data' => $promo
        ], 201);
    }


    /**
     * Display the specified promo.
     */
    public function show(VenuePromo $venuePromo)
    {
        // Tambahkan base URL juga di show
        if ($venuePromo->image_url) {
            $venuePromo->image_url = asset('storage/' . $venuePromo->image_url);
        }
        return response()->json($venuePromo);
    }

    /**
     * Update the specified promo in storage.
     */
    public function update(Request $request, VenuePromo $venuePromo)
    {
        $validated = $request->validate([
            'venue_id'    => 'sometimes|integer',
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'start_date'  => 'sometimes|date',
            'end_date'    => 'sometimes|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promo_images', 'public');
            $validated['image_url'] = $path;
        }

        $venuePromo->update($validated);

        if ($venuePromo->image_url) {
            $venuePromo->image_url = asset('storage/' . $venuePromo->image_url);
        }

        return response()->json([
            'message' => 'Promo berhasil diperbarui.',
            'data' => $venuePromo
        ]);
    }

    public function destroy(VenuePromo $venuePromo)
    {
        $venuePromo->delete();

        return response()->json([
            'message' => 'Promo berhasil dihapus.'
        ]);
    }
}
