 <div class="navbar">
        <nav>
            <div class="navigation">
                <ul>
                    <li class="{{ request()->is('home') ? 'active' : '' }}"><a href="/">Home</a></li>
                    <li class="{{ request()->is('venue*') ? 'active' : '' }}"><a href="/venue">Venue</a></li>
                    <li class="{{ request()->is('booking*') ? 'active' : '' }}"><a href="/book">Booking</a></li>
                    <li class="{{ request()->is('food-order*') ? 'active' : '' }}"><a href="/menu">Food Order</a></li>
                </ul>
            </div>
        </nav>
 </div>
