<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fnb_categories;
use App\Models\Fnb_menu;

class FoodMenuController extends Controller
{
    public function index()
{
    $categories = Fnb_categories::all();
    $menus = Fnb_menu::all(); // Ambil semua menu
    return view('fnb', compact('categories', 'menus'));
}
   public function getMenusByCategory($id)
{
    $menus = Fnb_menu::where('categories_id', $id)->get();
    return response()->json($menus);
}


    public function all()
    {
    $menus = Fnb_menu::all();
    return response()->json($menus);
}
public function store(Request $request)
{
    // Ambil input mentah price dari form
    $rawPrice = $request->price;

    // Bersihkan input price:
    // - hapus titik sebagai pemisah ribuan
    // - ganti koma sebagai desimal jadi titik
    $cleanPrice = str_replace('.', '', $rawPrice);
    $cleanPrice = str_replace(',', '.', $cleanPrice);

    // Update request supaya 'price' sudah bersih sebelum validasi
    $request->merge(['price' => $cleanPrice]);

    // Validasi data setelah price sudah bersih
    $request->validate([
        'name'          => 'required|string|max:255',
        'categories_id' => 'required|exists:fnb_categories,id',
        'venue_id'      => 'required|exists:venues,id',
        'price'         => 'required|numeric',
        'description'   => 'nullable|string',
        'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Ambil data lengkap dari request yang sudah tervalidasi
    $data = $request->all();

    // Jika ada file gambar, simpan file-nya
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('fnb_images', 'public');
    }

    // Simpan data ke database
    Fnb_menu::create($data);

    // Redirect dengan pesan sukses
    return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan');
}
}
