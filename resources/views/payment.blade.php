<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f5f7fa;
            padding: 40px;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #198754;
        }
        #payment-status {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card p-4 col-md-6">
            <h4 class="mb-3">Pembayaran</h4>

            <div class="mb-3">
                <p class="mb-1 text-muted">Total yang harus dibayar:</p>
                <p class="amount">Rp {{ number_format($payment->amount,0,',','.') }}</p>
            </div>

            <div class="d-grid mb-3">
                <button id="pay-button" type="button" class="btn btn-success btn-lg">
                    Bayar Sekarang
                </button>
            </div>

            <div class="alert alert-light border" role="alert">
                Status:
                <span id="payment-status" class="text-danger">
                    {{ ucfirst($payment->status ?? 'belum dibayar') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        const payButton = document.getElementById('pay-button');
        const paymentStatusEl = document.getElementById('payment-status');

        // Klik tombol bayar → buka Snap popup
        payButton.addEventListener('click', function() {
            snap.pay('{{ $snapToken }}', {
                onPending: function(result) {
                    paymentStatusEl.innerText = 'Pending ⏳';
                    paymentStatusEl.className = 'text-warning';
                },
                onError: function(result) {
                    paymentStatusEl.innerText = 'Gagal ❌';
                    paymentStatusEl.className = 'text-danger';
                },
                onClose: function() {
                    if (paymentStatusEl.innerText === 'Belum dibayar' ||
                        paymentStatusEl.innerText === '{{ $payment->status }}') {
                        paymentStatusEl.innerText = 'Dibatalkan 🚫';
                        paymentStatusEl.className = 'text-muted';
                    }
                }
            });
        });

        // Polling status pembayaran setiap 5 detik
        setInterval(() => {
            fetch(`/payment-status/{{ $payment->id }}`)
                .then(res => res.json())
                .then(data => {
                    paymentStatusEl.innerText = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    if (data.status === 'approved') {
                        paymentStatusEl.className = 'text-success';
                        payButton.disabled = true;
                    } else if (data.status === 'pending') {
                        paymentStatusEl.className = 'text-warning';
                    } else if (data.status === 'rejected') {
                        paymentStatusEl.className = 'text-danger';
                    }
                });
        }, 5000);
    </script>
</body>
</html>
