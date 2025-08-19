<?php

namespace App\Http\Controllers\API;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fnb_categories;
use App\Models\Fnb_menu;
use App\Models\Venues;

class FnbController extends Controller
{
    //

    public function getCities()
    {
        $cities = City::has('venues')->get();
        return response()->json($cities);
    }


    public function getVenuesByCity($cityId)
    {
        $venues = Venues::with('city', 'category')
                    ->where('city_id', $cityId)
                    ->get();

        return response()->json($venues);
    }

    public function getFnbMenuByVenue($venueId)
    {
        $menus = Fnb_menu::with('category')
            ->where('venue_id', $venueId)
            ->get();

        return response()->json($menus);
    }


    public function getFnbCategoriesByVenue($venueId)
    {
        $categories = Fnb_categories::whereHas('menus', function($query) use ($venueId) {
            $query->where('venue_id', $venueId);
        })->get();

        return response()->json($categories);
    }

    public function getFnbMenusByCategoryAndVenue($venueId, $categoryId)
    {
        $menus = Fnb_menu::with('category')
                ->where('venue_id', $venueId)
                ->where('categories_id', $categoryId)
                ->get();

        return response()->json($menus);
    }


}
