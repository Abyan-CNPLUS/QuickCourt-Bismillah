<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
  @extends('layouts.app')

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Carousel Gambar --}}
        <div class="md:col-span-2 space-y-4">
            @if ($venue->images && $venue->images->count() > 0)
                <div class="swiper rounded-lg overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach ($venue->images as $img)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $img->image_url) }}"
                                     alt="{{ $venue->name }}"
                                     class="w-full h-72 object-cover">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <img src="{{ asset('images/no-image.png') }}"
                     alt="No Image"
                     class="w-full h-72 object-cover rounded-lg">
            @endif

            {{-- Info --}}
            <div class="bg-white shadow rounded-lg p-4 space-y-2">
                <h1 class="text-3xl font-bold text-gray-800">{{ $venue->name }}</h1>
                <p class="text-gray-600"><strong>Alamat:</strong> {{ $venue->address }}</p>
                <p class="text-gray-600"><strong>Kota:</strong> {{ $venue->city->name ?? '-' }}</p>
                <p class="text-gray-600"><strong>Kategori:</strong> {{ $venue->category->name ?? '-' }}</p>
                <p class="text-gray-600"><strong>Kapasitas:</strong> {{ $venue->capacity }} orang</p>
                <p class="text-gray-600"><strong>Harga:</strong> Rp {{ number_format($venue->price, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Card Booking --}}
        <div class="md:col-span-1">
            <div class="sticky top-24 bg-white shadow-md rounded-lg p-6 border border-gray-200">
                <h2 class="text-xl font-semibold mb-4">Booking Sekarang</h2>
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="date" class="w-full border rounded px-3 py-2">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                        Booking Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Swiper JS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper('.swiper', {
        loop: true,
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });
</script>
@endsection
</body>
</html>
