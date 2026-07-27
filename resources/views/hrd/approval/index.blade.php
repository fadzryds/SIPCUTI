@extends('hrd.layouts.app')

@section('title','Approval Cuti')

@section('content')

<div class="hrd-approval-page">

    {{-- ========================================================= --}}
    {{-- HERO --}}
    {{-- ========================================================= --}}

    <section class="approval-hero">

        <div class="approval-hero-left">

            <span class="hero-badge">

                <i class="fa-solid fa-file-signature"></i>

                Human Resource Department

            </span>

            <h1>

                Approval Pengajuan Cuti

            </h1>

            <p>

                Kelola seluruh pengajuan cuti yang telah disetujui Manager
                sebelum diproses menjadi keputusan final.

            </p>

        </div>

        <div class="approval-hero-right">

            <div class="hero-icon">

                <i class="fa-solid fa-user-check"></i>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <section class="approval-summary">

        <div class="summary-card pending">

            <div class="summary-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div>

                <small>Pending</small>

                <h2>{{ $pending }}</h2>

                <span>Menunggu Approval</span>

            </div>

        </div>

        <div class="summary-card approved">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>

                <small>Approved</small>

                <h2>{{ $approved }}</h2>

                <span>Sudah Disetujui</span>

            </div>

        </div>

        <div class="summary-card rejected">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>

            <div>

                <small>Rejected</small>

                <h2>{{ $rejected }}</h2>

                <span>Ditolak</span>

            </div>

        </div>

        <div class="summary-card total">

            <div class="summary-icon">

                <i class="fa-solid fa-file-lines"></i>

            </div>

            <div>

                <small>Total</small>

                <h2>{{ $leaveRequests->total() }}</h2>

                <span>Seluruh Pengajuan</span>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <section class="approval-toolbar">

        <form method="GET" class="search-form">

            <div class="search-box">

                <i class="fa-solid fa-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau nomor pengajuan...">

            </div>

        </form>

        <form method="GET">

            <select
                name="status"
                onchange="this.form.submit()">

                <option value="">Semua Status</option>

                <option
                    value="Pending"
                    @selected(request('status')=='Pending')>

                    Pending

                </option>

                <option
                    value="Approved"
                    @selected(request('status')=='Approved')>

                    Approved

                </option>

                <option
                    value="Rejected"
                    @selected(request('status')=='Rejected')>

                    Rejected

                </option>

            </select>

        </form>

    </section>


    {{-- ========================================================= --}}
    {{-- TABLE --}}
    {{-- ========================================================= --}}

    <section class="approval-table-card">

        <div class="table-header">

            <div>

                <h3>

                    Daftar Pengajuan

                </h3>

                <p>

                    Pengajuan cuti yang menunggu persetujuan HRD.

                </p>

            </div>

        </div>

        <div class="table-responsive">

            <table>

                <thead>

                <tr>

                    <th>No</th>

                    <th>Karyawan</th>

                    <th>Jenis Cuti</th>

                    <th>Periode</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

                </thead>

                <tbody>

                @forelse($leaveRequests as $leave)

                @php

                    $hrdApproval = $leave->approvals
                        ->where('approval_level', \App\Models\LeaveApproval::LEVEL_HRD)
                        ->first();

                @endphp

                <tr>

                    <td>

                        {{ $leaveRequests->firstItem() + $loop->index }}

                    </td>

                    <td>

                        <div class="employee-cell">

                            <div class="employee-avatar">

                                {{ strtoupper(substr($leave->employee->user->name,0,1)) }}

                            </div>

                            <div>

                                <strong>

                                    {{ $leave->employee->user->name }}

                                </strong>

                                <span>

                                    {{ $leave->employee->department->name }}

                                </span>

                            </div>

                        </div>

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

                            s/d {{ $leave->end_date->format('d M Y') }}

                        </small>

                    </td>

                    <td>

                        @if($hrdApproval)

                            <span class="status-badge {{ strtolower($hrdApproval->status) }}">

                                {{ $hrdApproval->status }}

                            </span>

                        @endif

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

                    <td colspan="6">

                        <div class="empty-state">

                            <i class="fa-solid fa-folder-open"></i>

                            <h3>

                                Tidak Ada Pengajuan

                            </h3>

                            <p>

                                Belum ada pengajuan cuti yang perlu diproses.

                            </p>

                        </div>

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </section>


    @if($leaveRequests->hasPages())

        <div class="pagination-wrapper">

            {{ $leaveRequests->links() }}

        </div>

    @endif

</div>

@endsection