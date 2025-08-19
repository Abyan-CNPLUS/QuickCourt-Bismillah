<!DOCTYPE html>
<html lang="en">
    <head>
         <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <meta http-equiv="X-UA-Compatible" content="ie=edge">
          <link rel="stylesheet" href="{{asset('css/style.css')}}">
            <link rel="apple-touch-icon" sizes="100x100" href="../assets/img/apple-icon.png">
            <link rel="icon" type="image/png" href="{{asset('img/logo.png')}}">
                <title>Venue</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        </head>
        <body>
            @include('layouts.navbar')
            @include('layouts.app')
            <div class="kepala">
            <div class="kiri-kepala" style="margin-left: 20px">

                <div>
            <h1 style="color: white; text-align: center; height:100px; margin-top:100px">Pesan Lapangan Lebih Cepat, Main Lebih Puas</h1>
            </div>
            <div class="button-wrapper" style="margin-top: -100px; text-align: center; width:50%">
                <button class="button-89" role="button">Daftarkan Venue</button>
            </div>
        </div> {{-- <button class="button-19" role="button">Button 19</button> --}}
    </div>
    @include('layouts.filter2')
    <div id="venue-container" class="venue-container">
        @php
        // Pakai data yang ada: $menus atau $fnbMenus
        $list = isset($venues) ? $venues : ($venues ?? collect());
    @endphp

    @forelse($list as $venue)
        <div class="menu-card">
            <div class="venue-image">
                <img
                    src="{{ $venue->image ? asset('storage/' . $venue->image) : asset('images/default.jpg') }}"
                    alt="{{ $venue->name }}"
                >
            </div>
            <div class="venue-content">
                <h3 class="menu-title">{{ $venue->name }}</h3>
                <p class="menu-price">
                    Rp{{ is_numeric($venue->price) ? number_format($venue->price, 2, ',', '.') : $venue->price }}
                </p>
                <p class="menu-desc">{{ $venue->description }}</p>
                <a href="#" class="order-btn">Pesan Sekarang</a>
            </div>
        </div>
    @empty
        <p>Tidak ada menu.</p>
    @endforelse
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    loadVenues(); // pertama kali load semua

    // ketika filter berubah
    $('#city_id, #category_id').on('change', function () {
        loadVenues();
    });

    function loadVenues() {
        let cityId = $('#city_id').val();
        let categoryId = $('#category_id').val();

        $.ajax({
            url: "{{ route('venues.filter') }}",
            method: "GET",
            data: {
                city_id: cityId,
                category_id: categoryId
            },
            success: function (data) {
                let html = '';

                if (!Array.isArray(data) || data.length === 0) {
                    html = `<p>Tidak ada venue sesuai filter.</p>`;
                } else {
                    data.forEach(function (venue) {
                        // ambil gambar pertama kalau ada
                        let img = venue.images.length > 0
                            ? '/storage/' + venue.images[0].image_url
                            : '/images/default.jpg';

                        html += `
                            <div class="venue-card">
                                <div class="venue-image">
                                    <img src="${img}" alt="${venue.name}" width="150">
                                </div>
                                <div class="venue-content">
                                    <h3>${venue.name}</h3>
                                    <p>${venue.city ? venue.city.name : '-'}</p>
                                    <p>${venue.category ? venue.category.name : '-'}</p>
                                    <p class="menu-price">Rp ${venue.price}</p>
                                    <a href="/wow/${venue.id}" class="order-btn">Lihat Detail</a>
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
});
</script>
</body>
</html>
