<?php

namespace App\Http\Controllers\API;

use App\Models\Venues;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //
    public function index()
    {
        return response()->json(Category::all());
    }
}
