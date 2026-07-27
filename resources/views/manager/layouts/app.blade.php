<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>

        @yield('title')

        | SIPCUTI Manager

    </title>

    @vite([
        'resources/css/navbar.css',
        'resources/css/footer.css',
        'resources/css/manager.css',
        'resources/css/app.css',
        'resources/css/approval.css',
        'resources/css/approval-show.css',
        'resources/css/manager-profile.css',
        'resources/css/manager-history.css'
    ])

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <link rel="icon"
          href="{{ asset('assets/images/logo.png') }}">

    {{-- Font --}}
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    {{-- CSS --}}
    <link rel="stylesheet"
          href="{{ asset('css/manager.css') }}">

    @stack('styles')

</head>

<body>

<div class="layout">

    {{-- Sidebar --}}
    @include('manager.partials.sidebar')

    {{-- Main --}}
    <div class="main-wrapper">

        {{-- Navbar --}}
        @include('manager.partials.navbar')

        {{-- Content --}}
        <main class="page-content">

            @yield('content')

        </main>

    </div>

</div>

<script src="{{ asset('js/manager.js') }}"></script>

@stack('scripts')

</body>

</html>