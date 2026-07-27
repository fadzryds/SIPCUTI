@extends('manager.layouts.app')

@section('title','Approval Cuti')

@section('content')

<div class="approval-page">

    {{-- ===================================================== --}}
    {{-- HERO --}}
    {{-- ===================================================== --}}

    <section class="approval-hero">

        <div class="hero-left">

            <span class="hero-tag">
                <i class="fa-solid fa-file-signature"></i>
                Approval Manager
            </span>

            <h1>Approval Pengajuan Cuti</h1>

            <p>
                Kelola seluruh pengajuan cuti karyawan yang berada di bawah
                tanggung jawab Anda sebelum diteruskan ke HRD.
            </p>

        </div>

        <div class="hero-right">

            <div class="hero-icon">

                <i class="fa-solid fa-user-check"></i>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- SUMMARY --}}
    {{-- ===================================================== --}}

    <section class="approval-summary">

        <div class="summary-card warning">

            <div class="summary-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div>

                <small>Pending</small>

                <h2>{{ $pending }}</h2>

                <span>Menunggu Approval</span>

            </div>

        </div>

        <div class="summary-card success">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>

                <small>Approved</small>

                <h2>{{ $approved }}</h2>

                <span>Telah Disetujui</span>

            </div>

        </div>

        <div class="summary-card danger">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>

            <div>

                <small>Rejected</small>

                <h2>{{ $rejected }}</h2>

                <span>Ditolak</span>

            </div>

        </div>

        <div class="summary-card info">

            <div class="summary-icon">

                <i class="fa-solid fa-file-lines"></i>

            </div>

            <div>

                <small>Total Request</small>

                <h2>{{ $leaveRequests->total() }}</h2>

                <span>Seluruh Pengajuan</span>

            </div>

        </div>

    </section>

    {{-- ===================================================== --}}
    {{-- TOOLBAR --}}
    {{-- ===================================================== --}}

    <section class="approval-toolbar">

        <form method="GET">

            <div class="search-box">

                <i class="fa-solid fa-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama karyawan atau nomor pengajuan...">

            </div>

        </form>

        <form method="GET">

            <select
                name="status"
                onchange="this.form.submit()">

                <option value="">

                    Semua Status

                </option>

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

    {{-- ===================================================== --}}
    {{-- TABLE --}}
    {{-- ===================================================== --}}

    <section class="approval-table-card">

        <div class="table-header">

            <div>

                <h3>

                    Daftar Pengajuan Cuti

                </h3>

                <p>

                    Pengajuan yang harus diproses Manager.

                </p>

            </div>

        </div>

        <div class="table-responsive">

            <table>

                <thead>

                <tr>

                    <th>No</th>

                    <th>Karyawan</th>

                    <th>Request</th>

                    <th>Jenis Cuti</th>

                    <th>Tanggal</th>

                    <th>Status</th>

                    <th width="120">

                        Aksi

                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($leaveRequests as $leave)

                    @php

                        $approval = $leave->approvals
                            ->where('approval_level', \App\Models\LeaveApproval::LEVEL_MANAGER)
                            ->first();

                    @endphp

                    <tr>

                        <td>

                            {{ $leaveRequests->firstItem()+$loop->index }}

                        </td>

                        <td>

                            <div class="employee-cell">

                                <div class="employee-avatar">

                                    {{ strtoupper(substr($leave->employee->user->name,0,1)) }}

                                </div>

                                <div class="employee-detail">

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

                            <div class="request-number">

                                {{ $leave->request_number }}

                            </div>

                        </td>

                        <td>

                            <div class="leave-type">

                                {{ $leave->leaveType->name }}

                            </div>

                        </td>

                        <td>

                            <div class="leave-date">

                                <strong>

                                    {{ $leave->start_date->format('d M Y') }}

                                </strong>

                                <small>

                                    s/d

                                    {{ $leave->end_date->format('d M Y') }}

                                </small>

                            </div>

                        </td>

                        <td>

                            @switch(optional($approval)->status)

                                @case('Approved')

                                    <span class="status-badge approved">

                                        <i class="fa-solid fa-circle-check"></i>

                                        Approved

                                    </span>

                                @break

                                @case('Rejected')

                                    <span class="status-badge rejected">

                                        <i class="fa-solid fa-circle-xmark"></i>

                                        Rejected

                                    </span>

                                @break

                                @case('Pending')

                                    <span class="status-badge pending">

                                        <i class="fa-solid fa-clock"></i>

                                        Pending

                                    </span>

                                @break

                                @default

                                    <span class="status-badge waiting">

                                        <i class="fa-solid fa-hourglass-half"></i>

                                        Waiting

                                    </span>

                            @endswitch

                        </td>

                        <td>

                            <a
                                href="{{ route('manager.approval.show',$leave->id) }}"
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

                                <img
                                    src="{{ asset('assets/images/empty.png') }}"
                                    alt="Empty">

                                <h3>

                                    Belum Ada Pengajuan

                                </h3>

                                <p>

                                    Saat ini tidak ada pengajuan cuti yang harus diproses.

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