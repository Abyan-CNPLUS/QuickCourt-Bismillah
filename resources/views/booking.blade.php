<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan</title>
    <style>
        .booking-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Segoe UI', sans-serif;
        }

        .title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .section-title {
            color: #3498db;
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .date-grid, .time-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .date-form {
            margin: 0;
        }

        .date-option {
            width: 100%;
            padding: 15px 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .date-option:hover {
            background: #f8f9fa;
            border-color: #3498db;
        }

        .day {
            display: block;
            font-weight: bold;
            color: #2c3e50;
        }

        .date {
            display: block;
            color: #7f8c8d;
        }

        .time-option {
            position: relative;
        }

        .time-label {
            display: block;
            padding: 12px 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .time-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .time-option input[type="radio"]:checked + .time-label {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .time-option input[type="radio"]:focus + .time-label {
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.3);
        }

        .book-button {
            display: block;
            width: 100%;
            padding: 15px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
        }

        .book-button:hover {
            background: #27ae60;
        }

        .book-button i {
            margin-right: 8px;
        }
    </style>
</head>
<body>

    <div class="booking-container">
        <h1 class="title">Pilih Jadwal Booking</h1>

        <!-- Tanggal -->
        <div class="dates-container">
            <h3 class="section-title">Pilih Tanggal</h3>
            <div class="date-grid">
                @foreach ($date as $dates)
                    <form action="" method="GET" class="date-form">
                        <input type="hidden" name="date" value="{{ $dates->id }}">
                        <button type="submit" class="date-option">
                            <span class="day">{{ \Carbon\Carbon::parse($dates->Tanggal)->isoFormat('dddd') }}</span>
                            <span class="date">{{ \Carbon\Carbon::parse($dates->Tanggal)->isoFormat('D MMM') }}</span>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

        <!-- Waktu -->
        <div class="times-container">
    @csrf

    <!-- Nama & Email -->
    <div style="margin-bottom: 20px;">
        <label>Nama:</label>
        <input type="text" name="customer_name" required style="width: 100%; padding: 10px;">
    </div>

    <div style="margin-bottom: 20px;">
        <label>Email:</label>
        <input type="email" name="customer_email" required style="width: 100%; padding: 10px;">
    </div>


    <!-- Tanggal (ambil dari request GET) -->
    <input type="hidden" name="booking_date" value="{{ request('date') ? \App\Models\dateplay::find(request('date'))->Tanggal : '' }}">

    <!-- Waktu tersedia -->
    <div class="time-grid">
    @foreach ($times as $time)
        <div class="time-option">
            <input type="radio" id="time-{{ $time->id }}" name="time_id" value="{{ $time->id }}" required>
            <label for="time-{{ $time->id }}" class="time-label">
                {{ $time->start_time }} - {{ $time->end_time }}
            </label>
        </div>
    @endforeach
</div>
    <!-- Submit -->
    <button type="submit" class="book-button">
        <i class="fas fa-calendar-check"></i> Booking Sekarang
    </button>
</form>
        </div>
    </div>

</body>
</html>
