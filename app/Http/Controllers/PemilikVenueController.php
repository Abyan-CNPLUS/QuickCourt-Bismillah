<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venues;
use App\Models\Category;
use App\Models\Facility;
use App\Models\City;
use Illuminate\Support\Facades\Auth;

class PemilikVenueController extends Controller
{
    public function index()
    {
        $venues = Venues::with(['category', 'city', 'images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

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
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id'     => 'required|exists:cities,id',
            'description' => 'nullable|string',
        ]);

        $venue = Venues::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'city_id'     => $request->city_id,
            'description' => $request->description,
            'user_id'     => Auth::id(),
            'status'      => 'pending',
        ]);

        // ✅ kalau ada fasilitas dipilih
        if ($request->has('facilities')) {
            $venue->facilities()->sync($request->facilities);
        }

        return redirect()->route('Owner.venue.index')
                         ->with('success', 'Venue berhasil diajukan, tunggu approval admin!');
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
