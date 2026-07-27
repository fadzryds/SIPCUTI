<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SIPCUTI</title>

    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">

    @vite([
        'resources/css/navbar.css',
        'resources/css/footer.css',
        'resources/css/dashboard.css',
        'resources/css/app.css',
    ])

</head>

<body>

    @include('layouts.navbar')

    <main class="main-container">

        @yield('content')

    </main>

    @include('layouts.footer')

</body>

</html>