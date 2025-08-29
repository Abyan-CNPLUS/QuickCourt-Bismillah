<?php

namespace App\Http\Controllers\API\Owner;

use App\Models\Fnb_order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Venues;
use Illuminate\Support\Facades\Auth;

class FnbOrderOwnerController extends Controller
{
    //
    public function index($venueId)
    {
        $venue = Venues::where('id', $venueId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        $orders = $venue->fnbOrders()->with('items')->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    // Update status order
    public function updateStatus(Request $request, $orderId)
    {
        $order = Fnb_order::findOrFail($orderId);

        if ($order->venue->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:pending,completed,cancelled',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return response()->json($order);
    }
}
