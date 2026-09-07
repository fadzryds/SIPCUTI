<aside class="sidebar">

    <div class="sidebar-header">

        <div class="logo">

            <img
                src="{{ asset('assets/images/logo.png') }}"
                alt="Logo">

        </div>

        <div>

            <h2>SIP CUTI</h2>

            <span>MANAGER PANEL</span>

        </div>

    </div>

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

    <div class="sidebar-footer">

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