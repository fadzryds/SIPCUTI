@extends('manager.layouts.app')

@section('title','Riwayat Approval')

@section('content')

<div class="history-page">

    {{-- ========================================================= --}}
    {{-- HERO --}}
    {{-- ========================================================= --}}

    <section class="history-hero">

        <div class="hero-left">

            <span class="hero-tag">

                <i class="fa-solid fa-clock-rotate-left"></i>

                Riwayat Approval

            </span>

            <h1>

                Riwayat Approval Manager

            </h1>

            <p>

                Seluruh pengajuan cuti yang pernah Anda proses akan ditampilkan
                pada halaman ini. Gunakan filter untuk mempermudah pencarian.

            </p>

        </div>

        <div class="hero-right">

            <div class="hero-icon">

                <i class="fa-solid fa-file-circle-check"></i>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <section class="history-summary">

        <div class="summary-card primary">

            <div class="summary-icon">

                <i class="fa-solid fa-list-check"></i>

            </div>

            <div>

                <small>Total Approval</small>

                <h2>{{ $total }}</h2>

                <span>Seluruh Approval</span>

            </div>

        </div>



        <div class="summary-card success">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>

                <small>Approved</small>

                <h2>{{ $approved }}</h2>

                <span>Disetujui</span>

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



        <div class="summary-card warning">

            <div class="summary-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div>

                <small>Pending</small>

                <h2>{{ $pending }}</h2>

                <span>Menunggu</span>

            </div>

        </div>

    </section>

        {{-- ========================================================= --}}
    {{-- SEARCH & FILTER --}}
    {{-- ========================================================= --}}

    <section class="history-toolbar">

        <form
            method="GET"
            action="{{ route('manager.history.index') }}"
            class="toolbar-form">

            {{-- Search --}}
            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama karyawan, NIK atau nomor pengajuan...">

            </div>

            {{-- Status --}}
            <div class="filter-group">

                <select name="status">

                    <option value="">
                        Semua Status
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

                    <option
                        value="Pending"
                        @selected(request('status')=='Pending')>

                        Pending

                    </option>

                </select>

            </div>

            {{-- Month --}}
            <div class="filter-group">

                <select name="month">

                    <option value="">
                        Semua Bulan
                    </option>

                    @foreach(range(1,12) as $month)

                        <option
                            value="{{ $month }}"
                            @selected(request('month')==$month)>

                            {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Year --}}
            <div class="filter-group">

                <select name="year">

                    <option value="">
                        Semua Tahun
                    </option>

                    @foreach(range(now()->year, now()->year-5) as $year)

                        <option
                            value="{{ $year }}"
                            @selected(request('year')==$year)>

                            {{ $year }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Button Filter --}}
            <button
                type="submit"
                class="btn-filter">

                <i class="fa-solid fa-filter"></i>

                Filter

            </button>

            {{-- Reset --}}
            <a
                href="{{ route('manager.history.index') }}"
                class="btn-reset">

                <i class="fa-solid fa-rotate-left"></i>

                Reset

            </a>

        </form>

    </section>

    {{-- ========================================================= --}}
{{-- HISTORY TABLE --}}
{{-- ========================================================= --}}

<section class="history-table-card">

    <div class="table-header">

        <div>

            <h3>

                Daftar Riwayat Approval

            </h3>

            <p>

                Seluruh pengajuan cuti yang pernah Anda proses.

            </p>

        </div>

    </div>

    <div class="table-responsive">

        <table>

            <thead>

                <tr>

                    <th>No</th>

                    <th>Karyawan</th>

                    <th>Nomor Request</th>

                    <th>Jenis Cuti</th>

                    <th>Periode</th>

                    <th>Tanggal Approval</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

            @forelse($histories as $leave)

            @php

                $approval = $leave->approvals
                    ->where('approval_level', \App\Models\LeaveApproval::LEVEL_MANAGER)
                    ->first();

            @endphp

            <tr>

                <td>

                    {{ $histories->firstItem() + $loop->index }}

                </td>

                <td>

                    <div class="employee-cell">

                        <div class="employee-avatar">

                            {{ strtoupper(substr($leave->employee->user->name,0,1)) }}

                        </div>

                        <div class="employee-info">

                            <strong>

                                {{ $leave->employee->user->name }}

                            </strong>

                            <small>

                                {{ $leave->employee->department->name }}

                            </small>

                        </div>

                    </div>

                </td>

                <td>

                    <span class="request-number">

                        {{ $leave->request_number }}

                    </span>

                </td>

                <td>

                    {{ $leave->leaveType->name }}

                </td>

                <td>

                    <div class="period">

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

                    @if($approval?->approved_at)

                        {{ $approval->approved_at->format('d M Y') }}

                        <br>

                        <small>

                            {{ $approval->approved_at->format('H:i') }}

                        </small>

                    @else

                        -

                    @endif

                </td>

                <td>

                    @switch($approval?->status)

                        @case(\App\Models\LeaveApproval::STATUS_APPROVED)

                        <span class="status-badge approved">

                            <i class="fa-solid fa-circle-check"></i>

                            Approved

                        </span>

                        @break

                        @case(\App\Models\LeaveApproval::STATUS_REJECTED)

                        <span class="status-badge rejected">

                            <i class="fa-solid fa-circle-xmark"></i>

                            Rejected

                        </span>

                        @break

                        @case(\App\Models\LeaveApproval::STATUS_PENDING)

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

                <td colspan="8">

                    <div class="history-empty">

                        <div class="empty-icon">

                            <i class="fa-solid fa-folder-open"></i>

                        </div>

                        <h3>

                            Belum Ada Riwayat Approval

                        </h3>

                        <p>

                            Semua approval yang telah diproses akan tampil pada halaman ini.

                        </p>

                    </div>

                </td>

            </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</section>



{{-- ========================================================= --}}
{{-- PAGINATION --}}
{{-- ========================================================= --}}

@if($histories->hasPages())

<div class="pagination-wrapper">

    {{ $histories->links() }}

</div>

@endif

</div>

@endsection

