<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - {{ $venue->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">

    {{-- Header Venue --}}
    <div class="card shadow mb-4">
        <img src="{{ $venue->image ? asset('storage/' . $venue->image) : 'https://via.placeholder.com/1200x400' }}"
             class="card-img-top"
             alt="{{ $venue->name }}">
        <div class="card-body">
            <h3 class="card-title mb-0">{{ $venue->name }}</h3>
            <p class="text-muted">{{ $venue->city->name ?? '-' }}</p>
        </div>
    </div>

    {{-- Pilih Tanggal --}}
    <h5 class="mb-2">Pilih Tanggal</h5>
    <div class="d-flex overflow-auto mb-4">
        @foreach ($dates as $date)
            <button type="button"
                    class="btn btn-outline-primary me-2 {{ $loop->first ? 'active' : '' }}"
                    data-date="{{ $date->format('Y-m-d') }}">
                {{ $date->translatedFormat('D, d M') }}
            </button>
        @endforeach
    </div>

    {{-- Pilih Jam --}}
    <h5 class="mb-2">Pilih Jam</h5>
    <div class="row g-2 mb-4">
        @foreach ($times as $time)
            <div class="col-3">
                <button type="button"
                        class="btn w-100 {{ in_array($time, $bookedTimes) ? 'btn-secondary disabled' : 'btn-outline-success' }}"
                        data-time="{{ $time }}"
                        {{ in_array($time, $bookedTimes) ? 'disabled' : '' }}>
                    {{ $time }}
                </button>
            </div>
        @endforeach
    </div>

   <form action="{{ route('bookings.store') }}" method="POST">
    @csrf
    <input type="hidden" name="venue_id" value="{{ $venue->id }}">
    <input type="hidden" name="booking_date" id="input-date" value="{{ $dates->first()->format('Y-m-d') }}">
    <input type="hidden" name="start_time" id="input-time">
    <input type="hidden" name="end_time" id="input-end-time">
    <input type="hidden" name="total_price" id="input-total-price">

    <div class="card p-3">
        <h5>Ringkasan Pemesanan</h5>
        <p><strong>Tanggal:</strong> <span id="summary-date">{{ $dates->first()->translatedFormat('D, d M') }}</span></p>
        <p><strong>Jam:</strong> <span id="summary-time">-</span></p>
        <p><strong>Harga:</strong> <span id="summary-price" data-price="{{ $venue->price }}">Rp {{ number_format($venue->price,0,',','.') }}</span></p>

        <div class="mb-3">
            <label>Nomor Kontak</label>
            <input type="text" name="contact_number" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Booking Sekarang</button>
    </div>
</form>

<script>
const price = document.getElementById('summary-price').dataset.price;

// Pilih tanggal
document.querySelectorAll('[data-date]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('[data-date]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('summary-date').textContent = btn.textContent;
        document.getElementById('input-date').value = btn.dataset.date;
    });
});

// Pilih jam
document.querySelectorAll('[data-time]').forEach(btn => {
    if (!btn.classList.contains('disabled')) {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-time]').forEach(b => b.classList.remove('btn-success'));
            btn.classList.add('btn-success');

            let startTime = btn.dataset.time;
            let endHour = parseInt(startTime.split(':')[0]) + 1;
            let endTime = (endHour<10?'0':'') + endHour + ':00';

            document.getElementById('summary-time').textContent = startTime + ' - ' + endTime;
            document.getElementById('input-time').value = startTime;
            document.getElementById('input-end-time').value = endTime;
            document.getElementById('input-total-price').value = price;
        });
    }
});
</script>
</body>
</html>
