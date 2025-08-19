<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function index()
    {
        return view('midtrans',compact('snapToken'));
    }
}
