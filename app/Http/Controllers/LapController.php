<?php
// App\Http\Controllers\LapController.php
namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Venues;     // <- pastikan 'Venues' (bukan 'venues')
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
        // eager load relasi
        $venue->load(['city','category','images']);

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

    $venues = $query->get();

    // biar JSON response sesuai kebutuhan AJAX
    return response()->json($venues);
}
}
