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
    // Halaman daftar venue (yang sudah approved saja)
    public function index()
    {
        $venues = Venues::with(['category', 'city', 'facilities', 'images'])
            ->where('approval_status', 'approved') // hanya tampil yang sudah approve
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.venue.index', compact('venues'));
    }

    // Halaman approval list (khusus pending)
    public function approvalList()
    {
        $venues = Venues::with(['category', 'city'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.venue.Aproval', compact('venues'));
    }

    // Approve venue
    public function approve($id)
    {
        $venue = Venues::findOrFail($id);
        $venue->update(['approval_status' => 'approved']);

        return redirect()->back()->with('success', 'Venue berhasil disetujui!');
    }

    // Reject venue
    public function reject($id)
    {
        $venue = Venues::findOrFail($id);
        $venue->update(['approval_status' => 'rejected']);

        return redirect()->back()->with('success', 'Venue berhasil ditolak!');
    }

    // Show detail venue
    public function show($id)
    {
        $venue = Venues::with(['city', 'category', 'user', 'facilities', 'images'])
            ->findOrFail($id);

        return view('admin.venue.show', compact('venue'));
    }

    // Form tambah venue (mungkin dipakai owner, bukan admin)
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $cities     = City::orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();

        return view('admin.venue.create', compact('categories', 'cities', 'facilities'));
    }

    // Store venue
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
            ]);

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('venues', 'public');

                VenueImage::create([
                    'venue_id'   => $venue->id,
                    'image_url'  => $path,
                    'is_primary' => $index === 0 ? 1 : 0,
                ]);
            }

            $venue->facilities()->sync($validated['facilities'] ?? []);

            DB::commit();

            return redirect()->route('owner.dashboard') // balik ke owner dashboard
                ->with('success', 'Venue berhasil ditambahkan dan menunggu approval admin!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
{
    DB::beginTransaction();

    try {
        $venue = Venues::with('images')->findOrFail($id);

        // Hapus semua gambar di storage
        foreach ($venue->images as $image) {
            if (Storage::disk('public')->exists($image->image_url)) {
                Storage::disk('public')->delete($image->image_url);
            }
            $image->delete(); // hapus record di table venue_images
        }

        // Hapus relasi fasilitas (pivot table)
        $venue->facilities()->detach();

        // Hapus venue
        $venue->delete();

        DB::commit();

        return redirect()->route('admin.venue.index')
            ->with('success', 'Venue berhasil dihapus!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
}
