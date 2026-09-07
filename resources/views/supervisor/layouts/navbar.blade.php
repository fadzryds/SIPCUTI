<header class="supervisor-navbar">

    {{-- LEFT --}}

    <div class="navbar-left">

        <button
            type="button"
            id="sidebar-toggle"
            class="sidebar-toggle"
            aria-label="Toggle Sidebar">

            ☰

        </button>

        <div class="navbar-title">

            <span class="navbar-section">
                Sistem Informasi Cuti
            </span>

            <span class="navbar-role">
                Supervisor
            </span>

        </div>

    </div>


    {{-- RIGHT --}}

    <div class="navbar-right">

        {{-- NOTIFICATION --}}

        <a
            href="{{ route('supervisor.leave.index') }}"
            class="notification-button"
            title="Pengajuan Cuti">

            <span class="notification-icon">
                🔔
            </span>

            @if(isset($pendingLeaveCount) && $pendingLeaveCount > 0)

                <span class="notification-badge">
                    {{ $pendingLeaveCount > 99 ? '99+' : $pendingLeaveCount }}
                </span>

            @endif

        </a>


        {{-- USER --}}

        <div class="navbar-user">

            <div class="user-avatar">

                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

            </div>

            <div class="user-information">

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <span>
                    Supervisor
                </span>

            </div>

            <button
                type="button"
                class="user-menu-button"
                id="user-menu-button">

                ▾

            </button>

        </div>


        {{-- DROPDOWN --}}

        <div
            class="user-dropdown"
            id="user-dropdown">

            <div class="dropdown-header">

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <span>
                    {{ auth()->user()->email }}
                </span>

            </div>

            <div class="dropdown-divider"></div>

            <a href="{{ route('supervisor.dashboard') }}">
                Dashboard
            </a>

            <div class="dropdown-divider"></div>

            <form
                method="POST"
                action="{{ route('logout') }}">

                @csrf

                <button type="submit">

                    Keluar

                </button>

            </form>

        </div>

    </div>

</header>