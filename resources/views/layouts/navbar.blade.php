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

      <div class="navbar-right">
    @auth
        <div onclick="toggleUserDropdown()">
            <img src="{{asset('img/profile.avif')}}" alt="Profile" class="avatar">
            <div class="dropdown-user" id="dropdownUser">
                <div class="profile-link">
                    <a href="#"><i class="fa-regular fa-user" style="margin-right: 10px"></i>Profil</a>
                </div>

                @if(auth()->user()->role === 'admin')
                    <div class="profile-link">
                        <a href="{{route('admin.dashboard')}}">
                            <i class="fa-solid fa-lock" style="margin-right: 10px"></i>Admin Panel
                        </a>
                    </div>
                @endif

                <form method="POST" action="{{ route('logout')}}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket" style="margin-right: 10px"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="login-link">
            <i class="fa-solid fa-right-to-bracket" style="margin-right: 5px"></i>Login
        </a>
    @endauth
</div>
        </nav>
    </div>

    <script>
       function toggleUserDropdown() {
    const dropdown = document.getElementById('dropdownUser');
    dropdown.classList.toggle('show');
}

// Close the dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.matches('.avatar')) {
        const dropdowns = document.getElementsByClassName("dropdown-user");
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
 </script>

