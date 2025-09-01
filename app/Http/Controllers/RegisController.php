<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class RegisController extends Controller
{
    public function createOwner() {
    return view('auth.regis-owner');
}

public function addOwner(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone' => 'required'
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'role' => 'owner', // ✅ otomatis owner
    ]);

    return redirect()->route('login')->with('success', 'Registrasi owner berhasil, silakan login.');
    }
      public function create(){
        return view('auth.regis');

    }


   public function adduser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone' => 'required'
    ]);

    // Simpan user ke database
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'role' => 'user', // ✅ tambahkan ini
    ]);

    return redirect()->route('login');
}

}
