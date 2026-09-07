{{-- =========================================================
     SUPERVISOR SIDEBAR
========================================================= --}}

<aside class="supervisor-sidebar" id="supervisorSidebar">

    {{-- =====================================================
         SIDEBAR HEADER
    ====================================================== --}}

    <div class="sidebar-brand">

        <div class="brand-logo">
            SI
        </div>

        <div class="brand-information">

            <strong>
                SIPCUTI
            </strong>

            <span>
                Supervisor Panel
            </span>

        </div>

        {{-- MOBILE CLOSE --}}
        <button
            type="button"
            class="sidebar-close"
            id="sidebarClose"
            aria-label="Tutup menu">

            <svg viewBox="0 0 24 24" fill="none">
                <path
                    d="M6 6L18 18M18 6L6 18"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"/>
            </svg>

        </button>

    </div>


    {{-- =====================================================
         PROFILE
    ====================================================== --}}

    <div class="sidebar-profile">

        <div class="profile-avatar">

            {{ strtoupper(
                substr(
                    auth()->user()->name ?? 'U',
                    0,
                    1
                )
            ) }}

        </div>

        <div class="profile-information">

            <strong>
                {{ auth()->user()->name ?? 'Supervisor' }}
            </strong>

            <span>
                Supervisor
            </span>

        </div>

    </div>


    {{-- =====================================================
         NAVIGATION
    ====================================================== --}}

    <nav class="sidebar-navigation">

        <div class="sidebar-section">
            MENU UTAMA
        </div>


        {{-- DASHBOARD --}}
        <a
            href="{{ route('supervisor.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">

            <span class="sidebar-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <path
                        d="M3 10.5L12 3L21 10.5V20C21 20.55 20.55 21 20 21H4C3.45 21 3 20.55 3 20V10.5Z"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linejoin="round"/>

                    <path
                        d="M9 21V13H15V21"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linejoin="round"/>

                </svg>

            </span>

            <span class="sidebar-text">
                Dashboard
            </span>

        </a>


        {{-- APPROVAL CUTI --}}
        <a
            href="{{ route('supervisor.leave.index') }}"
            class="sidebar-link {{ request()->routeIs('supervisor.leave.*') ? 'active' : '' }}">

            <span class="sidebar-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <path
                        d="M6 3H14L19 8V21H6V3Z"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linejoin="round"/>

                    <path
                        d="M14 3V8H19"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linejoin="round"/>

                    <path
                        d="M9 13H16M9 17H14"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"/>

                </svg>

            </span>

            <span class="sidebar-text">
                Approval Cuti
            </span>


            @if(isset($pending) && $pending > 0)

                <span class="sidebar-badge">
                    {{ $pending }}
                </span>

            @endif

        </a>

    </nav>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="sidebar-footer">

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

        <div class="sidebar-footer-info">

            <span class="status-dot"></span>

            <span>
                Online
            </span>

        </div>

        <small>
            SIPCUTI • Supervisor Panel
        </small>

    </div>

</aside>


{{-- =========================================================
     MOBILE OVERLAY
========================================================= --}}

<div
    class="sidebar-overlay"
    id="sidebarOverlay">
</div>


{{-- =========================================================
     MOBILE HAMBURGER
========================================================= --}}

<button
    type="button"
    class="sidebar-toggle"
    id="sidebarToggle"
    aria-label="Buka menu"
    aria-expanded="false">

    <span></span>
    <span></span>
    <span></span>

</button>


{{-- =========================================================
     SIDEBAR SCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.getElementById('supervisorSidebar');
    const toggle = document.getElementById('sidebarToggle');
    const close = document.getElementById('sidebarClose');
    const overlay = document.getElementById('sidebarOverlay');

    if (!sidebar || !toggle || !close || !overlay) {
        return;
    }


    function openSidebar() {

        sidebar.classList.add('is-open');
        overlay.classList.add('is-visible');

        toggle.classList.add('is-active');

        toggle.setAttribute(
            'aria-expanded',
            'true'
        );

        document.body.classList.add(
            'sidebar-open'
        );
    }


    function closeSidebar() {

        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-visible');

        toggle.classList.remove('is-active');

        toggle.setAttribute(
            'aria-expanded',
            'false'
        );

        document.body.classList.remove(
            'sidebar-open'
        );
    }


    toggle.addEventListener(
        'click',
        function () {

            if (
                sidebar.classList.contains(
                    'is-open'
                )
            ) {

                closeSidebar();

            } else {

                openSidebar();

            }

        }
    );


    close.addEventListener(
        'click',
        closeSidebar
    );


    overlay.addEventListener(
        'click',
        closeSidebar
    );


    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                sidebar.classList.contains(
                    'is-open'
                )
            ) {

                closeSidebar();

            }

        }
    );


    /*
     * Tutup sidebar setelah memilih menu
     * pada perangkat mobile.
     */

    sidebar
        .querySelectorAll('.sidebar-link')
        .forEach(function (link) {

            link.addEventListener(
                'click',
                function () {

                    if (
                        window.innerWidth <= 1050
                    ) {

                        closeSidebar();

                    }

                }
            );

        });


    /*
     * Jika layar berubah dari mobile
     * ke desktop, reset state sidebar.
     */

    window.addEventListener(
        'resize',
        function () {

            if (
                window.innerWidth > 1050
            ) {

                closeSidebar();

            }

        }
    );

});

</script>