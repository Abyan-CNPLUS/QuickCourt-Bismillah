@foreach($menus as $menu)
<div class="menu-card">
    <!-- ... -->
    <p>Kategori: {{ $menu->categoryMenu->name_category }}</p>
    <!-- atau jika menggunakan category() -->
    <p>Kategori: {{ $menu->category->name_category }}</p>
</div>
@endforeach
