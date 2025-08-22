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
</head>
<body>
    @include('layouts.navbar')
    @include('layouts.app')

    <div class="kepala_fnb">
        <div class="left-kepala" style="margin-left: 20px">
            <div>
                <h1 style="color: white; text-align: center; height:100px; margin-top:100px">Pesan Makanan</h1>
            </div>
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
        // Pakai data yang ada: $menus atau $fnbMenus
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
                <p class="menu-price">
                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                </p>
                <p class="menu-desc">{{ $menu->description }}</p>
                <a href="#" class="order-btn">Pesan Sekarang</a>
            </div>
        </div>
    @empty
        <p>Tidak ada menu.</p>
    @endforelse
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
                        // rakit URL gambar persis seperti di Blade
                        const img = menu.image
                            ? `/storage/${menu.image}`
                            : `/images/default.jpg`;

                        // format harga (jika angka)
                        let price = menu.price;
                        if (!isNaN(price)) {
                            price = Number(price).toLocaleString('id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }

                        html += `
                            <div class="menu-card">
                                <div class="venue-image">
                                    <img src="${img}" alt="${menu.name}">
                                </div>
                                <div class="venue-content">
                                    <h3 class="menu-title">${menu.name}</h3>
                                    <p class="menu-price">Rp${price}</p>
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
