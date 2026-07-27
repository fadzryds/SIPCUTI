<header class="topbar">

    <div class="topbar-left">

        <h1>

            @yield('title')

        </h1>

        <p>

            Sistem Informasi Pengajuan Cuti • Human Resource Department

        </p>

    </div>

    <div class="topbar-right">

        <div class="user-info">

            <div class="user-avatar">

                {{ strtoupper(substr(Auth::user()->name,0,1)) }}

            </div>

            <div>

                <strong>

                    {{ Auth::user()->name }}

                </strong>

                <span>

                    HRD

                </span>

            </div>

        </div>

    </div>

</header>