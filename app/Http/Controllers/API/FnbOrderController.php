<?php

namespace App\Http\Controllers\Api;

use App\Models\Fnb_order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Fnb_menu;
use App\Models\FnbOrderItem;
use Illuminate\Support\Facades\Auth;

class FnbOrderController extends Controller
{

    public function index()
    {
        $orders = Fnb_order::with(['items.menu'])->latest()->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'items' => 'required|array|min:1',
            'items.*.fnb_menu_id' => 'required|exists:fnb_menu,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $order = Fnb_order::create([
                'booking_id' => $request->booking_id,
                'user_id' => Auth::id(),
                'status' => 'pending',
            ]);

            foreach ($request->items as $item) {
                $menu = Fnb_menu::findOrFail($item['fnb_menu_id']);
                FnbOrderItem::create([
                    'fnb_order_id' => $order->id,
                    'fnb_menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'order_id' => $order->id], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Kirim notifikasi FCM ke staf
     */




    public function show($id)
    {
        $order = Fnb_order::with(['items.menu'])->findOrFail($id);
        return response()->json($order);
    }
}
