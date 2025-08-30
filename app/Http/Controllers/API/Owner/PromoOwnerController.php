<?php

namespace App\Http\Controllers\API\Owner;

use App\Models\Venues;
use App\Models\VenuePromo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PromoOwnerController extends Controller
{
    //
    public function index($venueId)
    {

        $venue = Venues::where('id', $venueId)
            ->where('user_id', Auth::id())
            ->firstOrFail();


        $promos = $venue->venuePromos()->latest()->get();


        $promos->map(function ($promo) {
            if ($promo->image_url && !str_starts_with($promo->image_url, 'http')) {
                $promo->image_url = asset('storage/' . $promo->image_url);
            }
        });

        return response()->json([
            'promos' => $promos,
        ]);
    }

    public function store(Request $request, $venueId)
    {
        $venue = Venues::where('id', $venueId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promo_images', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $validated['venue_id'] = $venue->id;

        $promo = VenuePromo::create($validated);

        return response()->json([
            'message' => 'Promo berhasil dibuat.',
            'data'    => $promo
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $promo = VenuePromo::findOrFail($id);


        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'sometimes|date',
            'end_date'    => 'sometimes|date|after_or_equal:start_date',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {

            if ($promo->image_url) {
                $relativePath = str_replace('/storage/', '', $promo->image_url);
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
            }

            $path = $request->file('image')->store('promo_images', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $promo->update($validated);

        return response()->json([
            'message' => 'Promo berhasil diperbarui.',
            'data' => $promo
        ]);
    }


    public function destroy($id)
    {
        $promo = VenuePromo::findOrFail($id);



        if ($promo->image_url) {
            $relativePath = str_replace('/storage/', '', $promo->image_url);
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }


        $promo->delete();

        return response()->json(['message' => 'Promo berhasil dihapus.']);
    }
}
