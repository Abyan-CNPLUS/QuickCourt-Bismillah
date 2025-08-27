<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran Online</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f5f7fa; padding: 40px; }
    .card { border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .amount { font-size: 1.5rem; font-weight: bold; color: #198754; }
    #payment-status { font-weight: 600; }
</style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="card p-4 col-md-6">
        <h4 class="mb-3">Pembayaran Booking #{{ $booking->id }}</h4>

        <div class="mb-3">
            <p class="mb-1 text-muted">Total yang harus dibayar:</p>
            <p class="amount">
                Rp {{ number_format($booking->payment->amount ?? 0, 0, ',', '.') }}
            </p>
        </div>

        <div class="d-grid mb-3">
            <button id="pay-button" type="button" class="btn btn-success btn-lg" {{ $booking->payment ? '' : 'disabled' }}>
                Bayar Sekarang
            </button>
        </div>

        <div class="alert alert-light border" role="alert">
            Status:
            <span id="payment-status" class="{{ $booking->payment ? ($booking->payment->status === 'approved' ? 'text-success' : 'text-danger') : 'text-muted' }}">
                {{ $booking->payment->status ?? 'belum dibayar' }}
            </span>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
@if($booking->payment)
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
const payButton = document.getElementById('pay-button');
const paymentStatusEl = document.getElementById('payment-status');

payButton.addEventListener('click', function() {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            paymentStatusEl.innerText = 'Berhasil ✅';
            paymentStatusEl.className = 'text-success';
            payButton.disabled = true;
        },
        onPending: function(result) {
            paymentStatusEl.innerText = 'Pending ⏳';
            paymentStatusEl.className = 'text-warning';
        },
        onError: function(result) {
            paymentStatusEl.innerText = 'Gagal ❌';
            paymentStatusEl.className = 'text-danger';
        },
        onClose: function() {
            paymentStatusEl.innerText = 'Dibatalkan 🚫';
            paymentStatusEl.className = 'text-muted';
        }
    });
});

// Polling status setiap 5 detik
setInterval(() => {
    fetch(`/payments/status/{{ $booking->payment->id ?? 0 }}`)
        .then(res => res.json())
        .then(data => {
            let status = data.status || 'belum dibayar';
            paymentStatusEl.innerText = status.charAt(0).toUpperCase() + status.slice(1);
            paymentStatusEl.className =
                status === 'approved' ? 'text-success' :
                status === 'pending' ? 'text-warning' :
                status === 'rejected' ? 'text-danger' : 'text-muted';
            if(status === 'approved') payButton.disabled = true;
        });
}, 5000);
</script>
@endif
</body>
</html>
