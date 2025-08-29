<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Fnb_categories;
use Illuminate\Http\Request;

class FnbCategoriesController extends Controller
{
    //
    public function index()
    {
        return response()->json(Fnb_categories::all());
    }
}
