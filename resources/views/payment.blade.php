<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <!-- Tambahkan CSS jika diperlukan -->
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        #pay-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        #payment-status {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h3>Pembayaran</h3>
    <p>Total: Rp {{ number_format($payment->amount,0,',','.') }}</p>
    <button id="pay-button" type="button" class="btn btn-success">Bayar Sekarang</button>

    <!-- Status pembayaran -->
    <p>Status: <span id="payment-status">Belum dibayar</span></p>

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        const payButton = document.getElementById('pay-button');
        const paymentStatusEl = document.getElementById('payment-status');

        payButton.addEventListener('click', function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    paymentStatusEl.innerText = 'Approved';
                    payButton.disabled = true;

                    // Update status di backend
                    fetch(`/payment-update/{{ $payment->id }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ status: 'approved' })
                    });
                },
                onPending: function(result) {
                    paymentStatusEl.innerText = 'Pending';
                },
                onError: function(result) {
                    alert('Pembayaran gagal!');
                },
                onClose: function() {
                    paymentStatusEl.innerText = 'Dibatalkan';
                }
            });
        });
    </script>
</body>
</html>
