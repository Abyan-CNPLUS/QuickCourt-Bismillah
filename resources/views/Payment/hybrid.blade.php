<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran Manual</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f5f7fa; padding: 40px; }
    .card { border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .amount { font-size: 1.5rem; font-weight: bold; color: #198754; }
    .bank-info { background: #f0f8ff; border: 1px dashed #198754; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
</style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="card p-4 col-md-6">
        <h4 class="mb-3">Pembayaran Manual Booking #{{ $booking->id }}</h4>

        <div class="mb-3">
            <p class="mb-1 text-muted">Total yang harus dibayar:</p>
            <p class="amount">Rp {{ number_format($booking->payment->amount ?? 0,0,',','.') }}</p>
        </div>

        <div class="bank-info">
            <p class="mb-1"><strong>Transfer ke rekening:</strong></p>
            <ul class="mb-0">
                <li>BCA - 1234567890 a.n PT QuickCourt</li>
                <li>Mandiri - 0987654321 a.n PT QuickCourt</li>
            </ul>
        </div>

        <div class="alert alert-light border mb-3" role="alert">
            Status:
            <span class="fw-bold {{ $booking->payment ? ($booking->payment->status === 'waiting_verification' ? 'text-warning' : 'text-danger') : 'text-muted' }}">
                {{ $booking->payment->status ?? 'belum dibayar' }}
            </span>
        </div>

        <form action="{{ route('payments.manual.upload', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="proof" class="form-label">Upload Bukti Transfer</label>
                <input class="form-control" type="file" name="proof" id="proof" required>
                @error('proof')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg" {{ $booking->payment ? '' : 'disabled' }}>Kirim Bukti</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
