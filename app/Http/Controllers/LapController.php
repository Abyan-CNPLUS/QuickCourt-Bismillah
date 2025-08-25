<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Venues;
use App\Models\Category;
use Illuminate\Http\Request;

class LapController extends Controller
{
    public function index()
    {
        $cities = City::all();
        $categories = Category::all();
        $venues = Venues::latest('updated_at')->get();

        return view('venue.venue', compact('cities', 'categories', 'venues'));
    }

    // Show detail venue
    public function show(Venues $venue)
    {
        // Eager load relasi
        $venue->load(['city', 'category', 'images', 'facilities']);

        return view('venue.show', compact('venue'));
    }

    public function filter(Request $request)
{
    $query = Venues::with(['city', 'category', 'images']);

    if ($request->city_id) {
        $query->where('city_id', $request->city_id);
    }

    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    // Sorting
    if ($request->sort_by) {
        [$field, $direction] = explode('-', $request->sort_by);
        $query->orderBy($field, $direction);
    } else {
        $query->orderBy('updated_at', 'desc'); // default
    }

    return response()->json($query->get());
}
}
