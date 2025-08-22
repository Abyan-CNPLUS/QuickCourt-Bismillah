<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function show($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Ambil payment
        $payment = Payment::where('booking_id', $bookingId)->first();

        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'BOOKING-'.$booking->id,
                'gross_amount' => $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->contact_number,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment', compact('booking','payment','snapToken'));
    }

    // endpoint untuk update status Midtrans
    public function callback(Request $request)
    {
        $notif = new \Midtrans\Notification();

        $orderId = str_replace('BOOKING-', '', $notif->order_id);
        $payment = Payment::where('booking_id', $orderId)->first();

        if($notif->transaction_status == 'capture' || $notif->transaction_status == 'settlement'){
            $payment->status = 'approved';
            $payment->payment_date = now();
            $payment->save();

            $payment->booking->status = 'confirmed';
            $payment->booking->save();
        } elseif($notif->transaction_status == 'deny' || $notif->transaction_status == 'cancel'){
            $payment->status = 'rejected';
            $payment->save();
        }

        return response()->json(['success'=>true]);
    }
    public function status($paymentId)
{
    $payment = Payment::findOrFail($paymentId);

    return response()->json([
        'status' => $payment->status,
        'payment_date' => $payment->payment_date,
    ]);
}

}
