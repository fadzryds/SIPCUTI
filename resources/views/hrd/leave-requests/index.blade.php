@extends('hrd.layouts.app')

@section('title', 'Monitoring Pengajuan Cuti')

@section('content')

<div class="hrd-page leave-request-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon leave-request-header-icon">
                <i class="fas fa-file-signature"></i>
            </div>

            <div>

                <span class="page-header-label">
                    PENGAJUAN CUTI
                </span>

                <h1>
                    Monitoring Pengajuan Cuti
                </h1>

                <p>
                    Pantau seluruh pengajuan cuti karyawan yang terdaftar
                    dalam sistem.
                </p>

            </div>

        </div>


        <div class="page-header-actions">

            <div class="monitoring-badge">

                <span class="monitoring-badge-dot"></span>

                Monitoring Only

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS ALERT
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>

            <div class="alert-content">

                <strong>
                    Berhasil
                </strong>

                <span>
                    {{ session('success') }}
                </span>

            </div>

            <button
                type="button"
                class="alert-close"
                onclick="this.parentElement.remove()"
            >
                &times;
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR ALERT
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-error">

            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <div class="alert-content">

                <strong>
                    Terjadi Kesalahan
                </strong>

                <span>
                    {{ session('error') }}
                </span>

            </div>

            <button
                type="button"
                class="alert-close"
                onclick="this.parentElement.remove()"
            >
                &times;
            </button>

        </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    <div class="leave-statistics">


        {{-- TOTAL --}}

        <div class="leave-stat-card">

            <div class="leave-stat-icon total">

                <i class="fas fa-file-lines"></i>

            </div>

            <div class="leave-stat-content">

                <span>
                    Total Pengajuan
                </span>

                <h2>
                    {{ $totalRequests }}
                </h2>

                <small>
                    Seluruh pengajuan cuti
                </small>

            </div>

        </div>


        {{-- PENDING --}}

        <div class="leave-stat-card">

            <div class="leave-stat-icon pending">

                <i class="fas fa-clock"></i>

            </div>

            <div class="leave-stat-content">

                <span>
                    Menunggu
                </span>

                <h2>
                    {{ $pendingRequests }}
                </h2>

                <small>
                    Menunggu proses approval
                </small>

            </div>

        </div>


        {{-- APPROVED --}}

        <div class="leave-stat-card">

            <div class="leave-stat-icon approved">

                <i class="fas fa-circle-check"></i>

            </div>

            <div class="leave-stat-content">

                <span>
                    Disetujui
                </span>

                <h2>
                    {{ $approvedRequests }}
                </h2>

                <small>
                    Pengajuan telah disetujui
                </small>

            </div>

        </div>


        {{-- REJECTED --}}

        <div class="leave-stat-card">

            <div class="leave-stat-icon rejected">

                <i class="fas fa-circle-xmark"></i>

            </div>

            <div class="leave-stat-content">

                <span>
                    Ditolak
                </span>

                <h2>
                    {{ $rejectedRequests }}
                </h2>

                <small>
                    Pengajuan ditolak
                </small>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TABLE CARD
    ========================================================== --}}

    <div class="leave-table-card">


        {{-- =====================================================
            TABLE HEADER
        ====================================================== --}}

        <div class="table-card-header">

            <div>

                <h2>
                    Daftar Pengajuan Cuti
                </h2>

                <span>
                    Monitoring seluruh pengajuan cuti karyawan.
                </span>

            </div>


            <div class="table-count">

                <i class="fas fa-database"></i>

                {{ $leaveRequests->total() }} Data

            </div>

        </div>


        {{-- =====================================================
            FILTER AREA
        ====================================================== --}}

        <div class="leave-filter-wrapper">

            <form
                method="GET"
                action="{{ route('hrd.leave-requests.index') }}"
                class="leave-filter-form"
            >


                {{-- SEARCH --}}

                <div class="leave-search">

                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nomor pengajuan, NIK, nama karyawan, atau jenis cuti..."
                    >

                    @if(request('search'))

                        <a
                            href="{{ route('hrd.leave-requests.index', request()->except('search', 'page')) }}"
                            class="search-clear"
                            title="Reset pencarian"
                        >

                            <i class="fas fa-times"></i>

                        </a>

                    @endif

                </div>


                {{-- STATUS --}}

                <div class="leave-filter">

                    <i class="fas fa-filter"></i>

                    <select
                        name="status"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="{{ \App\Models\LeaveRequest::STATUS_PENDING }}"
                            @selected(request('status') === \App\Models\LeaveRequest::STATUS_PENDING)
                        >
                            Pending
                        </option>

                        <option
                            value="{{ \App\Models\LeaveRequest::STATUS_APPROVED }}"
                            @selected(request('status') === \App\Models\LeaveRequest::STATUS_APPROVED)
                        >
                            Approved
                        </option>

                        <option
                            value="{{ \App\Models\LeaveRequest::STATUS_REJECTED }}"
                            @selected(request('status') === \App\Models\LeaveRequest::STATUS_REJECTED)
                        >
                            Rejected
                        </option>

                    </select>

                </div>


                {{-- SEARCH BUTTON --}}

                <button
                    type="submit"
                    class="btn-filter"
                >

                    <i class="fas fa-search"></i>

                    Cari

                </button>


                {{-- RESET --}}

                @if(request('search') || request('status'))

                    <a
                        href="{{ route('hrd.leave-requests.index') }}"
                        class="btn-reset"
                    >

                        <i class="fas fa-rotate-left"></i>

                        Reset

                    </a>

                @endif

            </form>

        </div>


        {{-- =====================================================
            TABLE
        ====================================================== --}}

        <div class="leave-table-wrapper">

            <table class="leave-table">

                <thead>

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Karyawan
                        </th>

                        <th>
                            Pengajuan
                        </th>

                        <th>
                            Jenis Cuti
                        </th>

                        <th>
                            Periode
                        </th>

                        <th>
                            Durasi
                        </th>

                        <th>
                            Status
                        </th>

                        <th width="125">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($leaveRequests as $leave)

                        <tr>


                            {{-- =================================================
                                NO
                            ================================================== --}}

                            <td>

                                <span class="row-number">

                                    {{ $leaveRequests->firstItem() + $loop->index }}

                                </span>

                            </td>


                            {{-- =================================================
                                EMPLOYEE
                            ================================================== --}}

                            <td>

                                <div class="leave-employee-info">

                                    <div class="leave-employee-avatar">

                                        <i class="fas fa-user"></i>

                                    </div>


                                    <div class="leave-employee-details">

                                        <strong>

                                            {{ $leave->employee?->user?->name ?? 'Tidak diketahui' }}

                                        </strong>

                                        <span>

                                            NIK:
                                            {{ $leave->employee?->nik ?? '-' }}

                                        </span>

                                        <small>

                                            {{ $leave->employee?->department?->name ?? 'Department tidak tersedia' }}

                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                REQUEST
                            ================================================== --}}

                            <td>

                                <div class="leave-request-number">

                                    <strong>
                                        {{ $leave->request_number }}
                                    </strong>

                                    @if($leave->submitted_at)

                                        <span>

                                            <i class="far fa-calendar"></i>

                                            {{ $leave->submitted_at->format('d M Y, H:i') }}

                                        </span>

                                    @else

                                        <span>

                                            <i class="far fa-calendar"></i>

                                            Belum disubmit

                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                LEAVE TYPE
                            ================================================== --}}

                            <td>

                                <div class="leave-type-info">

                                    <div class="leave-type-icon">

                                        <i class="fas fa-calendar-days"></i>

                                    </div>

                                    <div>

                                        <strong>

                                            {{ $leave->leaveType?->name ?? 'Jenis cuti tidak tersedia' }}

                                        </strong>

                                        <span>
                                            Pengajuan Cuti
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                PERIOD
                            ================================================== --}}

                            <td>

                                <div class="leave-period">

                                    <div class="leave-period-row">

                                        <i class="fas fa-calendar-day"></i>

                                        <span>
                                            {{ $leave->start_date?->format('d M Y') ?? '-' }}
                                        </span>

                                    </div>


                                    <div class="leave-period-arrow">

                                        <i class="fas fa-arrow-down"></i>

                                    </div>


                                    <div class="leave-period-row">

                                        <i class="fas fa-calendar-check"></i>

                                        <span>
                                            {{ $leave->end_date?->format('d M Y') ?? '-' }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                TOTAL DAYS
                            ================================================== --}}

                            <td>

                                <div class="leave-duration">

                                    <strong>
                                        {{ $leave->total_days }}
                                    </strong>

                                    <span>
                                        Hari
                                    </span>

                                </div>

                            </td>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}

                            <td>

                                @if($leave->status === \App\Models\LeaveRequest::STATUS_APPROVED)

                                    <span class="leave-status-badge approved">

                                        <span class="leave-status-dot"></span>

                                        Approved

                                    </span>

                                @elseif($leave->status === \App\Models\LeaveRequest::STATUS_REJECTED)

                                    <span class="leave-status-badge rejected">

                                        <span class="leave-status-dot"></span>

                                        Rejected

                                    </span>

                                @else

                                    <span class="leave-status-badge pending">

                                        <span class="leave-status-dot"></span>

                                        Pending

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                ACTION
                            ================================================== --}}

                            <td>

                                <div class="leave-actions">


                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route(
                                            'hrd.leave-requests.show',
                                            $leave
                                        ) }}"
                                        class="leave-action view"
                                        title="Lihat detail pengajuan"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- DOWNLOAD PDF --}}

                                    @if($leave->status === \App\Models\LeaveRequest::STATUS_APPROVED)

                                        <a
                                            href="{{ route(
                                                'hrd.leave-requests.download',
                                                $leave
                                            ) }}"
                                            class="leave-action download"
                                            title="Download surat cuti PDF"
                                            target="_blank"
                                        >

                                            <i class="fas fa-file-pdf"></i>

                                        </a>

                                    @else

                                        <span
                                            class="leave-action disabled"
                                            title="PDF tersedia setelah pengajuan disetujui"
                                        >

                                            <i class="fas fa-file-pdf"></i>

                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =====================================================
                            EMPTY STATE
                        ====================================================== --}}

                        <tr>

                            <td
                                colspan="8"
                                class="empty-table-cell"
                            >

                                <div class="leave-empty-state">

                                    <div class="empty-icon">

                                        <i class="fas fa-file-circle-exclamation"></i>

                                    </div>

                                    <h3>
                                        Pengajuan cuti tidak ditemukan
                                    </h3>

                                    <p>

                                        @if(request('search') || request('status'))

                                            Tidak ada pengajuan cuti yang sesuai
                                            dengan filter yang dipilih.

                                        @else

                                            Belum ada pengajuan cuti yang
                                            terdaftar dalam sistem.

                                        @endif

                                    </p>


                                    @if(request('search') || request('status'))

                                        <a
                                            href="{{ route('hrd.leave-requests.index') }}"
                                            class="btn-reset-empty"
                                        >

                                            <i class="fas fa-rotate-left"></i>

                                            Reset Filter

                                        </a>

                                    @else

                                        <div class="empty-monitoring-info">

                                            <i class="fas fa-eye"></i>

                                            <span>
                                                Halaman ini digunakan untuk
                                                monitoring pengajuan cuti.
                                            </span>

                                        </div>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

        @if($leaveRequests->hasPages())

            <div class="leave-pagination">


                {{-- PAGINATION INFO --}}

                <div class="pagination-info">

                    Menampilkan

                    <strong>
                        {{ $leaveRequests->firstItem() }}
                    </strong>

                    sampai

                    <strong>
                        {{ $leaveRequests->lastItem() }}
                    </strong>

                    dari

                    <strong>
                        {{ $leaveRequests->total() }}
                    </strong>

                    pengajuan

                </div>


                {{-- PAGINATION NAVIGATION --}}

                <div class="custom-pagination">


                    {{-- PREVIOUS --}}

                    @if($leaveRequests->onFirstPage())

                        <span class="pagination-button disabled">

                            <i class="fas fa-chevron-left"></i>

                        </span>

                    @else

                        <a
                            href="{{ $leaveRequests->previousPageUrl() }}"
                            class="pagination-button"
                            aria-label="Halaman sebelumnya"
                        >

                            <i class="fas fa-chevron-left"></i>

                        </a>

                    @endif


                    {{-- PAGE NUMBERS --}}

                    @php

                        $currentPage = $leaveRequests->currentPage();
                        $lastPage = $leaveRequests->lastPage();

                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);

                    @endphp


                    @if($startPage > 1)

                        <a
                            href="{{ $leaveRequests->url(1) }}"
                            class="pagination-button"
                        >
                            1
                        </a>

                        @if($startPage > 2)

                            <span class="pagination-dots">
                                ...
                            </span>

                        @endif

                    @endif


                    @for($page = $startPage; $page <= $endPage; $page++)

                        @if($page === $currentPage)

                            <span class="pagination-button active">
                                {{ $page }}
                            </span>

                        @else

                            <a
                                href="{{ $leaveRequests->url($page) }}"
                                class="pagination-button"
                            >
                                {{ $page }}
                            </a>

                        @endif

                    @endfor


                    @if($endPage < $lastPage)

                        @if($endPage < $lastPage - 1)

                            <span class="pagination-dots">
                                ...
                            </span>

                        @endif

                        <a
                            href="{{ $leaveRequests->url($lastPage) }}"
                            class="pagination-button"
                        >
                            {{ $lastPage }}
                        </a>

                    @endif


                    {{-- NEXT --}}

                    @if($leaveRequests->hasMorePages())

                        <a
                            href="{{ $leaveRequests->nextPageUrl() }}"
                            class="pagination-button"
                            aria-label="Halaman berikutnya"
                        >

                            <i class="fas fa-chevron-right"></i>

                        </a>

                    @else

                        <span class="pagination-button disabled">

                            <i class="fas fa-chevron-right"></i>

                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>

@endsection