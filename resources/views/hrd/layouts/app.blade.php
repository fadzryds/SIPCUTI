<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>

        @yield('title')

        | SIP CUTI HRD

    </title>

    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">

    @vite([
        'resources/css/hrd/navbar.css',
        'resources/css/hrd/footer.css',
        'resources/css/hrd/sidebar.css',
        'resources/css/hrd/app.css',
        'resources/css/hrd/approval-show.css',
        'resources/css/hrd/approval-index.css',
        'resources/css/hrd/dashboard.css',
        'resources/css/hrd/hrd-history.css',
        'resources/css/hrd/history-show.css',
        'resources/css/hrd/hrd.css'
    ])

    {{-- Font Awesome --}}

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    {{-- Google Font --}}

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    @stack('styles')

</head>

<body>

<div class="dashboard-layout">

    {{-- Sidebar --}}

    @include('hrd.layouts.sidebar')

    <div class="dashboard-content">

        {{-- Navbar --}}

        @include('hrd.layouts.navbar')

        <main class="dashboard-main">

            @if(session('success'))

                <div class="alert-success">

                    <i class="fa-solid fa-circle-check"></i>

                    {{ session('success') }}

                </div>

            @endif

            @if(session('error'))

                <div class="alert-danger">

                    <i class="fa-solid fa-circle-xmark"></i>

                    {{ session('error') }}

                </div>

            @endif

            @yield('content')

        </main>

        {{-- Footer --}}

        @include('hrd.layouts.footer')

    </div>

</div>

@stack('scripts')

</body>

</html>