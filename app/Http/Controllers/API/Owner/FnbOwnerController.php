<?php

namespace App\Http\Controllers\API\Owner;

use App\Models\Venues;
use App\Models\Fnb_menu;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FnbOwnerController extends Controller
{
    //
    public function index($venueId)
    {
        $venue = Venues::where('id', $venueId)
                      ->where('user_id', Auth::id())
                      ->first();

        if (!$venue) {
            return response()->json(['message' => 'Venue tidak ditemukan / bukan milik Anda'], 403);
        }

        $items = Fnb_menu::where('venue_id', $venueId)->get();
        return response()->json($items);
    }

    // Tambah F&B baru
    public function store(Request $request)
    {
        $request->validate([
            'venue_id'      => 'required|exists:venues,id',
            'categories_id' => 'required|exists:fnb_categories,id',
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'description'   => 'nullable|string',
            'image'         => 'nullable|string'
        ]);

        // Cek apakah venue milik user yang login
        $venue = Venues::where('id', $request->venue_id)
                      ->where('user_id', Auth::id())
                      ->first();

        if (!$venue) {
            return response()->json(['message' => 'Venue tidak ditemukan / bukan milik Anda'], 403);
        }

        $item = Fnb_menu::create($request->all());

        return response()->json($item, 201);
    }

    // Update F&B
    public function update(Request $request, $id)
    {
        $item = Fnb_menu::find($id);

        if (!$item || $item->venue->user_id !== Auth::id()) {
            return response()->json(['message' => 'Item tidak ditemukan / bukan milik Anda'], 403);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|string',
            'categories_id' => 'required|exists:fnb_categories,id',
        ]);

        $item->update($request->all());

        return response()->json($item);
    }

    // Hapus F&B
    public function destroy($id)
    {
        $item = Fnb_menu::find($id);

        if (!$item || $item->venue->user_id !== Auth::id()) {
            return response()->json(['message' => 'Item tidak ditemukan / bukan milik Anda'], 403);
        }

        $item->delete();

        return response()->json(['message' => 'Item berhasil dihapus']);
    }
}
