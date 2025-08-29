<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Venues;

class VenuePemilikController extends Controller
{
   public function index()
{
    $venues = Venues::where('user_id', Auth::id())->get();
    return view('owner.venue.index', compact('venues'));
}
}
