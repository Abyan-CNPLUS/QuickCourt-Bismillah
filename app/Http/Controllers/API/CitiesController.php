<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    //
    public function index()
    {
        return response()->json(City::all());
    }
}
