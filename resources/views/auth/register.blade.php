<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register | SIPCUTI</title>
    <link rel="icon"
          href="{{ asset('assets/images/logo.png') }}">
          
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    @vite([
        'resources/css/auth.css',
        'resources/js/auth.js'
    ])

</head>

<body>

<div class="login-page">

    <div class="background-gradient"></div>

    <div class="background-beam"></div>

    <div class="background-circle circle-1"></div>

    <div class="background-circle circle-2"></div>

    <div class="background-circle circle-3"></div>

    <div class="login-container">

        {{-- ========================================================= --}}
        {{-- LEFT SIDE --}}
        {{-- ========================================================= --}}

        <div class="login-left">

            <div class="logo-area">

                <div class="logo">

                    <img src="{{ asset('assets/images/logo.png') }}"
                         alt="Logo">

                </div>

                <div class="logo-text">

                    <h2>SIPCUTI</h2>

                    <p>Employee Leave Management System</p>

                </div>

            </div>

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

                    Selamat Datang di SIPCUTI

                </div>

                <div class="float-sub">

                    Daftarkan akun Anda dan nikmati sistem pengajuan cuti yang
                    modern, cepat, aman, dan terintegrasi.

                </div>

                <div class="carousel-dots">

                    <span class="dot active"></span>

                    <span class="dot"></span>

                    <span class="dot"></span>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- RIGHT SIDE --}}
        {{-- ========================================================= --}}

        <div class="login-right">

            <div class="login-card register-card">

                <div class="login-header">

                    <h1>

                        Create your account!

                    </h1>

                    <p>

                        Silakan lengkapi data berikut untuk membuat akun SIPCUTI.

                    </p>

                </div>
            
            <form method="POST"
                action="{{ route('register') }}">
            {{-- ========================= --}}
            {{-- FULL NAME --}}
            {{-- ========================= --}}

            <div class="input-group">
            
                <div class="input-box">
                
                    <svg class="input-icon"
                         viewBox="0 0 24 24"
                         fill="none">
                
                        <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                              stroke="currentColor"
                              stroke-width="1.8"/>
                
                        <path d="M4 22C4 18.6863 7.58172 16 12 16C16.4183 16 20 18.6863 20 22"
                              stroke="currentColor"
                              stroke-width="1.8"
                              stroke-linecap="round"/>
                
                    </svg>
                
                    <input
                        type="text"
                        name="name"
                        placeholder="Full Name"
                        value="{{ old('name') }}"
                        required
                        autofocus>
                
                </div>
            
                @error('name')
            
                    <small class="error-text">
                    
                        {{ $message }}
                    
                    </small>
                
                @enderror
                
            </div>

            {{-- ========================= --}}
            {{-- EMAIL --}}
            {{-- ========================= --}}

            <div class="input-group">
            
                <div class="input-box">
                
                    <svg class="input-icon"
                         viewBox="0 0 24 24"
                         fill="none">
                
                        <rect x="3"
                              y="5"
                              width="18"
                              height="14"
                              rx="2.5"
                              stroke="currentColor"
                              stroke-width="1.8"/>
                
                        <path d="M3 6.5L11.2 12.2C11.7 12.55 12.3 12.55 12.8 12.2L21 6.5"
                              stroke="currentColor"
                              stroke-width="1.8"/>
                
                    </svg>
                
                    <input
                        type="email"
                        name="email"
                        placeholder="Email Address"
                        value="{{ old('email') }}"
                        required>
                
                </div>
            
                @error('email')
            
                    <small class="error-text">
                    
                        {{ $message }}
                    
                    </small>
                
                @enderror
                
            </div>

            {{-- ========================= --}}
            {{-- PHONE --}}
            {{-- ========================= --}}

            <div class="input-group">
            
                <div class="input-box">
                
                    <svg class="input-icon"
                         viewBox="0 0 24 24"
                         fill="none">
                
                        <path d="M5 4H8L9.5 8L7.5 9.5C8.6 11.8 10.2 13.4 12.5 14.5L14 12.5L18 14V17C18 18.1 17.1 19 16 19C9.9 19 5 14.1 5 8C5 6.9 5.9 6 7 6H5V4Z"
                              stroke="currentColor"
                              stroke-width="1.8"
                              stroke-linejoin="round"/>
                
                    </svg>
                
                    <input
                        type="text"
                        name="phone"
                        placeholder="Phone Number"
                        value="{{ old('phone') }}"
                        required>
                
                </div>
            
                @error('phone')
            
                    <small class="error-text">
                    
                        {{ $message }}
                    
                    </small>
                
                @enderror
                
            </div>

            {{-- ========================= --}}
            {{-- PASSWORD --}}
            {{-- ========================= --}}

            <div class="input-group">
            
                <div class="input-box">
                
                    <svg class="input-icon"
                         viewBox="0 0 24 24"
                         fill="none">
                
                        <rect x="4.5"
                              y="10.5"
                              width="15"
                              height="9"
                              rx="2"
                              stroke="currentColor"
                              stroke-width="1.8"/>

                        <path d="M7.5 10.5V8C7.5 5.5 9.3 3.7 12 3.7C14.7 3.7 16.5 5.5 16.5 8V10.5"
                              stroke="currentColor"
                              stroke-width="1.8"/>

                    </svg>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Password"
                        required>

                    <button
                        class="toggle-password"
                        id="togglePassword"
                        type="button">

                        <svg viewBox="0 0 24 24"
                             fill="none">

                            <path d="M2 12C2 12 5.5 5.5 12 5.5C18.5 5.5 22 12 22 12C22 12 18.5 18.5 12 18.5C5.5 18.5 2 12 2 12Z"
                                  stroke="currentColor"
                                  stroke-width="1.8"/>

                            <circle cx="12"
                                    cy="12"
                                    r="3"
                                    stroke="currentColor"
                                    stroke-width="1.8"/>

                        </svg>

                    </button>

                </div>

                @error('password')

                    <small class="error-text">

                        {{ $message }}

                    </small>

                @enderror

            </div>

            {{-- ========================= --}}
            {{-- CONFIRM PASSWORD --}}
            {{-- ========================= --}}

            <div class="input-group">

                <div class="input-box">

                    <svg class="input-icon"
                         viewBox="0 0 24 24"
                         fill="none">

                        <rect x="4.5"
                              y="10.5"
                              width="15"
                              height="9"
                              rx="2"
                              stroke="currentColor"
                              stroke-width="1.8"/>

                        <path d="M7.5 10.5V8C7.5 5.5 9.3 3.7 12 3.7C14.7 3.7 16.5 5.5 16.5 8V10.5"
                              stroke="currentColor"
                              stroke-width="1.8"/>

                    </svg>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm Password"
                        required>

                </div>

            </div>

            <div class="terms">

                <input
                    type="checkbox"
                    required>
            
                <span>
                    I agree to the
                    <a href="#">Terms</a>
                    &
                    <a href="#">Privacy Policy</a>
                </span>
            
            </div>

            <button
                type="submit"
                class="login-btn">

                <span>

                    Create Account

                </span>

                <svg viewBox="0 0 24 24"
                     fill="none">

                    <path d="M9 5L16 12L9 19"
                          stroke="currentColor"
                          stroke-width="2"/>

                </svg>

            </button>

            <p class="register-text">

                Already have an account?

                <a href="{{ route('login') }}">

                    Login

                </a>

            </p>
                    @csrf
            </form>

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