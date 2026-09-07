@extends('hrd.layouts.app')

@section('title', 'Dashboard HRD')

@section('page-title', 'Dashboard HRD')

@section('content')

<div class="hrd-dashboard">

    {{-- =====================================================
         WELCOME
    ====================================================== --}}

    <section class="hrd-welcome">

        <div>

            <span>
                HUMAN RESOURCE DEVELOPMENT
            </span>

            <h2>
                Selamat Datang, {{ auth()->user()->name }}
            </h2>

            <p>
                Pantau data karyawan dan aktivitas pengajuan
                cuti perusahaan dari dashboard HRD.
            </p>

        </div>

        <div class="hrd-welcome-icon">

            <i class="fas fa-users-gear"></i>

        </div>

    </section>


    {{-- =====================================================
         EMPLOYEE STATISTICS
    ====================================================== --}}

    <div class="hrd-stat-grid">

        <div class="hrd-stat-card">

            <div class="hrd-stat-icon">
                <i class="fas fa-users"></i>
            </div>

            <div>

                <span>
                    Total Karyawan
                </span>

                <strong>
                    {{ $totalEmployees }}
                </strong>

                <small>
                    Seluruh karyawan
                </small>

            </div>

        </div>


        <div class="hrd-stat-card">

            <div class="hrd-stat-icon">
                <i class="fas fa-user-check"></i>
            </div>

            <div>

                <span>
                    Karyawan Aktif
                </span>

                <strong>
                    {{ $activeEmployees }}
                </strong>

                <small>
                    Status aktif
                </small>

            </div>

        </div>


        <div class="hrd-stat-card">

            <div class="hrd-stat-icon">
                <i class="fas fa-building"></i>
            </div>

            <div>

                <span>
                    Department
                </span>

                <strong>
                    {{ $totalDepartments }}
                </strong>

                <small>
                    Total department
                </small>

            </div>

        </div>


        <div class="hrd-stat-card">

            <div class="hrd-stat-icon">
                <i class="fas fa-briefcase"></i>
            </div>

            <div>

                <span>
                    Position
                </span>

                <strong>
                    {{ $totalPositions }}
                </strong>

                <small>
                    Total posisi
                </small>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LEAVE STATISTICS
    ====================================================== --}}

    <div class="hrd-section-title">

        <div>

            <h3>
                Statistik Pengajuan Cuti
            </h3>

            <span>
                Ringkasan tahun {{ $currentYear }}
            </span>

        </div>

        <a href="#">
            Lihat Semua
            <i class="fas fa-arrow-right"></i>
        </a>

    </div>


    <div class="hrd-stat-grid">

        <div class="hrd-stat-card leave-total">

            <div class="hrd-stat-icon">
                <i class="fas fa-calendar-days"></i>
            </div>

            <div>

                <span>
                    Total Pengajuan
                </span>

                <strong>
                    {{ $totalLeaveRequests }}
                </strong>

                <small>
                    Seluruh pengajuan
                </small>

            </div>

        </div>


        <div class="hrd-stat-card leave-pending">

            <div class="hrd-stat-icon">
                <i class="fas fa-clock"></i>
            </div>

            <div>

                <span>
                    Pending
                </span>

                <strong>
                    {{ $pendingLeaveRequests }}
                </strong>

                <small>
                    Menunggu proses
                </small>

            </div>

        </div>


        <div class="hrd-stat-card leave-approved">

            <div class="hrd-stat-icon">
                <i class="fas fa-circle-check"></i>
            </div>

            <div>

                <span>
                    Approved
                </span>

                <strong>
                    {{ $approvedLeaveRequests }}
                </strong>

                <small>
                    Pengajuan disetujui
                </small>

            </div>

        </div>


        <div class="hrd-stat-card leave-rejected">

            <div class="hrd-stat-icon">
                <i class="fas fa-circle-xmark"></i>
            </div>

            <div>

                <span>
                    Rejected
                </span>

                <strong>
                    {{ $rejectedLeaveRequests }}
                </strong>

                <small>
                    Pengajuan ditolak
                </small>

            </div>

        </div>

    </div>

    {{-- =====================================================
         TWO COLUMNS
    ====================================================== --}}

    <div class="hrd-dashboard-columns">


        {{-- RECENT LEAVE --}}
        <div class="hrd-panel">

            <div class="hrd-panel-header">

                <div>

                    <h3>
                        Pengajuan Cuti Terbaru
                    </h3>

                    <span>
                        8 pengajuan terakhir
                    </span>

                </div>

                <a href="#">
                    Semua
                </a>

            </div>


            <div class="hrd-leave-list">

                @forelse(
                    $recentLeaveRequests
                    as $leave
                )

                    <div class="hrd-leave-item">

                        <div class="hrd-leave-avatar">

                            <i class="fas fa-user"></i>

                        </div>


                        <div class="hrd-leave-info">

                            <strong>

                                {{ $leave->employee?->user?->name
                                    ?? 'Karyawan' }}

                            </strong>

                            <span>

                                {{ $leave->leaveType?->name
                                    ?? 'Jenis Cuti' }}

                                ·

                                {{ $leave->total_days }}
                                hari

                            </span>

                        </div>


                        @switch($leave->status)

                            @case('Pending')

                                <span class="hrd-badge warning">
                                    Pending
                                </span>

                            @break


                            @case('Approved')

                                <span class="hrd-badge success">
                                    Approved
                                </span>

                            @break


                            @case('Rejected')

                                <span class="hrd-badge danger">
                                    Rejected
                                </span>

                            @break


                            @default

                                <span class="hrd-badge">
                                    {{ $leave->status }}
                                </span>

                        @endswitch

                    </div>

                @empty

                    <div class="hrd-empty">

                        <i class="fas fa-calendar-xmark"></i>

                        <p>
                            Belum ada pengajuan cuti.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- RECENT EMPLOYEES --}}
        <div class="hrd-panel">

            <div class="hrd-panel-header">

                <div>

                    <h3>
                        Karyawan Terbaru
                    </h3>

                    <span>
                        5 data terakhir
                    </span>

                </div>

                <a href="{{ route('hrd.employees.index') }}"
                    class="btn btn-outline-primary"
                >
                    Semua
                </a>

            </div>


            <div class="hrd-employee-list">

                @forelse(
                    $recentEmployees
                    as $employee
                )

                    <div class="hrd-employee-item">

                        <div class="hrd-employee-avatar">

                            <i class="fas fa-user"></i>

                        </div>


                        <div>

                            <strong>

                                {{ $employee->user?->name
                                    ?? 'Karyawan' }}

                            </strong>

                            <span>

                                {{ $employee->position?->name
                                    ?? '-' }}

                                ·

                                {{ $employee->department?->name
                                    ?? '-' }}

                            </span>

                        </div>

                    </div>

                @empty

                    <div class="hrd-empty">

                        <i class="fas fa-users-slash"></i>

                        <p>
                            Belum ada data karyawan.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection