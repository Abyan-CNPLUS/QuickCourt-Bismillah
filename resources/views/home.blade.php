<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    <div class="home-container">
        <div class="header">
                <div class="left-header" style="margin-left: 20px">
                    <div>
                        <h1 style="color: white; text-align: left">Pesan Lapangan Lebih Cepat, Main Lebih Puas</h2>
                    </div>
                    <div>
                        <p style="color: white; text-align: left;">Nikmati kemudahan booking lapangan mini soccer favorit, pesan makanan langsung dari tempat dudukmu, dan rasakan pengalaman bermain yang tak terlupakan bersama teman-teman!</p>
                    </div>

                    <div class="button-wrapper">
                        <button class="button-89" role="button">Booking Now</button>
                    </div>
                </div>

                <div class="right-header">
                    <img src="{{asset('img/full-shot-man-playing-with-ball.png')}}" alt="Header Image" class="header-image">
                </div>
        </div>

        {{-- <div class="promotion">
            <img src="{{asset('img/Promotion.png')}}" alt="Promotion" class="promotion-image">
        </div> --}}
         @include('layouts.filter')
    </div>
      <br>
      <div class="menu-container" id="menu-container">
</div>
</body>
</html>
