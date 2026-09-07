@extends('layouts.app')

@vite([
'resources/css/karyawan/dashboard.css',
])

@section('content')

<div class="dashboard">

    <!-- ==========================
            HERO SECTION
    =========================== -->

    <section class="hero">

        <div class="hero-content">

            <div>

                <span class="hero-badge">
                    Employee Dashboard
                </span>

                <h1>
                    Selamat Datang,
                    {{ Auth::user()->name }}
                </h1>

                <p>

                    Selamat datang di Sistem Informasi Pengajuan Cuti.
                    Kelola pengajuan cuti Anda dengan mudah, pantau status
                    persetujuan, dan lihat riwayat cuti kapan saja.

                </p>

            </div>

            <div class="hero-avatar">

                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=F97316&color=fff&size=150"
                    alt="Employee">

            </div>

        </div>

    </section>

    <!-- ==========================
            STATISTICS
=========================== -->

<div class="leave-summary">

    {{-- Total Hak Cuti --}}
    <div class="summary-card">

        <div class="summary-icon summary-orange">
            <img width="40" height="40"
                src="https://img.icons8.com/ios-filled/50/FFFFFF/planner.png"
                alt="Total Hak Cuti">
        </div>

        <div class="summary-content">

            <span class="summary-title">
                Total Hak Cuti
            </span>

            <h2>{{ $totalLeave }}</h2>

            <small>
                Total quota cuti tahun {{ now()->year }}
            </small>

            <div class="summary-progress">
                <div class="summary-progress-bar summary-orange-bar"
                    style="width:100%">
                </div>
            </div>

        </div>

    </div>


    {{-- Diajukan --}}
    <div class="summary-card">

        <div class="summary-icon summary-yellow">
            <img width="40" height="40"
                src="https://img.icons8.com/ios-filled/50/FFFFFF/paper-plane.png"
                alt="Diajukan">
        </div>

        <div class="summary-content">

            <span class="summary-title">
                Diajukan
            </span>

            <h2>{{ $pending }}</h2>

            <small>
                Pengajuan cuti menunggu persetujuan
            </small>

        </div>

    </div>


    {{-- Disetujui --}}
    <div class="summary-card">

        <div class="summary-icon summary-blue">
            <img width="40" height="40"
                src="https://img.icons8.com/ios-filled/50/FFFFFF/checked--v1.png"
                alt="Approved">
        </div>

        <div class="summary-content">

            <span class="summary-title">
                Disetujui
            </span>

            <h2>{{ $approved }}</h2>

            <small>
                Pengajuan cuti disetujui
            </small>

        </div>

    </div>


    {{-- Sisa Cuti --}}
    <div class="summary-card">

        <div class="summary-icon summary-green">
            <img width="40" height="40"
                src="https://img.icons8.com/ios-filled/50/FFFFFF/today.png"
                alt="Remaining">
        </div>

        <div class="summary-content">

            <span class="summary-title">
                Sisa Cuti
            </span>

            <h2>{{ $remainingLeave }}</h2>

            <small>
                Hari cuti tersedia
            </small>

            <div class="summary-progress">

                @php
                    $leavePercentage = $totalLeave > 0
                        ? min(($remainingLeave / $totalLeave) * 100, 100)
                        : 0;
                @endphp

                <div class="summary-progress-bar"
                    style="width:{{ $leavePercentage }}%">
                </div>

            </div>

        </div>

    </div>

</div>

    <!-- ==========================
            QUICK MENU
    =========================== -->

    <section class="quick-menu">

        <div class="section-header">

            <h2>

                Menu Cepat

            </h2>

        </div>

        <div class="quick-grid">

            <a href="{{ route('employee.leave.create') }}">

                <div class="quick-card">

                    <div class="quick-icon">

                        <img width="60" height="60" src="https://img.icons8.com/fluency-systems-regular/48/FFFFFF/clipboard-approve--v1.png" alt="clipboard-approve--v1"/>

                    </div>

                    <h3>

                        Pengajuan Cuti

                    </h3>

                    <p>

                        Buat pengajuan cuti baru.

                    </p>

                </div>

            </a>

            <a href="{{ route('employee.leave.index') }}">

                <div class="quick-card">

                    <div class="quick-icon">

                        <img width="60" height="60" src="https://img.icons8.com/ios/50/FFFFFF/order-history.png" alt="order-history"/>

                    </div>

                    <h3>

                        Riwayat Cuti

                    </h3>

                    <p>

                        Lihat seluruh pengajuan.

                    </p>

                </div>

            </a>

            <a href="{{ route('employee.profile') }}">

                <div class="quick-card">

                    <div class="quick-icon">

                        <img width="60" height="60" src="https://img.icons8.com/external-those-icons-fill-those-icons/24/FFFFFF/external-Edit-user-actions-those-icons-fill-those-icons.png" alt="external-Edit-user-actions-those-icons-fill-those-icons"/>

                    </div>

                    <h3>

                        Profile

                    </h3>

                    <p>

                        Kelola data akun.

                    </p>

                </div>

            </a>

        </div>

    </section>



    <!-- ==========================
            CONTENT GRID
    =========================== -->

    <section class="dashboard-grid">

        <!-- LEFT -->

        <div class="left-panel">

            <div class="card">

                <div class="card-header">

                    <h2>

                        Riwayat Pengajuan Terakhir

                    </h2>

                </div>

                <div class="history-table">

                    <table class="leave-table">
                
                        <thead>
                
                            <tr>
                
                                <th width="70">No</th>
                
                                <th>No Request</th>
                
                                <th>Tanggal Cuti</th>
                
                                <th width="160">Status</th>
                
                            </tr>
                
                        </thead>
                
                        <tbody>
                
                        @forelse($leaveRequests as $leave)
                
                            <tr>
                
                                <td data-label="No">
                
                                    <div class="ticket-number">
                
                                        {{ $loop->iteration }}
                
                                    </div>
                
                                </td>
                
                                <td data-label="Nomor Request">
                
                                    <div class="ticket-request">
                
                                        <strong>
                
                                            {{ $leave->request_number }}
                
                                        </strong>
                
                                        <small>
                
                                            Leave Request
                
                                        </small>
                
                                    </div>
                
                                </td>
                
                                <td data-label="Periode">
                
                                    <div class="ticket-date">
                
                                        <strong>
                
                                            {{ $leave->start_date->format('d M Y') }}
                
                                        </strong>
                
                                        <span>
                
                                            sampai
                
                                        </span>
                
                                        <strong>
                
                                            {{ $leave->end_date->format('d M Y') }}
                
                                        </strong>
                
                                    </div>
                
                                </td>
                
                                <td data-label="Status">
                
                                    @switch($leave->status)
                
                                        @case('Pending')
                
                                            <span class="badge warning">
                
                                                <i class="fa-solid fa-clock"></i>
                
                                                Pending
                
                                            </span>
                
                                        @break
                
                                        @case('Approved')
                
                                            <span class="badge success">
                
                                                <i class="fa-solid fa-circle-check"></i>
                
                                                Approved
                
                                            </span>
                
                                        @break
                
                                        @case('Rejected')
                
                                            <span class="badge danger">
                
                                                <i class="fa-solid fa-circle-xmark"></i>
                
                                                Rejected
                
                                            </span>
                
                                        @break
                
                                        @default
                
                                            <span class="badge">
                
                                                {{ $leave->status }}
                
                                            </span>
                
                                    @endswitch
                
                                </td>
                
                            </tr>
                
                        @empty
                
                            <tr>
                
                                <td colspan="4">
                
                                    <div class="empty-state">
                
                                        <img
                                            src="{{ asset('assets/images/empty.png') }}"
                                            alt="Empty">
                
                                        <h4>
                
                                            Belum Ada Pengajuan Cuti
                
                                        </h4>
                
                                        <p>
                
                                            Silakan buat pengajuan cuti pertama Anda untuk mulai menggunakan sistem.
                
                                        </p>
                
                                    </div>
                
                                </td>
                
                            </tr>
                
                        @endforelse
                
                        </tbody>
                
                    </table>
                
                </div>  

            </div>

        </div>



        <!-- RIGHT -->

        <div class="right-panel">

            <div class="card">

                <div class="card-header">

                    <h2>

                        Pengumuman

                    </h2>

                </div>

                <div class="announcement">

                    <strong>

                        HRD

                    </strong>

                    <p>

                        Pengajuan cuti minimal dilakukan
                        H-7 sebelum tanggal pelaksanaan cuti.

                    </p>

                </div>

            </div>

            <div class="card">

    <div class="card-header">

        <h2>
            Progress Hak Cuti
        </h2>

    </div>

    @php

        $used = (int) $usedLeave;

        $total = max((int) $totalLeave, 1);

        $percentage = min(
            ($used / $total) * 100,
            100
        );

    @endphp

    <div class="progress-box">

        <div class="progress-bar">

            <div
                class="progress-fill"
                style="width:{{ $percentage }}%">
            </div>

        </div>

        <span>
            {{ $usedLeave }} / {{ $totalLeave }}
        </span>

    </div>

</div>

        </div>

    </section>

</div>

@endsection