<aside class="director-sidebar">

    {{-- SIDEBAR OVERLAY --}}
    <div class="director-sidebar-overlay"></div>


    {{-- BRAND --}}
    <div class="director-brand">

        <div class="director-brand-logo">

            <i class="fa-solid fa-building-columns"></i>

        </div>

        <div class="director-brand-text">

            <strong>
                SIP Cuti
            </strong>

            <span>
                Director Panel
            </span>

        </div>

    </div>


    {{-- USER PROFILE --}}
    <div class="director-profile">

        <div class="director-profile-avatar">

            {{ strtoupper(
                substr(
                    auth()->user()->name ?? 'D',
                    0,
                    1
                )
            ) }}

        </div>

        <div class="director-profile-info">

            <strong>
                {{ auth()->user()->name ?? 'Director' }}
            </strong>

            <span>
                Director
            </span>

        </div>

    </div>


    {{-- NAVIGATION --}}
    <nav class="director-navigation">

        <div class="director-nav-label">
            MAIN MENU
        </div>


        {{-- DASHBOARD --}}
        <a
            href="{{ route('director.dashboard') }}"
            class="director-nav-item
                {{ request()->routeIs('director.dashboard')
                    ? 'active'
                    : '' }}"
        >

            <span class="director-nav-icon">

                <i class="fa-solid fa-chart-pie"></i>

            </span>

            <span class="director-nav-text">
                Dashboard
            </span>

        </a>


        {{-- APPROVAL --}}
        <a
            href="{{ route('director.approval.index') }}"
            class="director-nav-item
                {{ request()->routeIs('director.approval.*')
                    ? 'active'
                    : '' }}"
        >

            <span class="director-nav-icon">

                <i class="fa-solid fa-file-signature"></i>

            </span>

            <span class="director-nav-text">
                Approval Cuti
            </span>

            @isset($directorPending)

                @if($directorPending > 0)

                    <span class="director-nav-badge">
                        {{ $directorPending > 99 ? '99+' : $directorPending }}
                    </span>

                @endif

            @endisset

        </a>


        <div class="director-nav-divider"></div>


        <div class="director-nav-label">
            ACCOUNT
        </div>


        {{-- LOGOUT --}}
        <form
            action="{{ route('logout') }}"
            method="POST"
            class="director-logout-form"
        >

            @csrf

            <button
                type="submit"
                class="director-nav-item director-logout"
            >

                <span class="director-nav-icon">

                    <i class="fa-solid fa-right-from-bracket"></i>

                </span>

                <span class="director-nav-text">
                    Keluar
                </span>

            </button>

        </form>

    </nav>


    {{-- SIDEBAR FOOTER --}}
    <div class="director-sidebar-footer">

        <div class="director-system-status">

            <span class="status-dot"></span>

            <span>
                Sistem Aktif
            </span>

        </div>

        <small>
            SIP Cuti v1.0
        </small>

    </div>

</aside>


{{-- MOBILE OVERLAY --}}
<div class="director-sidebar-overlay"></div>