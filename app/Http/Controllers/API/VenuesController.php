<?php

namespace App\Http\Controllers\API;


use Log;
use App\Models\Venues;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\VenuesResource;
use Illuminate\Support\Facades\DB;

class VenuesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $query = Venues::with(['category', 'city', 'primaryImage'])
                ->orderBy('created_at', 'asc');

            if ($request->has('category') && $request->category != 0) {
                $query->where('category_id', $request->category);
            }

            $venues = $query->paginate($perPage);

            return VenuesResource::collection($venues);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
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
            'venue' => $venue->load('facilities', 'images'),
        ], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $venue = Venues::with(['category', 'city', 'images', 'facilities'])->find($id);
        if (!$venue) {
            return response()->json(['message' => 'Venue not found'], 404);
        }


        $venue->images = $venue->images->map(function ($img) {
            $img->image_url = asset('storage/' . $img->image_url);
            return $img;
        });

        return response()->json([
            'message' => 'Venue details',
            'venue' => $venue,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $venue = Venues::find($id);
        if (!$venue) {
            return response()->json(['message' => 'Venue not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'capacity' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'city_id' => 'sometimes|required|exists:city,id',
            'facility_ids' => 'nullable|array',
            'facility_ids.*' => 'exists:facilities,id',
            'deskripsi' => 'sometimes|required|string',
            'rules' => 'sometimes|required|string',


            'images' => 'nullable|array',
            'images.*' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',

            'image_urls' => 'nullable|array',
            'image_urls.*' => 'nullable|url',


            'remove_image_ids' => 'nullable|array',
            'remove_image_ids.*' => 'exists:venue_images,id',


            'primary_image_id' => 'nullable|exists:venue_images,id',
        ]);


        $venue->update($validatedData);


        if (isset($validatedData['facility_ids'])) {
            $venue->facilities()->sync($validatedData['facility_ids']);
        }


        if (!empty($validatedData['remove_image_ids'])) {
            DB::table('venue_images')
                ->whereIn('id', $validatedData['remove_image_ids'])
                ->where('venue_id', $venue->id)
                ->delete();
        }


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('venues', 'public');
                DB::table('venue_images')->insert([
                    'venue_id' => $venue->id,
                    'image_url' => $path,
                    'is_primary' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }


        if (!empty($validatedData['image_urls'])) {
            foreach ($validatedData['image_urls'] as $url) {
                DB::table('venue_images')->insert([
                    'venue_id' => $venue->id,
                    'image_url' => $url,
                    'is_primary' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }


        if (!empty($validatedData['primary_image_id'])) {
            DB::table('venue_images')
                ->where('venue_id', $venue->id)
                ->update(['is_primary' => 0]);

            DB::table('venue_images')
                ->where('id', $validatedData['primary_image_id'])
                ->where('venue_id', $venue->id)
                ->update(['is_primary' => 1]);
        }

        return response()->json([
            'message' => 'Venue updated successfully',
            'venue' => $venue->load('facilities', 'images'),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $venue = Venues::find($id);
        if (!$venue) {
            return response()->json(['message' => 'Venue not found'], 404);
        }
        $venue->delete();
        return response()->json(['message' => 'Venue deleted successfully']);
    }

    public function getFacilities()
    {
        return response()->json([
            'facilities' => Facility::all()
        ]);
    }

    public function availableTimes(Request $request, $id)
    {
        $venue = Venues::findOrFail($id);
        $date = $request->input('date');

        $open = Carbon::createFromTimeString($venue->open_time);
        $close = Carbon::createFromTimeString($venue->close_time);
        $slots = [];

        while ($open->lt($close)) {
            $start = $open->format('H:i');
            $end = $open->copy()->addHour()->format('H:i');


            $isBooked = Booking::where('venue_id', $venue->id)
                ->whereDate('booking_date', $date)
                ->where(function ($query) use ($start, $end) {
                    $query->where(function ($q) use ($start, $end) {
                        $q->where('start_time', '<', $end)
                        ->where('end_time', '>', $start);
                    });
                })
                ->exists();

            $slots[] = [
                'start_time' => $start,
                'end_time' => $end,
                'is_booked' => $isBooked,
            ];

            $open->addHour();
        }

        return response()->json([
            'slots' => $slots,
        ]);
    }


}
