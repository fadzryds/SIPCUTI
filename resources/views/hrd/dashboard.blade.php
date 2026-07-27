@extends('hrd.layouts.app')

@section('title','Dashboard')

@section('content')

<div class="hrd-dashboard">

    {{-- ========================================================= --}}
    {{-- HERO --}}
    {{-- ========================================================= --}}

    <section class="hrd-hero">

        <div class="hero-content">

            <span class="hero-label">

                <i class="fa-solid fa-user-shield"></i>

                Human Resource Department

            </span>

            <h1>

                Selamat Datang,

                {{ $hrd->user->name }}

            </h1>

            <p>

                Kelola seluruh proses approval cuti karyawan,
                lakukan validasi akhir pengajuan cuti,
                serta pantau statistik pengajuan secara real-time.

            </p>

            <a
                href="{{ route('hrd.approval.index') }}"
                class="hero-button">

                <i class="fa-solid fa-file-signature"></i>

                Approval Cuti

            </a>

        </div>

        <div class="hero-right">

            <div class="hero-avatar">

                {{ strtoupper(substr($hrd->user->name,0,1)) }}

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <section class="summary-grid">

        <div class="summary-card warning">

            <div class="summary-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div>

                <span>Pending Approval</span>

                <h2>

                    {{ $pending }}

                </h2>

                <small>

                    Menunggu persetujuan HRD

                </small>

            </div>

        </div>

        <div class="summary-card success">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>

                <span>Approved</span>

                <h2>

                    {{ $approved }}

                </h2>

                <small>

                    Disetujui HRD

                </small>

            </div>

        </div>

        <div class="summary-card danger">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>

            <div>

                <span>Rejected</span>

                <h2>

                    {{ $rejected }}

                </h2>

                <small>

                    Ditolak HRD

                </small>

            </div>

        </div>

        <div class="summary-card info">

            <div class="summary-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <div>

                <span>Total Karyawan</span>

                <h2>

                    {{ $totalEmployees }}

                </h2>

                <small>

                    Karyawan Aktif

                </small>

            </div>

        </div>

        <div class="summary-card primary">

            <div class="summary-icon">

                <i class="fa-solid fa-calendar-day"></i>

            </div>

            <div>

                <span>Cuti Hari Ini</span>

                <h2>

                    {{ $leaveToday }}

                </h2>

                <small>

                    Sedang Cuti

                </small>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- QUICK STATISTIC --}}
    {{-- ========================================================= --}}

    <section class="statistic-grid">

        <div class="statistic-card">

            <div class="statistic-header">

                <h3>

                    Ringkasan Approval

                </h3>

                <i class="fa-solid fa-chart-pie"></i>

            </div>

            <div class="progress-list">

                <div class="progress-item">

                    <div>

                        Pending

                    </div>

                    <strong>

                        {{ $pending }}

                    </strong>

                </div>

                <div class="progress-item">

                    <div>

                        Approved

                    </div>

                    <strong>

                        {{ $approved }}

                    </strong>

                </div>

                <div class="progress-item">

                    <div>

                        Rejected

                    </div>

                    <strong>

                        {{ $rejected }}

                    </strong>

                </div>

            </div>

        </div>

        <div class="statistic-card">

            <div class="statistic-header">

                <h3>

                    Informasi HRD

                </h3>

                <i class="fa-solid fa-address-card"></i>

            </div>

            <div class="info-list">

                <div>

                    <span>Nama</span>

                    <strong>{{ $hrd->user->name }}</strong>

                </div>

                <div>

                    <span>Department</span>

                    <strong>{{ $hrd->department->name }}</strong>

                </div>

                <div>

                    <span>Jabatan</span>

                    <strong>{{ $hrd->position->name }}</strong>

                </div>

                <div>

                    <span>Email</span>

                    <strong>{{ $hrd->user->email }}</strong>

                </div>

            </div>

        </div>

    </section>

        {{-- ========================================================= --}}
    {{-- APPROVAL TERBARU --}}
    {{-- ========================================================= --}}

    <section class="dashboard-table">

        <div class="table-header">

            <div>

                <h2>

                    Approval Terbaru

                </h2>

                <p>

                    Pengajuan cuti yang telah disetujui Manager dan masuk ke tahap HRD.

                </p>

            </div>

            <a
                href="{{ route('hrd.approval.index') }}"
                class="table-button">

                <i class="fa-solid fa-arrow-right"></i>

                Lihat Semua

            </a>

        </div>

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Karyawan</th>

                        <th>Department</th>

                        <th>Jenis Cuti</th>

                        <th>Tanggal</th>

                        <th>Status</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($recentApprovals as $leave)

                    @php

                        $approval = $leave->approvals
                            ->where('approval_level', \App\Models\LeaveApproval::LEVEL_HRD)
                            ->first();

                    @endphp

                    <tr>

                        <td>

                            {{ $loop->iteration }}

                        </td>

                        <td>

                            <div class="employee-info">

                                <div class="employee-avatar">

                                    {{ strtoupper(substr($leave->employee->user->name,0,1)) }}

                                </div>

                                <div>

                                    <strong>

                                        {{ $leave->employee->user->name }}

                                    </strong>

                                    <small>

                                        {{ $leave->employee->nik }}

                                    </small>

                                </div>

                            </div>

                        </td>

                        <td>

                            {{ $leave->employee->department->name }}

                        </td>

                        <td>

                            {{ $leave->leaveType->name }}

                        </td>

                        <td>

                            <strong>

                                {{ $leave->start_date->format('d M Y') }}

                            </strong>

                            <br>

                            <small>

                                s/d

                                {{ $leave->end_date->format('d M Y') }}

                            </small>

                        </td>

                        <td>

                            @switch(optional($approval)->status)

                                @case(\App\Models\LeaveApproval::STATUS_PENDING)

                                    <span class="badge warning">

                                        Pending

                                    </span>

                                @break

                                @case(\App\Models\LeaveApproval::STATUS_APPROVED)

                                    <span class="badge success">

                                        Approved

                                    </span>

                                @break

                                @case(\App\Models\LeaveApproval::STATUS_REJECTED)

                                    <span class="badge danger">

                                        Rejected

                                    </span>

                                @break

                                @default

                                    <span class="badge waiting">

                                        Waiting

                                    </span>

                            @endswitch

                        </td>

                        <td>

                            <a

                                href="{{ route('hrd.approval.show',$leave->id) }}"

                                class="btn-detail">

                                <i class="fa-solid fa-eye"></i>

                                Detail

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7">

                            <div class="empty-state">

                                <i class="fa-solid fa-folder-open"></i>

                                <h3>

                                    Belum Ada Approval

                                </h3>

                                <p>

                                    Saat ini belum ada pengajuan cuti yang masuk ke tahap HRD.

                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection