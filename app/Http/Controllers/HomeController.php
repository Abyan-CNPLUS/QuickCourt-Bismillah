<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\City;
use App\Models\Venues;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
   public function index()
{
    $locations = City::all();
    $categorys = Category::all();

    // ambil 4 venue yang statusnya approved
    $venues = Venues::where('approval_status', 'approved')
                    ->latest()
                    ->take(8)
                    ->get();

    return view('home', compact('locations', 'categorys', 'venues'));
}
}
