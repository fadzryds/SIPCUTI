<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | SIPCUTI</title>

    <link rel="icon"
          href="{{ asset('assets/images/logo.png') }}">

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600&display=swap"
          rel="stylesheet">

    @vite([
        'resources/css/auth.css',
        'resources/js/auth.js'
    ])

</head>

<body>

<div class="login-page">

    {{-- Background --}}
    <div class="background-gradient"></div>
    <div class="background-beam"></div>

    <div class="background-circle circle-1"></div>
    <div class="background-circle circle-2"></div>
    <div class="background-circle circle-3"></div>


    <div class="login-container">

        {{-- =====================================================
             LEFT SIDE
        ====================================================== --}}

        <div class="login-left">

            {{-- Logo --}}
            <div class="logo-area">

                <div class="logo">

                    <img
                        src="{{ asset('assets/images/logo.png') }}"
                        alt="Logo SIPCUTI"
                    >

                </div>

                <div class="logo-text">

                    <h2>SIPCUTI</h2>

                    <p>Employee Leave Management System</p>

                </div>

            </div>


            {{-- Illustration --}}
            <div class="hero-illustration">

                <div class="beam beam-1"></div>

                <div class="beam beam-2"></div>

                <div class="pillars"></div>

                <div class="floor-glow"></div>

                <div class="floor-rings"></div>

                <div class="floor-rings ring-delay"></div>


                <div class="orb-wrap">

                    <div class="orb-satellite orb-satellite-1"></div>

                    <div class="orb-satellite orb-satellite-2"></div>

                    <div class="orb-main"></div>

                </div>

            </div>


            {{-- Information Card --}}
            <div class="floating-card">

                <div class="float-title">
                    Kelola Cuti Lebih Mudah
                </div>

                <div class="float-sub">
                    Proses cepat, pengawasan akurat,
                    keputusan lebih tepat.
                </div>

                <div class="carousel-dots">

                    <span class="dot active"></span>

                    <span class="dot"></span>

                    <span class="dot"></span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT SIDE
        ====================================================== --}}

        <div class="login-right">

            <div class="login-card">


                {{-- Header --}}
                <div class="login-header">

                    <span class="welcome-badge">
                        SIPCUTI
                    </span>

                    <h1>
                        Selamat Datang
                    </h1>

                    <p>
                        Silakan masuk untuk melanjutkan
                        ke sistem pengajuan cuti.
                    </p>

                </div>


                {{-- Form --}}
                <form method="POST"
                      action="{{ route('login') }}">

                    @csrf


                    {{-- Login --}}
                    <div class="input-group">

                        <label
                            for="login"
                            class="input-label">

                            Email atau Nomor Handphone

                        </label>


                        <div class="input-box">

                            {{-- User Icon --}}
                            <svg
                                class="input-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">

                                <circle
                                    cx="12"
                                    cy="8"
                                    r="3.2"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M5.5 20C5.5 16.6863 8.41015 14 12 14C15.5899 14 18.5 16.6863 18.5 20"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />

                            </svg>


                            <input
                                type="text"
                                name="login"
                                id="login"
                                placeholder="Masukkan email atau nomor HP"
                                value="{{ old('login') }}"
                                autocomplete="username"
                                required
                                autofocus
                            >

                        </div>


                        @error('login')

                            <small class="error-text">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    {{-- Password --}}
                    <div class="input-group">

                        <label
                            for="password"
                            class="input-label">

                            Password

                        </label>


                        <div class="input-box">

                            {{-- Lock Icon --}}
                            <svg
                                class="input-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">

                                <rect
                                    x="4.5"
                                    y="10.5"
                                    width="15"
                                    height="9.5"
                                    rx="2.3"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                                <path
                                    d="M7.5 10.5V7.75C7.5 5.5 9.29 3.7 12 3.7C14.71 3.7 16.5 5.5 16.5 7.75V10.5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />

                            </svg>


                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >


                            {{-- Toggle Password --}}
                            <button
                                type="button"
                                id="togglePassword"
                                class="toggle-password"
                                aria-label="Tampilkan password">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg">

                                    <path
                                        d="M2 12C2 12 5.5 5.5 12 5.5C18.5 5.5 22 12 22 12C22 12 18.5 18.5 12 18.5C5.5 18.5 2 12 2 12Z"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linejoin="round"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="3"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    />

                                </svg>

                            </button>

                        </div>


                        @error('password')

                            <small class="error-text">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    {{-- Options --}}
                    <div class="login-option">

                        <div class="remember-wrapper">

                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                value="1"
                            >

                            <label for="remember">
                                Ingat saya
                            </label>

                        </div>


                        @if(Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="forgot-link">

                                Lupa password?

                            </a>

                        @endif

                    </div>


                    {{-- Login Button --}}
                    <button
                        type="submit"
                        class="login-btn">

                        <span>
                            Masuk ke SIPCUTI
                        </span>

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg">

                            <path
                                d="M9 5L16 12L9 19"
                                stroke="currentColor"
                                stroke-width="2.2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                        </svg>

                    </button>

                </form>


                {{-- Register --}}
                @if(Route::has('register'))

                    <p class="register-text">

                        Belum memiliki akun?

                        <a href="{{ route('register') }}">
                            Daftar sekarang
                        </a>

                    </p>

                @endif


                {{-- Security Information --}}
                <div class="login-security">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">

                        <path
                            d="M12 3L19 6V11.5C19 16.2 16.05 19.75 12 21C7.95 19.75 5 16.2 5 11.5V6L12 3Z"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linejoin="round"
                        />

                        <path
                            d="M9 12L11 14L15.5 9.5"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                    </svg>

                    <span>
                        Sistem login aman dan terlindungi
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const prefersReducedMotion =
        window.matchMedia(
            '(prefers-reduced-motion: reduce)'
        ).matches;


    /*
    |--------------------------------------------------------------------------
    | Toggle Password
    |--------------------------------------------------------------------------
    */

    const togglePassword =
        document.getElementById('togglePassword');

    const passwordInput =
        document.getElementById('password');


    if (togglePassword && passwordInput) {

        togglePassword.addEventListener('click', function () {

            const isHidden =
                passwordInput.type === 'password';


            passwordInput.type =
                isHidden ? 'text' : 'password';


            togglePassword.classList.toggle(
                'active',
                isHidden
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Carousel Dots
    |--------------------------------------------------------------------------
    */

    const dots =
        document.querySelectorAll(
            '.carousel-dots .dot'
        );


    if (
        dots.length &&
        !prefersReducedMotion
    ) {

        let dotIndex = 0;


        setInterval(function () {

            dots[dotIndex].classList.remove(
                'active'
            );


            dotIndex =
                (dotIndex + 1) % dots.length;


            dots[dotIndex].classList.add(
                'active'
            );

        }, 3200);

    }

});

</script>

</body>

</html>