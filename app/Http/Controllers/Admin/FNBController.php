<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fnb_menu;
use App\Models\Fnb_categories;
use App\Models\Venues;
use Illuminate\Http\Request;

class FNBController extends Controller
{
    public function index()
{
    $fnbMenus = Fnb_menu::with(['category', 'venue'])->latest()->paginate(10);
    return view('admin.Food_menu.index', compact('fnbMenus'));
}
    public function create()
    {
        $categories = Fnb_categories::all();
        $venues = Venues::all(); // Pakai Venues
        return view('admin.Food_menu.create', compact('categories', 'venues'));
    }

    public function store(Request $request)
{
    // Bersihkan harga sebelum validasi
    $cleanPrice = str_replace('.', '', $request->price);
    $cleanPrice = str_replace(',', '.', $cleanPrice);
    $request->merge(['price' => $cleanPrice]);

    // Validasi
    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'categories_id' => 'required|exists:fnb_categories,id',
        'venue_id'      => 'required|exists:venues,id',
        'price'         => 'required|numeric',
        'description'   => 'nullable|string',
        'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Upload image jika ada
    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('fnb_images', 'public');
    }

    // Simpan data
    Fnb_menu::create($validated);

    return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan');
}

public function update(Request $request, $id)
{
    $menu = Fnb_menu::findOrFail($id);

    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'categories_id' => 'required|exists:fnb_categories,id',
        'venue_id'      => 'required|exists:venues,id',
        'price'         => 'required|numeric',
        'description'   => 'nullable|string',
        'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('fnb_images', 'public');
    }

    $menu->update($validated);

    return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui');
}

public function edit($id)
{
    $menu = Fnb_menu::findOrFail($id);
    $categories = Fnb_categories::all();
    $venues = Venues::all();

    return view('admin.Food_menu.edit', compact('menu', 'categories', 'venues'));
}



    public function destroy($id)
    {
        $fnbMenus = Fnb_menu::findOrFail($id);

        if ($fnbMenus->image && \Storage::disk('public')->exists($fnbMenus->image)) {
            \Storage::disk('public')->delete($fnbMenus->image);
        }

        $fnbMenus->delete();

        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus');
    }
}
