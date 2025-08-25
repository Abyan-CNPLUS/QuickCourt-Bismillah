<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="apple-touch-icon" sizes="100x100" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Venue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
        .kepala {
            position: relative;
            width: 100%;
            height: 400px;
        }
        .kepala .swiper {
            width: 100%;
            height: 100%;
        }
        .kepala .swiper-slide img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        /* overlay text */
        .kepala .kiri-kepala {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }
        .kepala .kiri-kepala h1 {
            color: #fff;
            text-align: center;
            font-size: 32px;
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    @include('layouts.app')

    {{-- Hero dengan Carousel --}}
    <div class="kepala">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('img/Basket.jpg') }}" alt="Banner 1">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('img/image.png') }}" alt="Banner 2">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('img/banner3.jpg') }}" alt="Banner 3">
                </div>
            </div>

            <!-- Navigasi -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="kiri-kepala">
            <div class="button-wrapper" style="margin-top: 20px; text-align: center; width:50%">
                {{-- tombol CTA kalau perlu --}}
            </div>
        </div>
    </div>

    {{-- Filter --}}
    @include('layouts.filter2')

    {{-- Venue Container --}}
    <div id="venue-container" class="venue-container">
        @forelse($venues as $venue)
            @php
                $image = $venue->images->first()
                    ? asset('storage/' . $venue->images->first()->image_url)
                    : asset('images/default.jpg');
            @endphp
            <div class="venue-card">
                <div class="venue-image">
                    <img src="{{ $image }}" alt="{{ $venue->name }}" width="150">
                </div>
                <div class="venue-content">
                    <h3>{{ $venue->name }}</h3>
                    <p>{{ $venue->city->name ?? '-' }}</p>
                    <p>{{ $venue->category->name ?? '-' }}</p>
                    <p class="menu-price">Rp {{ number_format($venue->price, 0, ',', '.') }}</p>
                    <a href="{{ route('lapangan.show', $venue->id) }}" class="order-btn">Lihat Detail</a>
                </div>
            </div>
        @empty
            <p>Tidak ada venue.</p>
        @endforelse
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Swiper init
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        // Format Rupiah & Filter
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function applyFilters() {
            let cityId = $('#city_id').val();
            let categoryId = $('#category_id').val();

            $.ajax({
                url: "{{ route('venues.filter') }}",
                method: "GET",
                data: { city_id: cityId, category_id: categoryId },
                success: function (data) {
                    let html = '';
                    if (!Array.isArray(data) || data.length === 0) {
                        html = `<p>Tidak ada venue</p>`;
                    } else {
                        data.forEach(function (venue) {
                            let img = venue.images.length > 0
                                ? '/storage/' + venue.images[0].image_url
                                : '/images/default.jpg';

                            let showUrl = "{{ route('lapangan.show', ':id') }}".replace(':id', venue.id);

                            html += `
                                <div class="venue-card">
                                    <div class="venue-image">
                                        <img src="${img}" alt="${venue.name}" width="150">
                                    </div>
                                    <div class="venue-content">
                                        <h3>${venue.name}</h3>
                                        <p>${venue.city ? venue.city.name : '-'}</p>
                                        <p>${venue.category ? venue.category.name : '-'}</p>
                                        <p class="menu-price">${formatRupiah(venue.price)}</p>
                                        <a href="${showUrl}" class="order-btn">Lihat Detail</a>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    $('#venue-container').html(html);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    $('#venue-container').html(`<p style="color:red;">Terjadi kesalahan memuat venue.</p>`);
                }
            });
        }
    </script>
</body>
</html>
