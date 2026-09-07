<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <title>

        @yield('title')

        | SIPCUTI Supervisor

    </title>

    @vite([
        'resources/css/supervisor/app.css',
        'resources/js/supervisor/app.js'
    ])

    @stack('styles')

    <link rel="icon"
          href="{{ asset('assets/images/logo.png') }}">

</head>

<body>

    <div class="supervisor-layout">

        {{-- SIDEBAR --}}
        @include('supervisor.layouts.sidebar')

        {{-- MAIN AREA --}}
        <div class="supervisor-main">

            {{-- NAVBAR --}}
            @include('supervisor.layouts.navbar')

            {{-- CONTENT --}}
            <main class="supervisor-content">

                @if(session('success'))

                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>

                @endif

                @if(session('error'))

                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>

                @endif

                @if($errors->any())

                    <div class="alert alert-error">

                        <strong>
                            Terjadi kesalahan:
                        </strong>

                        <ul>

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                @yield('content')

            </main>

        </div>

    </div>

    @vite([
        'resources/js/supervisor/app.js'
    ])

    @stack('scripts')

</body>

</html>