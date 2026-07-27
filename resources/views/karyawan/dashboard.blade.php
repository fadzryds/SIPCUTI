@extends('layouts.app')

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
    
                <small>Hak cuti per tahun</small>
    
                <div class="summary-progress">
                    <div class="summary-progress-bar summary-orange-bar" style="width:100%"></div>
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
    
                <h2>{{ $submitted }}</h2>
    
                <small>Total pengajuan cuti</small>
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
    
                <small>Pengajuan disetujui</small>
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
    
                <small>Hari cuti tersedia</small>
    
                <div class="summary-progress">
                    <div class="summary-progress-bar" style="width:{{ ($remainingLeave / max($totalLeave,1))*100 }}%"></div>
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

                        <img width="60" height="60" src="https://img.icons8.com/fluency-systems-regular/48/clipboard-approve--v1.png" alt="clipboard-approve--v1"/>

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

                        <img width="60" height="60" src="https://img.icons8.com/ios/50/order-history.png" alt="order-history"/>

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

                        <img width="80" height="80" src="https://img.icons8.com/dotty/80/edit-user-male.png" alt="edit-user-male"/>

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

                <table>

                    <thead>
        
                    <tr>
        
                        <th>No</th>
        
                        <th>No Request</th>
        
                        <th>Tanggal</th>
        
        
                        <th>Status</th>
        
                    </tr>
        
                    </thead>
        
                    <tbody>
        
                    @forelse($leaveRequests as $leave)
        
                        <tr>
        
                            <td>{{ $loop->iteration }}</td>
        
                            <td>
        
                                {{ $leave->request_number }}
        
                            </td>
        
                            <td>
        
                                {{ $leave->start_date->format('d M Y') }}
        
                                -
        
                                {{ $leave->end_date->format('d M Y') }}
        
                            </td>
        
                            <td>
        
                                @switch($leave->status)
        
                                    @case('Pending')
        
                                        <span class="badge warning">
        
                                            Pending
        
                                        </span>
        
                                        @break
        
                                    @case('Approved')
        
                                        <span class="badge success">
        
                                            Approved
        
                                        </span>
        
                                        @break
        
                                    @case('Rejected')
        
                                        <span class="badge danger">
        
                                            Rejected
        
                                        </span>
        
                                        @break
        
                                    @default
        
                                        <span class="badge">
        
                                            {{ $leave->status }}
        
                                        </span>
        
                                @endswitch
        
                            </td>
        
                            <td>
        
                            </td>
        
                        </tr>
        
                    @empty
        
                        <tr>
        
                            <td colspan="7">
        
                                <div class="empty-state">
        
                                    <img
        
                                        src="{{ asset('assets/images/empty.png') }}"
        
                                        width="150">
        
                                    <h4>
        
                                        Belum ada pengajuan cuti
        
                                    </h4>
        
                                    <p>
        
                                        Silakan buat pengajuan cuti pertama Anda.
        
                                    </p>
        
                                </div>
        
                            </td>
        
                        </tr>
        
                    @endforelse
        
                    </tbody>
        
                </table>        

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

                <div class="progress-box">

                    <div class="progress-bar">

                        <div
                            class="progress-fill"
                            style="width:75%">
                        </div>

                    </div>

                    <span>

                        9 / 12 Hari

                    </span>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection