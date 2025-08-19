<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="filter-venue">
            <div class="filter-venue-options">
                <div class="filter-option">
                    <div class="filter-icon">
                        <i class="fa-solid fa-location-dot fa-2x"></i>
                    </div>

                    <div class="filter-name" style="text-align: center">
                        {{-- <label for="location">Lokasi:</label> --}}

                        <select id="location">

                            <option value="all">Semua Lokasi</option>
                            @forelse ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>

                            @empty

                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="span"></div>
                <div class="filter-option">
                    <div class="filter-icon">
                        {{-- <i class="fa-solid fa-money-bill"></i> --}}
                        <i class="fas fa-futbol fa-2x"></i>
                    </div>

                    <div class="filter-name">
                        {{-- <label for="location">Venue:</label> --}}
                        <select id="location">
                            <option value="all">Semua Tipe</option>
                            @forelse ($categorys as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>)

                            @empty
                            Empty
                            @endforelse

                        </select>
                    </div>
                </div>
                <div class="span"></div>
                <div class="filter-option">
                    <div class="filter-icon">
                        <i class="fa-solid fa-money-bill fa-2x"></i>
                        {{-- <i class="fas fa-futbol"></i> --}}
                    </div>

                    <div class="filter-name">
                        {{-- <label for="location">Harga:</label> --}}
                        <select id="location">
                            <option value="all">Semua Harga</option>
                            <option value="indoor">Harga Terendah</option>
                            <option value="outdoor">Harga Tertinggi</option>
                        </select>
                    </div>
                </div>
                <div class="span"></div>
                <button class="filter-button" onclick="applyFilters()">Tampilkan</button>
            </div>


</div>
