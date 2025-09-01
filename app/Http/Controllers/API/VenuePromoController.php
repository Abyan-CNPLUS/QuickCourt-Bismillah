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

        $promos = VenuePromo::where('end_date', '>=', $now)
            ->orderBy('start_date', 'asc')
            ->paginate($limit);

        return response()->json($promos);
    }

    public function show(VenuePromo $venuePromo)
    {
        return response()->json($venuePromo);
    }

    public function store(Request $request)
    {
        $request->request->remove('image_url'); // Cegah manual input
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

    public function update(Request $request, VenuePromo $venuePromo)
    {
        $request->request->remove('image_url'); // Cegah manual input

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

        return response()->json([
            'message' => 'Promo berhasil diperbarui.',
            'data' => $venuePromo
        ]);
    }

    public function destroy(VenuePromo $venuePromo)
    {
        $venuePromo->delete();

        return response()->json(['message' => 'Promo berhasil dihapus.']);
    }
}
