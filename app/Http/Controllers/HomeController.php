<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\City;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $locations = City::all();
        $categorys = category::all();

        return view('home', compact('locations', 'categorys'));
    }



}
