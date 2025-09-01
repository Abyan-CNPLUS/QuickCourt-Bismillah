<div class="navbar">
    <nav>
        <div class="navigation">
            <ul>
                <li class="{{ request()->is('home') ? 'active' : '' }}"><a href="/">Home</a></li>
                <li class="{{ request()->is('venue*') ? 'active' : '' }}"><a href="/venue">Venue</a></li>
                <li class="{{ request()->is('booking*') ? 'active' : '' }}"><a href="">Booking</a></li>
                <li class="{{ request()->is('food-order*') ? 'active' : '' }}"><a href="/menu">Food Order</a></li>
            </ul>
        </div>

        <div class="navbar-right">
            @auth
                <div class="user-dropdown" onclick="toggleUserDropdown()">
                    <img src="{{ asset('img/profile.avif') }}" alt="Profile" class="avatar">

                    <div class="dropdown-user" id="dropdownUser">
                        <!-- Username -->
                        <div class="dropdown-header">
                            <strong>{{ auth()->user()->name }}</strong><br>
                            <small>{{ ucfirst(auth()->user()->role) }}</small>
                        </div>
                        <hr>

                        <!-- Profile -->
                        <div class="profile-link">
                            <a href="#">
                                <i class="fa-regular fa-user" style="margin-right: 10px"></i>Profile
                            </a>
                        </div>

                        <!-- Admin Panel -->
                        @if(auth()->user()->role === 'admin')
                            <div class="profile-link">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="fa-solid fa-lock" style="margin-right: 10px"></i>Admin Panel
                                </a>
                            </div>
                        @endif

                        <!-- Owner Panel -->
                        @if(auth()->user()->role === 'owner')
                            <div class="profile-link">
                                <a href="{{ route('owner.dashboard') }}">
                                    <i class="fa-solid fa-building" style="margin-right: 10px"></i>Owner Panel
                                </a>
                            </div>
                        @endif

                        <hr>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
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

