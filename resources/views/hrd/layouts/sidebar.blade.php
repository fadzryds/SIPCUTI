<aside class="sidebar">

    <div class="sidebar-header">

        <div class="logo">

            <img
                src="{{ asset('assets/images/logo.png') }}"
                alt="Logo">

        </div>

        <div>

            <h2>SIP CUTI</h2>

            <span>HRD PANEL</span>

        </div>

    </div>

    <nav class="sidebar-menu">

        <a
            href="{{ route('hrd.dashboard') }}"
            class="{{ request()->routeIs('hrd.dashboard') ? 'active' : '' }}">

            <i class="fa-solid fa-house"></i>

            Dashboard

        </a>

        <a
            href="{{ route('hrd.approval.index') }}"
            class="{{ request()->routeIs('hrd.approval.*') ? 'active' : '' }}">

            <i class="fa-solid fa-file-signature"></i>

            Approval Cuti

        </a>

        <a
            href="{{ route('hrd.history.index') }}"
            class="{{ request()->routeIs('hrd.history.*') ? 'active' : '' }}">

            <i class="fa-solid fa-clock-rotate-left"></i>

            Riwayat Approval

        </a>

        <a
            href="{{ route('hrd.profile') }}"
            class="{{ request()->routeIs('hrd.profile') ? 'active' : '' }}">

            <i class="fa-solid fa-user"></i>

            Profile

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