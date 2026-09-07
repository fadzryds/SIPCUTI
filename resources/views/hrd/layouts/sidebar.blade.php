<aside class="hrd-sidebar">

    {{-- LOGO --}}
    <div class="hrd-sidebar-brand">

        <div class="hrd-brand-icon">
            <i class="fas fa-building"></i>
        </div>

        <div class="hrd-brand-text">

            <strong>
                HRD SYSTEM
            </strong>

            <span>
                Human Resources
            </span>

        </div>

    </div>


    {{-- USER --}}
    <div class="hrd-user-box">

        <div class="hrd-user-avatar">

            <i class="fas fa-user-tie"></i>

        </div>

        <div class="hrd-user-info">

            <strong>
                {{ auth()->user()->name }}
            </strong>

            <span>
                HRD
            </span>

        </div>

    </div>


    {{-- NAVIGATION --}}
    <nav class="hrd-navigation">

        <span class="hrd-nav-label">
            UTAMA
        </span>

        <a
            href="{{ route('hrd.dashboard') }}"
            class="hrd-nav-item
                {{ request()->routeIs('hrd.dashboard') ? 'active' : '' }}"
        >

            <i class="fas fa-chart-pie"></i>

            <span>
                Dashboard
            </span>

        </a>


        <span class="hrd-nav-label">
            DATA MASTER
        </span>

        <a
            href="{{ route('hrd.employees.index') }}"
            class="hrd-nav-item
                {{ request()->routeIs('hrd.employees.*') ? 'active' : '' }}"
        >
        
            <i class="fas fa-users"></i>
        
            <span>
                Employees
            </span>
        
        </a>


        {{-- Department --}}
        <a
            href="{{ route('hrd.departments.index') }}"
            class="hrd-nav-item
                {{ request()->routeIs('hrd.departments.*') ? 'active' : '' }}"
        >

            <i class="fas fa-building"></i>

            <span>
                Departments
            </span>

        </a>


        {{-- Position --}}
        <a
            href="{{ route('hrd.positions.index') }}"
            class="hrd-nav-item
                {{ request()->routeIs('hrd.positions.*') ? 'active' : '' }}"
        >

            <i class="fas fa-briefcase"></i>

            <span>
                Positions
            </span>

        </a>


        <span class="hrd-nav-label">
            CUTI
        </span>


        {{-- Leave --}}
        <a
            href="{{ route('hrd.leave-requests.index') }}"
            class="hrd-nav-item
                {{ request()->routeIs('hrd.leave-requests.*') ? 'active' : '' }}"
        >

            <i class="fas fa-calendar-check"></i>

            <span>
                Pengajuan Cuti
            </span>

        </a>


        {{-- Leave Report --}}
        <a
            href="{{ route('hrd.reports.leave') }}"
            class="hrd-nav-item
                {{ request()->routeIs('hrd.reports.*') ? 'active' : '' }}"
        >

            <i class="fas fa-file-lines"></i>

            <span>
                Laporan Cuti
            </span>

        </a>

        <a
            href="{{ route('hrd.leave-balances.index') }}"
            class="hrd-nav-item {{ request()->routeIs('hrd.leave-balances.*') ? 'active' : '' }}"
        >
            <i class="fas fa-wallet"></i>

            <span>
                Saldo Cuti
            </span>
        </a>


        <span class="hrd-nav-label">
            AKUN
        </span>


        {{-- Profile --}}
        <a
            href="{{ route('hrd.profile') }}"
            class="hrd-nav-item
                {{ request()->routeIs('hrd.profile') ? 'active' : '' }}"
        >

            <i class="fas fa-user-circle"></i>

            <span>
                Profile
            </span>

        </a>

    </nav>


    {{-- LOGOUT --}}
    <div class="hrd-sidebar-footer">

        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="hrd-logout"
            >

                <i class="fas fa-right-from-bracket"></i>

                <span>
                    Logout
                </span>

            </button>

        </form>

    </div>

</aside>