<header class="topbar">

    <div class="topbar-left">

        <div class="page-heading">

            <h2>@yield('title')</h2>

            <span>

                <i class="fa-solid fa-house"></i>

                Manager

                <i class="fa-solid fa-angle-right"></i>

                @yield('title')

            </span>

        </div>

    </div>

    <div class="topbar-right">

        {{-- Search --}}
        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                placeholder="Cari data...">

        </div>

        {{-- Notification --}}
        <button class="notification-btn">

            <i class="fa-regular fa-bell"></i>

            @isset($pending)
                @if($pending > 0)
                    <span>{{ $pending }}</span>
                @endif
            @endisset

        </button>

        {{-- Clock --}}
        <div class="clock-box">

            <i class="fa-regular fa-clock"></i>

            <span id="liveClock"></span>

        </div>

        {{-- Profile --}}
        <div class="profile-dropdown">

            <button
                class="profile-btn"
                id="profileBtn">

                <div class="avatar">

                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}

                </div>

                <div class="profile-info">

                    <strong>

                        {{ Auth::user()->name }}

                    </strong>

                    <small>

                        Manager

                    </small>

                </div>

                <i class="fa-solid fa-chevron-down"></i>

            </button>

            <div
                class="dropdown-menu"
                id="dropdownMenu">

                <a href="{{ route('manager.profile') }}">

                    <i class="fa-solid fa-user"></i>

                    Profile

                </a>

                <hr>

                <form
                    action="{{ route('logout') }}"
                    method="POST">

                    @csrf

                    <button type="submit">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        Logout

                    </button>

                </form>

            </div>

        </div>

    </div>

</header>