<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Dashboard Director')
        | Sistem Informasi Cuti
    </title>

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

    {{-- Director CSS --}}
    @vite([
        'resources/css/director/app.css',
        'resources/css/director/sidebar.css',
        'resources/css/director/navbar.css',
        'resources/css/director/dashboard.css',
        'resources/css/director/approval.css'
    ])

    @stack('styles')

    <link rel="icon"
          href="{{ asset('assets/images/logo.png') }}">

</head>

<body>

<div class="director-layout">

    {{-- SIDEBAR --}}
    @include('director.layouts.sidebar')


    <div class="director-main">

        {{-- NAVBAR --}}
        @include('director.layouts.navbar')


        {{-- CONTENT --}}
        <main class="director-content">

            @yield('content')

        </main>

    </div>

</div>


{{-- GLOBAL SCRIPT --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.querySelector('.director-sidebar');
    const overlay = document.querySelector('.director-sidebar-overlay');
    const toggle = document.querySelector('.director-menu-toggle');

    if (!sidebar || !toggle) {
        return;
    }

    toggle.addEventListener('click', function () {

        sidebar.classList.toggle('active');

        if (overlay) {
            overlay.classList.toggle('active');
        }

    });

    if (overlay) {

        overlay.addEventListener('click', function () {

            sidebar.classList.remove('active');
            overlay.classList.remove('active');

        });

    }

});

</script>

@stack('scripts')

</body>

</html>