<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\City;
use App\Models\Category;
use App\Models\Venues;
use App\Models\VenueImage;

class VenueController extends Controller
{
    // Halaman list venue (Blade)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Venues::with(['category', 'city'])
            ->latest();

        if ($request->filled('category') && $request->category != 0) {
            $query->where('category_id', $request->category);
        }

        $venues = $query->paginate($perPage);

        return view('admin.venue.index', compact('venues'));
    }

    // Halaman form tambah venue
    public function create()
    {
        $categories = Category::all();
        $cities = City::all();
        return view('admin.venue.create', compact('categories', 'cities'));
    }

    // Proses simpan venue baru
   public function store(Request $request)
{
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'city_id'     => 'required|exists:city,id',
        'address'     => 'required|string|max:255',
        'price'       => 'required|numeric|min:0',
        'status'      => 'required|in:available,booked',
        'capacity'    => 'required|integer|min:1',
        'images'      => 'required|array',
        'images.*'    => 'image|mimes:jpg,jpeg,png|max:100048',
    ]);

    DB::beginTransaction();
    try {
        // Simpan venue
        $venue = Venues::create([
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'city_id'     => $validated['city_id'],
            'address'     => $validated['address'],
            'price'       => $validated['price'],
            'capacity'    => $validated['capacity'],
            'status'      => $validated['status'],
        ]);

        // Simpan gambar
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('venues', 'public');

            VenueImage::create([
                'venue_id'   => $venue->id,         // sesuai migration
                'image_url'  => $path,
                'is_primary' => $index === 0 ? 1 : 0,
            ]);
        }

        DB::commit();
        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue berhasil ditambahkan beserta gambarnya!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}



    // Halaman edit venue
    public function edit($id)
    {
        $venue = Venues::findOrFail($id);
        $categories = Category::all();
        $cities = City::all();
        return view('admin.venue.edit', compact('venue', 'categories', 'cities'));
    }
public function update(Request $request, $id)
{
    $venue = Venues::findOrFail($id);

    // Normalisasi harga (buang pemisah ribuan)
    $cleanPrice = str_replace(['.', ' '], '', $request->price);
    // Jika user pakai koma desimal, ubah ke titik
    $cleanPrice = str_replace(',', '.', $cleanPrice);
    $request->merge(['price' => $cleanPrice]);

    // Validasi — bikin status opsional agar tidak gagal diam-diam
    $validated = $request->validate([
        'name'        => 'required|string|max:255',
        'address'     => 'required|string|max:255',
        'capacity'    => 'required|integer|min:1',
        'price'       => 'required|numeric|min:0',
        'status'      => 'required|in:available,booked',
        'category_id' => 'required|exists:categories,id',
        'city_id'     => 'required|exists:city,id',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:100048',
        'images'      => 'nullable|array',
        'images.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:100048',
    ]);

    // Jika user tidak kirim status, pertahankan status lama
    if (! array_key_exists('status', $validated)) {
        $validated['status'] = $venue->status;
    }

    // Update gambar utama (opsional)
    if ($request->hasFile('image')) {
    if ($venue->image && \Storage::disk('public')->exists($venue->image)) {
        \Storage::disk('public')->delete($venue->image);
    }
    $validated['image'] = $request->file('image')->store('venue', 'public');
}


    // Update kolom utama
    $venue->update($validated);

    // Simpan gambar tambahan (opsional)
    if ($request->hasFile('image')) {
    // Cari gambar utama lama
    $oldImage = $venue->images()->where('is_primary', 1)->first();
    if ($oldImage && \Storage::disk('public')->exists($oldImage->image_url)) {
        \Storage::disk('public')->delete($oldImage->image_url);
    }
    if ($oldImage) {
        $oldImage->delete();
    }

    // Simpan gambar utama baru
    $path = $request->file('image')->store('venues', 'public');
    $venue->images()->create([
        'image_url'  => $path,
        'is_primary' => 1,
    ]);
}

    return redirect()
        ->route('admin.venues.index')
        ->with('success', 'Venue berhasil diperbarui!');
}
    // Hapus venue
    public function destroy(Venues $venue)
    {
        if ($venue->image && Storage::disk('public')->exists($venue->image)) {
            Storage::disk('public')->delete($venue->image);
        }

        $venue->delete();

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully!');
    }

    // Struck (contoh halaman khusus)
    public function struck()
    {
        $random = rand(1000, 9999);
        $date = now()->format('d-m-Y H:i:s');
        return view('admin.venues.struck', compact('random', 'date'));
    }
}
