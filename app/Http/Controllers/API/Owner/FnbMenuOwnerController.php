<?php

namespace App\Http\Controllers\API\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Venues;
use App\Models\Fnb_menu;

class FnbMenuOwnerController extends Controller
{
    //
    public function index($venueId)
    {
        $venue = Venues::where('id', $venueId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $menus = $venue->fnbMenus()->get();

        return response()->json([
            'menus' => $menus,
        ]);
    }

    // Tambah menu baru ke venue
    public function store(Request $request, $venueId)
    {
        $venue = Venues::where('id', $venueId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'categories_id' => 'required|integer|exists:fnb_categories,id',

        ]);


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('fnb_images', 'public');
            $validated['image'] = $path;
        }

        $menu = new Fnb_menu($validated);
        $menu->venue_id = $venue->id;
        $menu->save();

        return response()->json($menu, 201);
    }



    public function update(Request $request, $menuId)
    {
        $menu = Fnb_menu::findOrFail($menuId);

        // Pastikan user punya akses ke menu ini (misal owner dari venue)
        if ($menu->venue->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $menu->update($validated);

        return response()->json($menu);
    }


    public function destroy($menuId)
    {
        $menu = Fnb_menu::findOrFail($menuId);

        if ($menu->venue->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully']);
    }
}
