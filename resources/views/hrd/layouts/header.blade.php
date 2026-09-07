<header class="hrd-header">

    <div class="hrd-header-left">

        <button
            type="button"
            class="hrd-menu-toggle"
            id="hrdMenuToggle"
        >

            <i class="fas fa-bars"></i>

        </button>

        <div>

            <span class="hrd-header-label">
                HUMAN RESOURCE DEVELOPMENT
            </span>

            <h1>
                @yield('page-title', 'Dashboard')
            </h1>

        </div>

    </div>


    <div class="hrd-header-right">

        <div class="hrd-header-date">

            <i class="far fa-calendar"></i>

            <span>
                {{ now()->translatedFormat('d F Y') }}
            </span>

        </div>


        <div class="hrd-header-user">

            <div class="hrd-header-avatar">

                <i class="fas fa-user-tie"></i>

            </div>

            <div>

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <span>
                    HRD
                </span>

            </div>

        </div>

    </div>

</header>