<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="apple-touch-icon" sizes="100x100" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{asset('img/logo.png')}}">
    <title>Pesan makanan terbaik anda</title>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
        .kepala_fnb {
            position: relative;
            width: 100%;
            height: 400px;
        }
        .kepala_fnb .swiper {
            width: 100%;
            height: 100%;
        }
        .kepala_fnb .swiper-slide img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .kepala_fnb .left-kepala {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(0,0,0,0.4);
        }
        .kepala_fnb .left-kepala h1 {
            color: #fff;
            text-align: center;
            font-size: 36px;
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    @include('layouts.app')

    {{-- Hero dengan carousel --}}
    <div class="kepala_fnb">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('img/Nasiuduk.jpg') }}" alt="Banner 1">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('img/Promotion2.png') }}" alt="Banner 2">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('img/promosale.jpg') }}" alt="Banner 3">
                </div>
            </div>
            <!-- tombol navigasi -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="kategori">
        <label for="category_id"></label>
        <select name="category_id" id="category_id">
            <option value="">Pilih Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="menu-header">
        <h1>Menu Spesial</h1>
        <p>Nikmati berbagai menu lezat dengan harga terjangkau</p>
    </div>

    <div class="menu-container" id="menu-container">
        @php
            $list = isset($menus) ? $menus : ($fnbMenus ?? collect());
        @endphp

        @forelse($list as $menu)
            <div class="menu-card">
                <div class="venue-image">
                    <img
                        src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('images/default.jpg') }}"
                        alt="{{ $menu->name }}"
                    >
                </div>
                <div class="venue-content">
                    <h3 class="menu-title">{{ $menu->name }}</h3>
                    <p class="menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    <p class="menu-desc">{{ $menu->description }}</p>
                    <a href="#" class="order-btn">Pesan Sekarang</a>
                </div>
            </div>
        @empty
            <p>Tidak ada menu.</p>
        @endforelse
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // init carousel
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

        // filter kategori
        $(function () {
            $("#category_id").on("change", function () {
                const categoryId = $(this).val();
                const url = categoryId ? `/menu/category/${categoryId}` : `/menu/all`;

                $.ajax({
                    url,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        let html = '';
                        if (!Array.isArray(data) || data.length === 0) {
                            html = `<p>Tidak ada menu${categoryId ? ' di kategori ini' : ''}.</p>`;
                        } else {
                            data.forEach(function (menu) {
                                const img = menu.image
                                    ? `/storage/${menu.image}`
                                    : `/images/default.jpg`;

                                let price = menu.price;
                                if (!isNaN(price)) {
                                    price = Number(price).toLocaleString('id-ID', {
                                        minimumFractionDigits: 0
                                    });
                                }

                                html += `
                                    <div class="menu-card">
                                        <div class="venue-image">
                                            <img src="${img}" alt="${menu.name}">
                                        </div>
                                        <div class="venue-content">
                                            <h3 class="menu-title">${menu.name}</h3>
                                            <p class="menu-price">Rp ${price}</p>
                                            <p class="menu-desc">${menu.description ? menu.description : ''}</p>
                                            <a href="#" class="order-btn">Pesan Sekarang</a>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        $("#menu-container").html(html);
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        $("#menu-container").html(`<p style="color:red;">Terjadi kesalahan memuat menu.</p>`);
                    }
                });
            });
        });
    </script>
</body>
</html>
