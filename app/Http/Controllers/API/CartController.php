<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use App\Models\FnbCart;
use App\Models\Fnb_order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FnbOrderItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //
    public function index()
    {
        $cart = FnbCart::with('menu')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => optional($item->menu)->name ?? 'Unknown',
                    'price' => optional($item->menu)->price ?? 0,
                    'quantity' => $item->quantity,
                    'total_price' => $item->menu->price * $item->quantity,
                    'image_url' => optional($item->menu)->image_url ?? null,
                ];
            });

        $totalCartPrice = $cart->sum('total_price');

        return response()->json([
            'items' => $cart,
            'total' => $totalCartPrice,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'fnb_menu_id' => 'required|exists:fnb_menu,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = FnbCart::where('user_id', Auth::id())
            ->where('fnb_menu_id', $request->fnb_menu_id)
            ->first();

        if ($cartItem) {

            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {

            FnbCart::create([
                'user_id' => Auth::id(),
                'fnb_menu_id' => $request->fnb_menu_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
        ]);
    }

    public function destroy($id)
    {
        $item = \App\Models\FnbCart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Item removed from cart']);
    }


    public function checkout(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);


        $booking = Booking::where('id', $request->booking_id)->where('user_id', Auth::id())->first();
        if (!$booking) return response()->json(['error' => 'Invalid booking'], 403);

        $cartItems = FnbCart::with('menu')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        DB::beginTransaction();

        try {
            $order = Fnb_order::create([
                'booking_id' => $request->booking_id,
                'user_id' => Auth::id(),
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                FnbOrderItem::create([
                    'fnb_order_id' => $order->id,
                    'fnb_menu_id' => $item->fnb_menu_id,
                    'quantity' => $item->quantity,
                    'price' => $item->menu->price,
                ]);
            }

            FnbCart::where('user_id', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Checkout berhasil',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = FnbCart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated',
        ]);
    }


}
