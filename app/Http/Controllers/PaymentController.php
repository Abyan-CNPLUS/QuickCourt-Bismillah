<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function show($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Ambil payment
        $payment = Payment::where('booking_id', $bookingId)->firstOrFail();

        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'BOOKING-' . $booking->id,
                'gross_amount' => $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->contact_number,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment', compact('booking', 'payment', 'snapToken'));
    }

    /**
     * Endpoint Callback dari Midtrans
     */
    public function callback(Request $request)
    {
        $notif = new Notification();

        $orderId = str_replace('BOOKING-', '', $notif->order_id);
        $payment = Payment::where('booking_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $transaction = $notif->transaction_status;
        $type        = $notif->payment_type;
        $fraud       = $notif->fraud_status ?? null;

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $payment->status = 'challenge';
                } else {
                    $payment->status = 'approved';
                    $payment->payment_date = now();
                    $payment->booking->status = 'confirmed';
                    $payment->booking->save();
                }
            }
        } elseif ($transaction == 'settlement') {
            $payment->status = 'approved';
            $payment->payment_date = now();
            $payment->booking->status = 'confirmed';
            $payment->booking->save();
        } elseif ($transaction == 'pending') {
            $payment->status = 'pending';
        } elseif ($transaction == 'deny' || $transaction == 'cancel' || $transaction == 'expire') {
            $payment->status = 'rejected';
        }

        $payment->save();

        return response()->json(['success' => true]);
    }

    /**
     * Endpoint untuk polling status via AJAX
     */
    public function status($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        return response()->json([
            'status' => $payment->status,
            'payment_date' => $payment->payment_date,
        ]);
    }

    /**
     * Optional: Update manual status dari Snap JS (fallback)
     */
    public function updateStatus(Request $request, $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->status = $request->status;
        if ($request->status == 'approved') {
            $payment->payment_date = now();
            $payment->booking->status = 'confirmed';
            $payment->booking->save();
        }
        $payment->save();

        return response()->json(['success' => true]);
    }
}
