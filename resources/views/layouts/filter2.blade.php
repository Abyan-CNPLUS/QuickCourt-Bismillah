<style>
    .venue-filter {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    text-align:center;

    /* margin-left: 00px; */

    width: 100%; /* agar pas isinya */
    z-index: 10;
    /* background-color: non; */
    /* border: 1px solid #0066cc; */
    border-radius: 10px;
}

.filter-name{
    text-align:center;
}

.venue-filter-options {
    display: flex;
    align-items: center;
    flex-direction: row;
    gap: 20px; /* biar rapi */
    background-color: #fff;
    border-radius: 10px;
    padding: 15px 30px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    border: 1px solid #0066cc;
}

.span {
  display: block;
  width: 1px;            /* ketebalan garis */
  height: 40px;          /* tinggi garis */
  background-color: #ccc; /* warna garis */
  margin: 0 10px;        /* jarak ke kiri kanan */
}


.filter-option {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 10px;;
}

.filter-option .filter-name {
  display: flex;
  flex-direction: column;
}

.filter-option label {
  font-weight: 500;
  margin-bottom: 5px;
}

.filter-icon {
  font-size: 18px;
  margin-bottom: 5px;
  color: #0066cc;
}

.venue-filter select {
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #0066cc;
  font-size: 14px;
}

.filter-button {
  padding: 10px 20px;
  border-radius: 8px;
  border: none;
  background-color: #0066cc;
  color: #fff;
  font-size: 14px;
  cursor: pointer;
}

.filter-venue button:hover {
  background-color: #004999;
}
</style>

<div class="venue-filter">
            <div class="venue-filter-options">
                <div class="filter-option">
                    <div class="filter-icon">
                        <i class="fa-solid fa-location-dot fa-2x"></i>
                    </div>

                    <div class="filter-name">
                        {{-- <label for="location">Lokasi:</label> --}}
                        <select id="city_id">
                    <option value="">Semua Kota</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
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
                        <select id="category_id">
                        <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="span"></div>

                <button class="filter-button" onclick="applyFilters()">Tampilkan</button>
            </div>
</div>
