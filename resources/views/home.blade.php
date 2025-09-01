<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
     <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/venues.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="apple-touch-icon" sizes="100x100" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{asset('img/logo.png')}}">
    <title>
        Home
    </title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @include('layouts.navbar')
    @include('layouts.app')
    {{-- Home.blade.php --}}
<div class="home-container">
    <div class="header">
        <div class="left-header ml-5">
            <h1 class="text-white text-3xl md:text-5xl font-bold mb-4">Pesan Lapangan Lebih Cepat, Main Lebih Puas</h1>
            <p class="text-white mb-6 text-sm md:text-base">Nikmati kemudahan booking lapangan mini soccer favorit, pesan makanan langsung dari tempat dudukmu, dan rasakan pengalaman bermain yang tak terlupakan bersama teman-teman!</p>
            <div class="button-wrapper">
                <button class="button-89" role="button">Booking Now</button>
            </div>
        </div>

        <div class="right-header">
            <img src="{{asset('img/full-shot-man-playing-with-ball.png')}}" alt="Header Image" class="header-image">
        </div>
    </div>

    {{-- Venue Terbaru --}}
    <div class="venue-section py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-2xl font-bold mb-6">Venue Terbaru</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($venues as $venue)
                    @php
                        $image = $venue->images->first()
                            ? asset('storage/' . $venue->images->first()->image_url)
                            : asset('images/default.jpg');
                    @endphp

                    <div class="venue-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <img src="{{ $image }}" alt="{{ $venue->name }}" class="w-full h-40 md:h-48 object-cover">
                        <div class="p-4 text-center">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $venue->name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $venue->city->name ?? '-' }}</p>
                            <p class="text-indigo-600 font-bold mt-2">Rp {{ number_format($venue->price, 0, ',', '.') }}</p>
                            <a href="{{ route('lapangan.show', $venue->id) }}" class="inline-block mt-3 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500 transition">Lihat Detail</a>
                        </div>
                    </div>

                @empty
                    <p class="col-span-4 text-center text-gray-500">Tidak ada venue tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Carousel Section -->
<div class="mt-16 px-6 md:px-12 lg:px-20">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Promo & Partner</h2>

     <div class="swiper mySwiper">
             <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <img src="{{ asset('img/promo1.png') }}" class="w-full h-48 object-cover rounded-lg shadow-md" alt="Promo 1">
            </div>
            <!-- Slide 2 -->
            <div class="swiper-slide">
                <img src="{{ asset('img/promo2.png') }}" class="w-full h-48 object-cover rounded-lg shadow-md" alt="Promo 2">
            </div>
            <!-- Slide 3 -->
            <div class="swiper-slide">
                <img src="{{ asset('img/promo3.png') }}" class="w-full h-48 object-cover rounded-lg shadow-md" alt="Promo 3">
            </div>
        </div>
            <!-- Navigasi -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
</div>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 mt-10">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="text-center md:text-center">
                <h3 class="text-xl font-bold text-white mb-2">QuickCourt</h3>
                <p style="color: #fff">Platform booking lapangan olahraga cepat & mudah.</p>
            </div>

            <div class="text-center md:text-center">
                <h3 class="text-xl font-bold text-white mb-2">Kontak</h3>
                <p style="color: #fff">📧 Quickcourt@email.com</p>
            </div>

            <div class="text-center md:text-center">
                <h3 class="text-xl font-bold text-white mb-2">Ikuti Kami</h3>
                <div class="flex justify-center md:justify-start gap-4 mt-2">
                    <a href="https://www.instagram.com/quick.court25/?next=%2F" class="hover:text-white transition-colors">🌐 IG: Quick Court</a>
                </div>
                <div class="flex justify-center md:justify-start gap-4 mt-2">
                <a href="#" class="hover:text-white transition-colors">🎵 TikTok</a>
                </div>
                {{-- Play Store & App Store --}}
 <div class="mt-4 flex justify-center md:justify-start gap-4">
                    <a href="https://play.google.com/store/apps/details?id=com.quickcourt.app" target="_blank">
                        <img src="https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png" alt="Download di Play Store" class="h-12 md:h-15">
                    </a>
                    <a href="#">
                        <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="Download di App Store" class="h-12 md:h-10">
                    </a>
                </div>
            </div>

        </div>

        <div class="bg-gray-800 text-center py-4 text-sm mt-6">
            © {{ date('Y') }} QuickCourt. All rights reserved.
        </div>
    </footer>
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
    </script>
</div>
</body>
</html>
