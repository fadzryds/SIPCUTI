<header class="director-navbar">

    <div class="director-navbar-left">

        <button
            type="button"
            class="director-menu-toggle"
            aria-label="Toggle menu"
        >

            <i class="fa-solid fa-bars"></i>

        </button>


        <div class="director-breadcrumb">

            <span>
                Director
            </span>

            <i class="fa-solid fa-chevron-right"></i>

            <strong>
                @yield('page-name', 'Dashboard')
            </strong>

        </div>

    </div>


    <div class="director-navbar-right">

        {{-- NOTIFICATION --}}
        <button
            type="button"
            class="director-navbar-button"
            title="Notifikasi"
        >

            <i class="fa-regular fa-bell"></i>

            @isset($directorPending)

                @if($directorPending > 0)

                    <span class="director-notification-dot"></span>

                @endif

            @endisset

        </button>


        <div class="director-navbar-divider"></div>


        {{-- USER --}}
        <div class="director-navbar-user">

            <div class="director-navbar-avatar">

                {{ strtoupper(
                    substr(
                        auth()->user()->name ?? 'D',
                        0,
                        1
                    )
                ) }}

            </div>

            <div class="director-navbar-user-info">

                <strong>
                    {{ auth()->user()->name ?? 'Director' }}
                </strong>

                <span>
                    Director
                </span>

            </div>

            <i class="fa-solid fa-chevron-down director-user-arrow"></i>

        </div>

    </div>

</header>