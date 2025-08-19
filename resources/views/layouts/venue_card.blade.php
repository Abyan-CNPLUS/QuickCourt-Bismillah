<style>
  /* CARD CONTAINER */
.card-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
  margin-top: 150px;
}

/* CARD STYLING */
.card {
  width: 300px;
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  overflow: hidden;
  position: relative;
  transition: .3s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: linear-gradient(to right, #a90329 0%, #c44848 44%, #aa2238 100%);
  color: #fff;
  padding: 5px 10px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  border-radius: 10px;
  z-index: 10;
}

.venue-img {
  height: 180px;
  overflow: hidden;
}

.venue-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .5s;
}

.card:hover .venue-img img { transform: scale(1.05); }

.info {
    padding: 20px;
    border: 1px solid #F4F4F5;
}

.cat {
  font-size: 11px; text-transform: uppercase; color: #71717A; margin-bottom: 5px; font-weight: 600;
}

.title { font-size: 18px; font-weight: 700; margin-bottom: 10px; color: #18181B; }

.desc { font-size: 13px; color: #52525B; margin-bottom: 12px; line-height: 1.4; }

.feats { display: flex; gap: 6px; margin-bottom: 15px; flex-wrap: wrap; }

.feat {
  font-size: 10px; background: #F4F4F5; color: #71717A; padding: 3px 8px; border-radius: 10px; font-weight: 500;
}

.bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.price { display: flex; flex-direction: column; }

.old {
    font-size: 13px;
    text-decoration: line-through;
    color: #A1A1AA;
}

.new {
    font-size: 20px;
    font-weight: 700;
    color: #18181B;
}

.btn {
  background: linear-gradient(45deg, #18181B, #27272A);
  color: #fff; border: none;
  border-radius: 10px;
  padding: 8px 15px; font-size: 13px;
  font-weight: 600; cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  width: 100%;
  position: relative; transition: 0.3s;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  justify-content: center
}

.btn:hover { background: linear-gradient(45deg, #27272A, #3F3F46); transform: translateY(-2px); }

.icon { transition: transform 0.3s; }

.btn:hover .icon { transform: rotate(-10deg) scale(1.1); }

.meta {
  display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F4F4F5;
  padding-top: 12px;
}

.rating { display: flex; align-items: center; gap: 2px; }

.rcount { margin-left: 6px; font-size: 11px; color: #71717A; }

.stock { font-size: 11px; font-weight: 600; color: #22C55E; }

/* RESPONSIVE */
@media(max-width: 500px) {
  .venue-filter-options { flex-direction: column; gap: 10px; }
  .card { width: 90%; }
}
</style>

<div class="card-container">
    <div class="card">

      <div class="tilt">
        <div class="venue-img">
          <img src="{{asset('img/rajawali.jpg')}}" alt="Venue Image">
        </div>
      </div>
      <div class="info">
        <div class="cat">Venue</div>
        <h2 class="title">Rajawali Mini Soccer</h2>
        {{-- <p class="desc">Intel Core i9, 32GB RAM, 1TB SSD, sleek lightweight design.</p> --}}
        <div class="feats">
          <span class="feat">Mini Soccer</span>
          <span class="feat">Outdoor</span>
          {{-- <span class="feat">Thunderbolt 4</span> --}}
        </div>
        <div class="bottom">
          <div class="price">
            {{-- <span class="old">$2,499</span> --}}
            <span class="new">Rp100.000/hour</span>
          </div>
          {{-- <button class="btn">
            <span>Add to Cart</span>
            <i class="fa-solid fa-cart-shopping icon"></i>
          </button> --}}
        </div>
        <button class="btn">
            <span><a href="{{ route('booking.index') }}">Booking Now</a></span>
            <i class="fa-solid fa-cart-shopping icon"></i>
          </button>
      </div>
    </div>
    <!-- Duplikat card kalau mau -->
  </div>

