<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    //
    public function createSnapToken(Request $request)
    {
        
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;


        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $request->total,
            ],
            'customer_details' => [
                'first_name' => $request->user()->name ?? 'Guest',
                'email' => $request->user()->email ?? 'guest@example.com',
            ],
        ];

        // Dapatkan Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json([
            'snap_token' => $snapToken,
        ]);
    }
}
