<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $venue->name }} - Detail Venue</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/venues.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav2.css') }}">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body class="bg-gray-100">

{{-- Import helper di blade --}}
@php
use App\Helpers\FacilityHelper;
@endphp

{{-- Header --}}
@include("layouts.header")

<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Carousel + Info + Maps --}}
        <div class="md:col-span-2 space-y-4">

            {{-- Carousel --}}
            @if ($venue->images && $venue->images->count() > 0)
                @php
                    $primary = $venue->images->where('is_primary', 1)->first();
                    $others = $venue->images->where('is_primary', 0);
                @endphp

                <div class="swiper rounded-lg overflow-hidden">
                    <div class="swiper-wrapper">
                        {{-- Primary image dulu --}}
                        @if ($primary)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $primary->image_url) }}"
                                     alt="Primary {{ $venue->name }}"
                                     class="w-full h-72 object-cover" />
                            </div>
                        @endif

                        {{-- Gambar lainnya --}}
                        @foreach ($others as $img)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $img->image_url) }}"
                                     alt="{{ $venue->name }}"
                                     class="w-full h-72 object-cover" />
                            </div>
                        @endforeach
                    </div>

                    {{-- Navigasi & Pagination --}}
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <img src="{{ asset('images/no-image.png') }}"
                     alt="No Image"
                     class="w-full h-72 object-cover rounded-lg" />
            @endif

            {{-- Info Venue --}}
            <div class="bg-white shadow rounded-lg p-4 space-y-2">
                <h1 class="text-3xl font-bold text-gray-800">{{ $venue->name }}</h1>
                <p class="text-gray-600"><strong>Alamat:</strong> {{ $venue->address }}</p>
                <p class="text-gray-600"><strong>Kota:</strong> {{ optional($venue->city)->name ?? '-' }}</p>
                <p class="text-gray-600"><strong>Kategori:</strong> {{ optional($venue->category)->name ?? '-' }}</p>
                <p class="text-gray-600"><strong>Kapasitas:</strong> {{ $venue->capacity }} orang</p>
                <p class="text-gray-600"><strong>Harga:</strong> Rp {{ number_format($venue->price, 0, ',', '.') }}</p>
            </div>

            {{-- Maps --}}
            <div class="bg-white shadow rounded-lg p-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Lokasi Venue</h2>
                <div class="w-full h-64 rounded overflow-hidden">
                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode($venue->address) }}&output=embed"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>

        {{-- Fasilitas + Booking --}}
        <div class="bg-white shadow rounded-lg p-4" style="height: 50%; width:115%">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Fasilitas</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                @forelse ($venue->facilities as $facility)
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">{{ FacilityHelper::icon($facility->name) }}</span>
                        <span>{{ $facility->name }}</span>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada fasilitas tersedia.</p>
                @endforelse
            </div>

            {{-- Card Booking --}}
            <div style="margin-top: 25px">
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Booking</h2>
                    <p class="text-gray-600 mb-3">Pesan lapangan dengan mudah.</p>
                    <a href="{{ route('bookings.create.withVenue', $venue->id) }}"
                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg">
                        Booking Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Footer --}}
<footer class="bg-gray-900 text-gray-300 mt-10">
    <div class="container mx-auto px-6 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <h3 class="text-lg font-bold text-white mb-2">QuickCourt</h3>
            <p class="text-sm">Platform booking lapangan olahraga cepat & mudah.</p>
        </div>
        <div style="gap: 5px">
            <h3 class="text-lg font-bold text-white mb-2 text-center">Kontak</h3>
            <p class="text-sm">📍 {{ $venue->address }}</p>
            <p class="text-sm">📞 0812-3456-7890</p>
            <p class="text-sm">✉️ quickcourt@email.com</p>
        </div>
        <div>
            <h3 class="text-lg font-bold text-white mb-2 text-center">Ikuti Kami</h3>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-white">🌐 IG</a>
            </div>
            <div class="flex space-x-4">
            <a href="#" class="hover:text-white">💬 WA</a>
            </div>
            <div class="flex space-x-4">
            <a href="#" class="hover:text-white">🎵 TikTok</a>
            </div>
        </div>
    </div>
    <div class="bg-gray-800 text-center py-3 text-sm">
        © {{ date('Y') }} QuickCourt. All rights reserved.
    </div>
</footer>

{{-- Swiper JS --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper('.swiper', {
        loop: true,
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });
</script>
</body>
</html>
