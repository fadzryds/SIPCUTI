<nav class="navbar">

    <div class="navbar-container">

        {{-- Logo --}}
        <a href="{{ route('employee.dashboard') }}" class="logo">

            <div class="logo-icon">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SIPCUTI">
            </div>

            <div class="logo-text">

                <h3>SIPCUTI</h3>

                <span>Employee Portal</span>

            </div>

        </a>

        {{-- Desktop Menu --}}
        <ul class="nav-menu">

            <li>
                <a href="{{ route('employee.dashboard') }}"
                   class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('employee.leave.index') }}"
                   class="{{ request()->routeIs('employee.leave.*') ? 'active' : '' }}">
                    Riwayat
                </a>
            </li>

            <li>
                <a href="{{ route('employee.profile') }}"
                   class="{{ request()->routeIs('employee.profile') ? 'active' : '' }}">
                    Profile
                </a>
            </li>

        </ul>

        {{-- Right Menu --}}
        <div class="nav-right">

            {{-- Notification --}}
            <button class="notification-btn">

                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">

                    <path d="M18 8a6 6 0 10-12 0c0 7-3 8-3 8h18s-3-1-3-8"/>

                    <path d="M13.73 21a2 2 0 01-3.46 0"/>

                </svg>

                <span class="notification-dot"></span>

            </button>

            {{-- User --}}
            <div class="user-menu">

                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=F97316&color=fff"
                    alt="avatar">

                <div>

                    <strong>{{ Auth::user()->name }}</strong>

                    <small>Karyawan</small>

                </div>

            </div>

            {{-- Hamburger --}}
            <button id="hamburger" class="hamburger">

                <span></span>
                <span></span>
                <span></span>

            </button>

        </div>

    </div>

</nav>

{{-- Mobile Navigation --}}

<div id="mobileMenu" class="mobile-menu">

    <a href="{{ route('employee.dashboard') }}">
        Dashboard
    </a>

    <a href="{{ route('employee.leave.index') }}">
        Riwayat
    </a>
    
    <a href="{{ route('employee.profile') }}">
        Profile
    </a>

    <form method="POST" action="{{ route('logout') }}">

        @csrf

        <button type="submit">

            Logout

        </button>

    </form>

</div>