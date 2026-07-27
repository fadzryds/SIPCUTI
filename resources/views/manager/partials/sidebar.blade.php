<aside class="sidebar">

    {{-- Logo --}}
    <div class="sidebar-header">

        <div class="logo-box">

            <img
                src="{{ asset('assets/images/logo.png') }}"
                alt="Logo">

        </div>

        <div>

            <h2>SIPCUTI</h2>

            <small>Manager Panel</small>

        </div>

    </div>

    {{-- Menu --}}
    <nav class="sidebar-menu">

        <a
            href="{{ route('manager.dashboard') }}"
            class="{{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">

            <i class="fa-solid fa-house"></i>

            <span>Dashboard</span>

        </a>

        <a
            href="{{ route('manager.approval.index') }}"
            class="{{ request()->routeIs('manager.approval.*') ? 'active' : '' }}">

            <i class="fa-solid fa-file-signature"></i>

            <span>Approval Cuti</span>

        </a>

        <a
            href="{{ route('manager.history.index') }}"
            class="{{ request()->routeIs('manager.history.*') ? 'active' : '' }}">

            <i class="fa-solid fa-clock-rotate-left"></i>

            <span>Riwayat Approval</span>

        </a>

        <a
            href="{{ route('manager.profile') }}"
            class="{{ request()->routeIs('manager.manager-profile') ? 'active' : '' }}">

            <i class="fa-solid fa-user"></i>

            <span>Profile</span>

        </a>

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">

        <div class="manager-card">

            <div class="avatar">

                {{ strtoupper(substr(Auth::user()->name,0,1)) }}

            </div>

            <div>

                <strong>{{ Auth::user()->name }}</strong>

                <small>Manager</small>

            </div>

        </div>

        <form
            action="{{ route('logout') }}"
            method="POST">

            @csrf

            <button
                type="submit"
                class="logout-btn">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </button>

        </form>

    </div>

</aside>