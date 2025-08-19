<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Jam | Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #4895ef;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        .booking-card {
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
        }

        .booking-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .time-slot {
            transition: var(--transition);
            border-radius: var(--border-radius);
        }

        .time-slot.available:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .time-slot.selected {
            background-color: var(--primary-color);
            color: white;
        }

        .time-slot.booked {
            position: relative;
            overflow: hidden;
        }

        .booked-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--danger-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            opacity: 0.9;
        }

        .confirm-btn {
            transition: var(--transition);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .confirm-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(67, 97, 238, 0.2);
        }
    </style>
</head>
<body>

    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg animate-fade-in-out">
            {{ session('success') }}
        </div>
    @endif

    <script>
        // Update form saat tanggal berubah
        document.getElementById('booking-date').addEventListener('change', function() {
            window.location.href = "{{ route('booking.index') }}?booking_date=" + this.value;
        });

        // Highlight slot yang dipilih
        document.querySelectorAll('.time-slot.available').forEach(slot => {
            slot.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;

                document.querySelectorAll('.time-slot').forEach(s => {
                    s.classList.remove('selected');
                });

                this.classList.add('selected');
            });
        });
    </script>
</body>
</html>
