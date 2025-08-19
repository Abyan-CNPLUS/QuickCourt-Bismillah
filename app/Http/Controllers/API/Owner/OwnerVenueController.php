<?php

namespace App\Http\Controllers\API\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\Venues;
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
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id'     => 'required|exists:cities,id',
            'description' => 'nullable|string',
        ]);

        $venue = Venues::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'city_id'     => $request->city_id,
            'description' => $request->description,
            'user_id'     => Auth::id(),
        ]);

        return response()->json($venue, 201);
    }


    public function update(Request $request, Venues $venues)
    {
        $this->authorizeVenue($venues);

        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'city_id'     => 'sometimes|exists:cities,id',
            'description' => 'nullable|string',
        ]);

        $venues->update($request->all());

        return response()->json($venues);
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
}
