@extends('hrd.layouts.app')

@section('title', 'Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/hrd/profile.css') }}">
@endpush

@section('content')

<div class="hrd-profile-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="profile-page-header">

        <div>
            <span class="page-eyebrow">
                <i class="fa-solid fa-user-shield"></i>
                ACCOUNT MANAGEMENT
            </span>

            <h1>Profile</h1>

            <p>
                Kelola informasi akun dan lihat ringkasan data kepegawaian Anda.
            </p>
        </div>

        <div class="header-date">
            <i class="fa-regular fa-calendar"></i>

            <span>
                {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>

    </div>


    {{-- =========================================================
        PROFILE HERO
    ========================================================== --}}

    <section class="profile-hero">

        <div class="hero-background-shape hero-shape-one"></div>
        <div class="hero-background-shape hero-shape-two"></div>

        <div class="profile-hero-content">

            {{-- AVATAR --}}

            <div class="profile-avatar-wrapper">

                <div class="profile-avatar">

                    {{ strtoupper(
                        substr(
                            $employee->user->name ?? 'H',
                            0,
                            1
                        )
                    ) }}

                </div>

                <span class="avatar-status"></span>

            </div>


            {{-- INFORMATION --}}

            <div class="profile-identity">

                <div class="profile-role-label">
                    <span></span>
                    HRD / HUMAN RESOURCE
                </div>

                <h2>
                    {{ $employee->user->name ?? 'Nama Tidak Diketahui' }}
                </h2>

                <p class="profile-position">

                    <i class="fa-solid fa-briefcase"></i>

                    {{ $employee->position->name ?? 'HRD' }}

                    <span class="profile-divider">•</span>

                    {{ $employee->department->name ?? 'Department' }}

                </p>

                <div class="profile-meta">

                    <div class="profile-meta-item">

                        <i class="fa-solid fa-id-card"></i>

                        <div>
                            <span>NIK</span>
                            <strong>
                                {{ $employee->nik ?? '-' }}
                            </strong>
                        </div>

                    </div>


                    <div class="profile-meta-item">

                        <i class="fa-solid fa-calendar-check"></i>

                        <div>
                            <span>Bergabung</span>
                            <strong>
                                {{ optional($employee->join_date)->format('d M Y') ?? '-' }}
                            </strong>
                        </div>

                    </div>


                    <div class="profile-meta-item">

                        <i class="fa-solid fa-circle-check"></i>

                        <div>
                            <span>Status</span>

                            <strong class="status-active">
                                {{ $employee->status ?? 'Active' }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- HERO ACTION --}}

        <div class="profile-hero-action">

            <div class="account-status">

                <span class="status-dot"></span>

                <div>
                    <strong>Akun Aktif</strong>
                    <small>Sistem SIPCUTI</small>
                </div>

            </div>

        </div>

    </section>

    <section class="profile-statistics"> </section>


    {{-- =========================================================
        MAIN GRID
    ========================================================== --}}

    <div class="profile-main-grid">


        {{-- =====================================================
            LEFT COLUMN
        ====================================================== --}}

        <div class="profile-main-column">


            {{-- =================================================
                PERSONAL INFORMATION
            ================================================== --}}

            <section class="profile-card">

                <div class="card-heading">

                    <div class="heading-icon blue">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>

                        <h3>
                            Informasi Pribadi
                        </h3>

                        <p>
                            Data identitas pribadi yang terdaftar pada sistem.
                        </p>

                    </div>

                </div>


                <div class="information-grid">


                    {{-- NIK --}}

                    <div class="information-item">

                        <span class="information-label">
                            NIK
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-id-card"></i>

                            <strong>
                                {{ $employee->nik ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- NAME --}}

                    <div class="information-item">

                        <span class="information-label">
                            Nama Lengkap
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-user"></i>

                            <strong>
                                {{ $employee->user->name ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- EMAIL --}}

                    <div class="information-item">

                        <span class="information-label">
                            Email
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-envelope"></i>

                            <strong>
                                {{ $employee->user->email ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- GENDER --}}

                    <div class="information-item">

                        <span class="information-label">
                            Jenis Kelamin
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-venus-mars"></i>

                            <strong>
                                {{ $employee->gender ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- BIRTH DATE --}}

                    <div class="information-item">

                        <span class="information-label">
                            Tanggal Lahir
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-cake-candles"></i>

                            <strong>

                                {{ optional(
                                    $employee->birth_date
                                )->translatedFormat('d F Y') ?? '-' }}

                            </strong>

                        </div>

                    </div>


                    {{-- ADDRESS --}}

                    <div class="information-item information-full">

                        <span class="information-label">
                            Alamat
                        </span>

                        <div class="address-box">

                            <i class="fa-solid fa-location-dot"></i>

                            <span>

                                @if($employee->address)

                                    {{ $employee->address }}

                                @else

                                    <span class="empty-value">
                                        Belum ada alamat yang tersedia.
                                    </span>

                                @endif

                            </span>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                EMPLOYMENT INFORMATION
            ================================================== --}}

            <section class="profile-card">

                <div class="card-heading">

                    <div class="heading-icon purple">
                        <i class="fa-solid fa-building"></i>
                    </div>

                    <div>

                        <h3>
                            Informasi Kepegawaian
                        </h3>

                        <p>
                            Informasi posisi dan struktur organisasi.
                        </p>

                    </div>

                </div>


                <div class="information-grid">


                    {{-- DEPARTMENT --}}

                    <div class="information-item">

                        <span class="information-label">
                            Department
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-building"></i>

                            <strong>
                                {{ $employee->department->name ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- POSITION --}}

                    <div class="information-item">

                        <span class="information-label">
                            Jabatan
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-briefcase"></i>

                            <strong>
                                {{ $employee->position->name ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- MANAGER --}}

                    <div class="information-item">

                        <span class="information-label">
                            Manager
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-user-tie"></i>

                            <strong>
                                {{ $employee->manager_name ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- JOIN DATE --}}

                    <div class="information-item">

                        <span class="information-label">
                            Tanggal Bergabung
                        </span>

                        <div class="information-value">

                            <i class="fa-solid fa-calendar-check"></i>

                            <strong>

                                {{ optional(
                                    $employee->join_date
                                )->translatedFormat('d F Y') ?? '-' }}

                            </strong>

                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div class="information-item information-full">

                        <span class="information-label">
                            Status Kepegawaian
                        </span>

                        <div>

                            @if(
                                strtolower(
                                    $employee->status ?? ''
                                ) === 'active'
                            )

                                <span class="employment-status active">

                                    <span class="employment-status-dot"></span>

                                    Active

                                </span>

                            @else

                                <span class="employment-status inactive">

                                    <span class="employment-status-dot"></span>

                                    {{ $employee->status ?? 'Inactive' }}

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </section>


        </div>


        {{-- =====================================================
            RIGHT COLUMN
        ====================================================== --}}

        <aside class="profile-side-column">


            {{-- =================================================
                LEAVE PROGRESS
            ================================================== --}}

            <section class="profile-card leave-progress-card">

                <div class="card-heading">

                    <div class="heading-icon green">

                        <i class="fa-solid fa-chart-pie"></i>

                    </div>

                    <div>

                        <h3>
                            Progress Cuti
                        </h3>

                        <p>
                            Ringkasan penggunaan hak cuti.
                        </p>

                    </div>

                </div>


                @php

                    $approvedValue = (int) ($approved ?? 0);

                    $remainingValue = (int) ($remainingLeave ?? 0);

                    $totalLeave =
                        max(
                            $approvedValue + $remainingValue,
                            1
                        );

                    $leavePercentage =
                        min(
                            100,
                            round(
                                ($approvedValue / $totalLeave) * 100
                            )
                        );

                @endphp


                <div class="leave-progress-content">


                    {{-- CIRCLE --}}

                    <div class="leave-circle">

                        <div class="leave-circle-inner">

                            <strong>
                                {{ $leavePercentage }}%
                            </strong>

                            <span>
                                Digunakan
                            </span>

                        </div>

                    </div>


                    <div class="leave-summary">

                        <strong>
                            Penggunaan Hak Cuti
                        </strong>

                        <p>

                            Anda telah menggunakan

                            <b>
                                {{ $approvedValue }} hari
                            </b>

                            dari total

                            <b>
                                {{ $totalLeave }} hari
                            </b>

                            hak cuti.

                        </p>

                    </div>


                    {{-- PROGRESS BAR --}}

                    <div class="leave-progress-bar">

                        <div
                            class="leave-progress-fill"
                            style="width: {{ $leavePercentage }}%;"
                        ></div>

                    </div>


                    {{-- PROGRESS LEGEND --}}

                    <div class="leave-legend">

                        <div>

                            <span class="legend-dot used"></span>

                            <span>
                                Digunakan
                            </span>

                            <strong>
                                {{ $approvedValue }}
                            </strong>

                        </div>


                        <div>

                            <span class="legend-dot remaining"></span>

                            <span>
                                Tersedia
                            </span>

                            <strong>
                                {{ $remainingValue }}
                            </strong>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
                ACCOUNT SECURITY
            ================================================== --}}

            <section class="profile-card security-card">

                <div class="security-header">

                    <div class="security-icon">

                        <i class="fa-solid fa-shield-halved"></i>

                    </div>

                    <div>

                        <h3>
                            Keamanan Akun
                        </h3>

                        <p>
                            Lindungi akses akun SIPCUTI Anda.
                        </p>

                    </div>

                </div>


                <div class="security-status">

                    <div class="security-status-icon">

                        <i class="fa-solid fa-check"></i>

                    </div>

                    <div>

                        <strong>
                            Akun Terlindungi
                        </strong>

                        <span>
                            Status akun saat ini aktif.
                        </span>

                    </div>

                </div>


                <div class="security-info">

                    <div class="security-info-row">

                        <span>
                            <i class="fa-solid fa-envelope"></i>
                            Email akun
                        </span>

                        <strong>
                            {{ $employee->user->email ?? '-' }}
                        </strong>

                    </div>


                    <div class="security-info-row">

                        <span>
                            <i class="fa-solid fa-user-shield"></i>
                            Hak akses
                        </span>

                        <strong>
                            HRD
                        </strong>

                    </div>

                </div>

            </section>


            {{-- =================================================
                QUICK INFO
            ================================================== --}}

            <section class="profile-card quick-info-card">

                <div class="quick-info-icon">

                    <i class="fa-solid fa-circle-info"></i>

                </div>

                <div>

                    <h3>
                        Informasi
                    </h3>

                    <p>
                        Jika terdapat perubahan data pribadi atau kepegawaian,
                        silakan hubungi administrator sistem atau pihak HRD.
                    </p>

                </div>

            </section>


        </aside>

    </div>

</div>

@endsection