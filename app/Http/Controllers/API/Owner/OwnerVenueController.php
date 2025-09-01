<?php

namespace App\Http\Controllers\API\Owner;

use App\Models\Venue;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OwnerVenueController extends Controller
{

    public function index()
    {
        $user = Auth::user();


        if ($user->role === 'admin') {
            $venues = Venues::with(['category', 'city'])->get();
        } else {

            $venues = Venues::with(['category', 'city'])
                ->where('user_id', $user->id)
                ->get();
        }

        return response()->json($venues);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:city,id',
            'facility_ids' => 'nullable|array',
            'facility_ids.*' => 'exists:facilities,id',
            'deskripsi' => 'required|string',
            'rules' => 'required|string',
            'longitude' => 'nullable|numeric|between:-180,180',
            'latitude' => 'nullable|numeric|between:-90,90',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'nullable|url',
        ]);

        $venue = Venues::create([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'capacity' => $validatedData['capacity'],
            'price' => $validatedData['price'],
            'status' => $validatedData['status'],
            'category_id' => $validatedData['category_id'],
            'city_id' => $validatedData['city_id'],
            'deskripsi' => $validatedData['deskripsi'],
            'rules' => $validatedData['rules'],
            'longitude' => $validatedData['longitude'] ?? null,
            'latitude' => $validatedData['latitude'] ?? null,
            'user_id' => Auth::user()->id,
        ]);

        if (!empty($validatedData['facility_ids'])) {
            $venue->facilities()->sync($validatedData['facility_ids']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('venues', 'public');
                DB::table('venue_images')->insert([
                    'venue_id' => $venue->id,
                    'image_url' => $path,
                    'is_primary' => $index === 0 ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (!empty($validatedData['image_urls'])) {
            foreach ($validatedData['image_urls'] as $index => $url) {
                DB::table('venue_images')->insert([
                    'venue_id' => $venue->id,
                    'image_url' => $url,
                    'is_primary' => ($request->hasFile('images') ? false : ($index === 0)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Venue created successfully',
            'venue' => $venue->load('facilities', 'images', 'city', 'category'),
        ], 201);
    }



    public function update(Request $request, Venues $venues)
    {
        $this->authorizeVenue($venues);

        $validatedData = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'city_id'     => 'sometimes|exists:city,id', // cek nama tabelmu
            'address'     => 'sometimes|string|max:255',
            'capacity'    => 'sometimes|integer',
            'price'       => 'sometimes|numeric',
            'status'      => 'sometimes|string',
            'deskripsi'   => 'sometimes|string',
            'rules'       => 'sometimes|string',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'facility_ids' => 'nullable|array',
            'facility_ids.*' => 'exists:facilities,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'nullable|url',
        ]);

        // Update data dasar venue
        $venues->update($validatedData);

        // Update facilities jika ada
        if (isset($validatedData['facility_ids'])) {
            $venues->facilities()->sync($validatedData['facility_ids']);
        }

        // Update images jika ada file baru
        if ($request->hasFile('images')) {
            // Bisa kamu tambahkan logic hapus gambar lama dulu kalau perlu

            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('venues', 'public');
                DB::table('venue_images')->insert([
                    'venue_id' => $venues->id,
                    'image_url' => $path,
                    'is_primary' => $index === 0 ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        
        if (!empty($validatedData['image_urls'])) {
            foreach ($validatedData['image_urls'] as $index => $url) {
                DB::table('venue_images')->insert([
                    'venue_id' => $venues->id,
                    'image_url' => $url,
                    'is_primary' => ($request->hasFile('images') ? false : ($index === 0)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json($venues->load('facilities', 'images', 'city', 'category'));
    }




    public function destroy(Venues $venues)
    {
        $this->authorizeVenue($venues);
        $venues->delete();
        return response()->json(['message' => 'Venue deleted successfully']);
    }


    private function authorizeVenue(Venues $venue)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $venue->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
    }

    public function checkVenueStatus(Request $request)
    {
        $venues = Venues::where('user_id', auth()->id())->get();

        if ($venues->isEmpty()) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => true,
            'venues' => $venues
        ]);
    }

    public function toggleStatus(Request $request, Venues $venue)
    {
        $venue->is_closed = !$venue->is_closed;
        $venue->save();

        return response()->json([
            'message' => $venue->is_closed ? 'Venue ditutup (libur)' : 'Venue dibuka',
            'is_closed' => $venue->is_closed
        ]);
    }
}
