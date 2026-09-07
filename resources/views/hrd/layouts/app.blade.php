<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Dashboard HRD')
        | Sistem Informasi Cuti
    </title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    @vite([
        'resources/css/hrd/app.css',
        'resources/css/hrd/employees.css',
        'resources/css/hrd/dashboard.css',
        'resources/css/hrd/create.css',
        'resources/css/hrd/show.css',
        'resources/css/hrd/departments.css',
        'resources/css/hrd/positions.css',
        'resources/css/hrd/leave.css',
        'resources/css/hrd/leave-show.css',
        'resources/css/hrd/leave-report.css',
        'resources/css/hrd/leave-balance.css',
        'resources/css/hrd/profile.css',
        'resources/css/hrd/employee-import.css'
    ])

    @stack('styles')

    <link rel="icon"
          href="{{ asset('assets/images/logo.png') }}">

</head>

<body>

    <div class="hrd-layout">

        {{-- SIDEBAR --}}
        @include('hrd.layouts.sidebar')

        <div class="hrd-main">

            {{-- HEADER --}}
            @include('hrd.layouts.header')

            <main class="hrd-content">

                @yield('content')

            </main>

        </div>

    </div>

    @stack('scripts')

</body>

</html>