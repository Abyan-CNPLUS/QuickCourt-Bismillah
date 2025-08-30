<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venues;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\VenueImage;
use App\Models\Facility;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

class PemilikVenueController extends Controller
{
    // Tampilkan semua venue milik owner
    public function index()
    {
        $venues = Venues::with(['category','city','images'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at','desc')
            ->paginate(10); // paginate supaya bisa pakai links()
        return view('Owner.venue.index', compact('venues'));
    }

    // Form tambah venue
    public function create()
    {
        $categories = Category::all();
        $cities     = City::all();
        $facilities = Facility::all(); // ✅ tambahkan

        return view('Owner.venue.create', compact('categories', 'cities', 'facilities'));
    }

    // Simpan venue baru
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'         => 'required|string|max:255',
        'category_id'  => 'required|exists:categories,id',
        'city_id'      => 'required|exists:city,id',
        'address'      => 'required|string|max:255',
        'price'        => 'required|numeric|min:0',
        'capacity'     => 'required|integer|min:1',
        'status'       => 'required|in:available,booked',
        'images'       => 'required|array',
        'images.*'     => 'image|mimes:jpg,jpeg,png|max:10048',
        'facilities'   => 'nullable|array',
        'facilities.*' => 'exists:facilities,id',
    ]);

    DB::beginTransaction();

    try {
        $venue = Venues::create([
            'name'            => $validated['name'],
            'category_id'     => $validated['category_id'],
            'city_id'         => $validated['city_id'],
            'address'         => $validated['address'],
            'price'           => $validated['price'],
            'capacity'        => $validated['capacity'],
            'status'          => $validated['status'],
            'approval_status' => 'pending', // default pending
            'user_id'         => Auth::id(), // simpan siapa ownernya
        ]);

        // ✅ simpan gambar
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('venues', 'public');

            VenueImage::create([
                'venue_id'   => $venue->id,
                'image_url'  => $path,
                'is_primary' => $index === 0 ? 1 : 0,
            ]);
        }

        // ✅ kalau ada fasilitas
        $venue->facilities()->sync($validated['facilities'] ?? []);

        DB::commit();

        return redirect()->route('owner.venues.index')
            ->with('success', 'Venue berhasil diajukan, tunggu approval admin!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}

    // Form edit venue
    public function edit(Venues $venue)
    {
        $this->authorizeVenue($venue);

        $categories = Category::all();
        $cities     = City::all();
        $facilities = Facility::all(); // ✅ tambahkan

        return view('Owner.venue.edit', compact('venue','categories','cities','facilities'));
    }

    // Update venue
    public function update(Request $request, Venues $venue)
    {
        $this->authorizeVenue($venue);

        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id'     => 'required|exists:cities,id',
            'description' => 'nullable|string',
        ]);

        $venue->update($request->only(['name','category_id','city_id','description']));

        // ✅ update fasilitas juga
        if ($request->has('facilities')) {
            $venue->facilities()->sync($request->facilities);
        }

        return redirect()->route('Owner.venue.index')
                         ->with('success', 'Venue berhasil diperbarui!');
    }

    // Hapus venue
    public function destroy(Venues $venue)
    {
        $this->authorizeVenue($venue);
        $venue->delete();

        return redirect()->route('Owner.venue.index')
                         ->with('success', 'Venue berhasil dihapus!');
    }

    // Authorization helper
    private function authorizeVenue(Venues $venue)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $venue->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
    }
}
