<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Midtrans\Snap;
use Midtrans\Config;

class PaymentController extends Controller
{
    // === PAYMENT OPTION ===
    public function option(Booking $booking)
    {
        // Pastikan booking masih pending
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.create.withVenue', $booking->venue_id)
                ->with('error', 'Booking ini sudah diproses.');
        }

        // Pastikan payment record ada
        if (!$booking->payment) {
            $booking->payment()->create([
                'amount' => $booking->total_price,
                'payment_method' => 'gateway', // default
                'status' => 'pending',
            ]);
            $booking->load('payment');
        }

        return view('payment.option', compact('booking'));
    }

    public function optionProcess(Request $request, Booking $booking)
    {
        $request->validate([
            'method' => 'required|in:gateway,manual',
        ]);

        // Update metode pembayaran
        $booking->payment->update([
            'payment_method' => $request->method,
        ]);

        if ($request->method === 'gateway') {
            return redirect()->route('payments.gateway', $booking->id);
        } else {
            return redirect()->route('payments.manual', $booking->id);
        }
    }

    // === GATEWAY PAYMENT (Midtrans) ===
    public function gateway($bookingId)
    {
        $booking = Booking::with('payment', 'user')->findOrFail($bookingId);

        // Buat payment jika belum ada
        if (!$booking->payment) {
            $booking->payment()->create([
                'amount' => $booking->total_price,
                'payment_method' => 'gateway',
                'status' => 'pending',
            ]);
            $booking->load('payment');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => 'BOOKING-' . $booking->id,
                'gross_amount' => $booking->payment->amount,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->contact_number,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment.midtrans', compact('booking', 'snapToken'));
    }

    public function gatewayCallback(Request $request)
    {
        $bookingId = str_replace('BOOKING-', '', $request->order_id);
        $payment = Payment::where('booking_id', $bookingId)->first();

        if (!$payment) return response()->json(['message' => 'Payment not found'], 404);

        switch ($request->transaction_status) {
            case 'capture':
            case 'settlement':
                $payment->update([
                    'status' => 'success',
                    'payment_date' => now(),
                ]);
                $payment->booking->update(['status' => 'confirmed']);
                break;
            case 'pending':
                $payment->update(['status' => 'pending']);
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
                $payment->update(['status' => 'failed']);
                $payment->booking->update(['status' => 'cancelled']);
                break;
        }

        return response()->json(['success' => true]);
    }

    // === MANUAL PAYMENT (Hybrid) ===
    public function manual($bookingId)
    {
        $booking = Booking::with('payment', 'user')->findOrFail($bookingId);

        // Buat payment jika belum ada
        if (!$booking->payment) {
            $booking->payment()->create([
                'amount' => $booking->total_price,
                'payment_method' => 'manual',
                'status' => 'pending',
            ]);
            $booking->load('payment');
        }

        return view('payment.hybrid', compact('booking'));
    }

    public function manualUpload(Request $request, $bookingId)
    {
        $booking = Booking::with('payment')->findOrFail($bookingId);

        $request->validate([
            'proof' => 'required|image|max:2048',
        ]);

        $path = $request->file('proof')->store('payments', 'public');

        $booking->payment->update([
            'status' => 'waiting_verification',
            'proof' => $path,
        ]);

        return redirect()->route('bookings.create.withVenue', $booking->venue_id)
            ->with('success', 'Bukti transfer berhasil diupload, tunggu verifikasi admin.');
    }

    // === Optional: status polling via AJAX ===
    public function status($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        return response()->json([
            'status' => $payment->status,
            'payment_date' => $payment->payment_date,
        ]);
    }
}
