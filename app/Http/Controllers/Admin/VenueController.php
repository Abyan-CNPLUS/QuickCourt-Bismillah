<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\City;
use App\Models\Category;
use App\Models\Venues;
use App\Models\VenueImage;
use App\Models\Facility;

class VenueController extends Controller
{
    // Halaman daftar venue
    public function index()
    {
        $venues = Venues::with(['category', 'city', 'facilities', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.venue.index', compact('venues'));
    }

    // Halaman form tambah venue
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $cities     = City::orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();

        return view('admin.venue.create', compact('categories', 'cities', 'facilities'));
    }

    // Simpan data venue baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'city_id'      => 'required|exists:city,id', // ← fix table name
            'address'      => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
            'status'       => 'required|in:available,booked',
            'capacity'     => 'required|integer|min:1',
            'images'       => 'required|array',
            'images.*'     => 'image|mimes:jpg,jpeg,png|max:10048',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        DB::beginTransaction();

        try {
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
                    'venue_id'   => $venue->id,
                    'image_url'  => $path,
                    'is_primary' => $index === 0 ? 1 : 0,
                ]);
            }

            // Simpan fasilitas ke pivot table
            $venue->facilities()->sync($validated['facilities'] ?? []);

            DB::commit();

            return redirect()->route('admin.venues.index')
                ->with('success', 'Venue berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // Halaman edit venue
    public function edit($id)
    {
        $venue      = Venues::with('facilities')->findOrFail($id);
        $categories = Category::all();
        $cities     = City::all();
        $facilities = Facility::all();

        return view('admin.venue.edit', compact('venue', 'categories', 'cities', 'facilities'));
    }

    // Update venue
    public function update(Request $request, $id)
{
    $venue = Venues::findOrFail($id);

    $validated = $request->validate([
        'name'         => 'required|string|max:255',
        'address'      => 'required|string|max:255',
        'capacity'     => 'required|integer|min:1',
        'price'        => 'required|numeric|min:0',
        'status'       => 'required|in:available,booked',
        'category_id'  => 'required|exists:categories,id',
        'city_id'      => 'required|exists:city,id',
        'facilities'   => 'nullable|array',
        'facilities.*' => 'exists:facilities,id',
        'images'       => 'nullable|array',
        'images.*'     => 'nullable|image|mimes:jpg,jpeg,png|max:10048',
    ]);

    // Hanya update field yang ada di tabel 'venues'
    $venueData = collect($validated)->except('facilities')->toArray();
    $venue->update($venueData);

    // Update gambar jika ada upload baru
if ($request->hasFile('images')) {
    foreach ($request->file('images') as $index => $image) {
        $path = $image->store('venues', 'public');

        VenueImage::create([
            'venue_id'   => $venue->id,
            'image_url'  => $path,
            'is_primary' => $venue->images()->count() === 0 && $index === 0 ? 1 : 0,
        ]);
    }
}


    // Update fasilitas
    $venue->facilities()->sync($request->facilities ?? []);

    // (tambahkan logika gambar jika perlu)

    return redirect()->route('admin.venues.index')
        ->with('success', 'Venue berhasil diperbarui!');
}


    // Hapus venue
    public function destroy(Venues $venue)
{
    // Hapus semua gambar terkait
    foreach ($venue->images as $img) {
        if (Storage::disk('public')->exists($img->image_url)) {
            Storage::disk('public')->delete($img->image_url);
        }
        $img->delete();
    }

    $venue->delete();

    return redirect()->route('admin.venues.index')
        ->with('success', 'Venue berhasil dihapus!');
}
}
