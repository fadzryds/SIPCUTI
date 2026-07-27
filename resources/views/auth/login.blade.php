<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | SIPCUTI</title>
    <link rel="icon"
          href="{{ asset('assets/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">

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

        {{-- LEFT SIDE --}}
        <div class="login-left">

            <div class="logo-area">

                <div class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SIPCUTI">
                </div>

                <div class="logo-text">

                    <h2>SIPCUTI</h2>

                    <p>Employee Leave Management System</p>

                </div>

            </div>

            {{-- Illustration (CSS-generated, gantikan dengan asset asli bila ada) --}}
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

            <div class="floating-card">

                <div class="float-title">
                    Kelola Cuti Lebih Mudah
                </div>

                <div class="float-sub">
                    Proses cepat, pengawasan akurat, keputusan lebih tepat.
                </div>

                <div class="carousel-dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>

            </div>

        </div>

        {{-- RIGHT SIDE --}}
        <div class="login-right">

            <div class="login-card">

                <div class="login-header">

                    <h1>

                        Login your account!

                    </h1>

                    <p>

                        Silakan masuk untuk melanjutkan ke SIPCUTI

                    </p>

                </div>

                <div class="login-tabs">
                    <button type="button" class="tab-btn active" data-tab="email">E-mail</button>
                    <span class="tab-indicator"></span>
                </div>

                <form method="POST"
                      action="{{ route('login') }}">

                    @csrf

                    <div class="input-group">

                        <div class="input-box">

                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M3 6.5L11.14 12.18C11.6614 12.5433 12.3386 12.5433 12.86 12.18L21 6.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Email"
                                value="{{ old('email') }}"
                                required
                                autofocus>

                        </div>

                        @error('email')

                        <small class="error-text">

                            {{ $message }}

                        </small>

                        @enderror

                    </div>

                    <div class="input-group">

                        <div class="input-box">

                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="4.5" y="10.5" width="15" height="9.5" rx="2.3" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M7.5 10.5V7.75C7.5 5.5 9.29 3.7 12 3.7C14.71 3.7 16.5 5.5 16.5 7.75V10.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Password"
                                required>

                            <button
                                type="button"
                                id="togglePassword"
                                class="toggle-password"
                                aria-label="Tampilkan password">

                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 12C2 12 5.5 5.5 12 5.5C18.5 5.5 22 12 22 12C22 12 18.5 18.5 12 18.5C5.5 18.5 2 12 2 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                                </svg>

                            </button>

                        </div>

                        @error('password')

                        <small class="error-text">

                            {{ $message }}

                        </small>

                        @enderror

                    </div>

                    <div class="login-option">

                        @if(Route::has('password.request'))

                        <a href="{{ route('password.request') }}" class="forgot-link">

                            Forgot password?

                        </a>

                        @endif

                    </div>

                    <button
                        type="submit"
                        class="login-btn">

                        <span>Continue</span>

                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>

                    </button>

                </form>

                <div class="divider">

                    <span>

                        Or continue with

                    </span>

                </div>

                <div class="social-login">

                    <button type="button" class="social-btn" aria-label="Continue with Google">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#EA4335" d="M12 10.2v3.96h5.52c-.24 1.38-1.68 4.05-5.52 4.05-3.33 0-6.05-2.76-6.05-6.15S8.67 5.91 12 5.91c1.9 0 3.17.81 3.9 1.5l2.66-2.56C16.9 3.24 14.66 2.25 12 2.25 6.9 2.25 2.75 6.42 2.75 11.55S6.9 20.85 12 20.85c6.93 0 8.87-4.86 8.87-7.38 0-.5-.05-.87-.12-1.27H12z"/>
                        </svg>
                    </button>

                    <button type="button" class="social-btn" aria-label="Continue with Facebook">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#1877F2" d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.16 8.44 9.94v-7.03H7.9v-2.9h2.54V9.86c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.9h-2.34V22c4.78-.78 8.44-4.94 8.44-9.94z"/>
                        </svg>
                    </button>

                    <button type="button" class="social-btn" aria-label="Continue with Apple">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#111111" d="M16.36 1.5c.11 1-.29 2-.9 2.75-.62.76-1.65 1.36-2.65 1.28-.13-.98.34-2 .93-2.68.66-.75 1.77-1.31 2.62-1.35zM20.6 17.32c-.44 1-.65 1.45-1.22 2.33-.79 1.23-1.9 2.76-3.29 2.78-1.23.02-1.55-.8-3.21-.79-1.67.01-2.02.8-3.25.78-1.38-.02-2.44-1.4-3.23-2.63-2.22-3.44-2.45-7.47-1.08-9.62.97-1.53 2.5-2.42 3.94-2.42 1.47 0 2.39.81 3.6.81 1.18 0 1.9-.81 3.6-.81 1.29 0 2.66.7 3.63 1.92-3.19 1.75-2.67 6.31.51 7.65z"/>
                        </svg>
                    </button>

                </div>

                <p class="register-text">

                    Don't have an account?
                    <a href="{{ route('register') }}">Register</a>

                </p>

            </div>

        </div>

    </div>

</div>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // ---- Toggle tampil/sembunyi password ----------------------------
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput  = document.getElementById('password');

        if (togglePassword && passwordInput) {

            togglePassword.addEventListener('click', function () {

                const isHidden = passwordInput.getAttribute('type') === 'password';

                passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
                togglePassword.classList.toggle('active', isHidden);

            });

        }

        // ---- Tab Email / Mobile Number + indikator geser -----------------
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabIndicator = document.querySelector('.tab-indicator');
        const emailInput = document.getElementById('email');

        function moveIndicator(btn) {
            if (!tabIndicator) return;
            tabIndicator.style.width = btn.offsetWidth + 'px';
            tabIndicator.style.transform = 'translateX(' + btn.offsetLeft + 'px)';
        }

        if (tabButtons.length && tabIndicator) {

            moveIndicator(document.querySelector('.tab-btn.active'));

            tabButtons.forEach(function (btn) {

                btn.addEventListener('click', function () {

                    tabButtons.forEach(function (b) { b.classList.remove('active'); });
                    btn.classList.add('active');
                    moveIndicator(btn);

                    if (btn.dataset.tab === 'phone') {
                        emailInput.type        = 'tel';
                        emailInput.placeholder = 'Nomor HP';
                    } else {
                        emailInput.type        = 'email';
                        emailInput.placeholder = 'Email';
                    }

                });

            });

            window.addEventListener('resize', function () {
                moveIndicator(document.querySelector('.tab-btn.active'));
            });

        }

        // ---- Auto-cycle carousel dots -------------------------------------
        const dots = document.querySelectorAll('.carousel-dots .dot');

        if (dots.length && !prefersReducedMotion) {

            let dotIndex = 0;

            setInterval(function () {
                dots[dotIndex].classList.remove('active');
                dotIndex = (dotIndex + 1) % dots.length;
                dots[dotIndex].classList.add('active');
            }, 3200);

        }

    });

</script>

</body>

</html>