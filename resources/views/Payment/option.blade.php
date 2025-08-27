<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h4 class="mb-0">Pilih Metode Pembayaran</h4>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted mb-4 text-center">
                        Booking #{{ $booking->id }} | Total:
                        <strong>Rp {{ number_format($booking->total, 0, ',', '.') }}</strong>
                    </p>

                    <form action="{{ route('payments.option.process', $booking->id) }}" method="POST">
                        @csrf

                        <div class="row g-4">

                            {{-- Gateway (Midtrans / Online Payment) --}}
                            <div class="col-md-6">
                                <label class="w-100">
                                    <input type="radio" name="method" value="gateway" class="d-none" required>
                                    <div class="card option-card text-center h-100 p-3">
                                        <div class="card-body">
                                            <img src="https://cdn-icons-png.flaticon.com/512/2331/2331970.png"
                                                 alt="Gateway" width="60" class="mb-3">
                                            <h6 class="fw-bold">Bayar Online</h6>
                                            <p class="small text-muted">Praktis & aman via Midtrans</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            {{-- Manual Transfer --}}
                            <div class="col-md-6">
                                <label class="w-100">
                                    <input type="radio" name="method" value="manual" class="d-none" required>
                                    <div class="card option-card text-center h-100 p-3">
                                        <div class="card-body">
                                            <img src="https://cdn-icons-png.flaticon.com/512/1041/1041873.png"
                                                 alt="Manual" width="60" class="mb-3">
                                            <h6 class="fw-bold">Transfer Manual</h6>
                                            <p class="small text-muted">Upload bukti pembayaran</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                Lanjutkan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- CSS biar card ke-highlight saat dipilih --}}
<style>
    .option-card {
        border: 2px solid #eaeaea;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    input[type="radio"]:checked + .option-card {
        border: 2px solid #0d6efd;
        background-color: #f8f9ff;
        box-shadow: 0 4px 10px rgba(13,110,253,0.2);
    }
</style>
</body>
</html>
