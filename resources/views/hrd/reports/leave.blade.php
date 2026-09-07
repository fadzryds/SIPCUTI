@extends('hrd.layouts.app')

@section('title', 'Laporan Cuti')

@section('content')

<div class="hrd-page leave-report-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon leave-report-header-icon">
                <i class="fas fa-chart-column"></i>
            </div>

            <div class="page-header-text">

                <span class="page-header-label">
                    REPORTING
                </span>

                <h1>
                    Laporan Cuti
                </h1>

                <p>
                    Rekapitulasi pengajuan cuti berdasarkan karyawan,
                    department, jenis cuti, periode, dan status.
                </p>

            </div>

        </div>

        <div class="page-header-actions">

            <a
                href="{{ route('hrd.reports.leave.export', request()->query()) }}"
                class="btn-primary leave-export-button"
            >
                <i class="fas fa-file-csv"></i>
                <span>Export Data</span>
            </a>

        </div>

    </div>


    {{-- =========================================================
        STATISTICS — 4 CARD
    ========================================================== --}}

    <div class="leave-report-statistics">

        {{-- TOTAL REQUEST --}}

        <div class="leave-report-stat-card">

            <div class="leave-report-stat-icon total">
                <i class="fas fa-file-lines"></i>
            </div>

            <div class="leave-report-stat-content">

                <span>
                    Total Pengajuan
                </span>

                <h2>
                    {{ number_format($totalRequests) }}
                </h2>

                <small>
                    Sesuai filter laporan
                </small>

            </div>

        </div>


        {{-- APPROVED --}}

        <div class="leave-report-stat-card">

            <div class="leave-report-stat-icon approved">
                <i class="fas fa-circle-check"></i>
            </div>

            <div class="leave-report-stat-content">

                <span>
                    Approved
                </span>

                <h2>
                    {{ number_format($approvedRequests) }}
                </h2>

                <small>
                    {{ number_format($approvedDays) }} hari disetujui
                </small>

            </div>

        </div>


        {{-- PENDING --}}

        <div class="leave-report-stat-card">

            <div class="leave-report-stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>

            <div class="leave-report-stat-content">

                <span>
                    Pending
                </span>

                <h2>
                    {{ number_format($pendingRequests) }}
                </h2>

                <small>
                    {{ number_format($pendingDays) }} hari menunggu
                </small>

            </div>

        </div>


        {{-- REJECTED --}}

        <div class="leave-report-stat-card">

            <div class="leave-report-stat-icon rejected">
                <i class="fas fa-circle-xmark"></i>
            </div>

            <div class="leave-report-stat-content">

                <span>
                    Rejected
                </span>

                <h2>
                    {{ number_format($rejectedRequests) }}
                </h2>

                <small>
                    {{ number_format($rejectedDays) }} hari ditolak
                </small>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTER
    ========================================================== --}}

    <div class="leave-report-filter-card">

        <div class="leave-report-filter-header">

            <div>

                <span class="report-card-label">
                    FILTER LAPORAN
                </span>

                <h2>
                    Parameter Laporan
                </h2>

                <p>
                    Gunakan filter untuk menampilkan data yang lebih spesifik.
                </p>

            </div>

            <div class="filter-header-icon">
                <i class="fas fa-sliders"></i>
            </div>

        </div>


        <form
            method="GET"
            action="{{ route('hrd.reports.leave') }}"
            class="leave-report-filter-form"
        >

            {{-- SEARCH --}}

            <div class="report-filter-group report-filter-search">

                <label>
                    Pencarian
                </label>

                <div class="report-input-wrapper">

                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nomor pengajuan, NIK, nama karyawan, atau jenis cuti..."
                    >

                </div>

            </div>


            {{-- DATE FROM --}}

            <div class="report-filter-group">

                <label>
                    Dari Tanggal
                </label>

                <div class="report-input-wrapper">

                    <i class="fas fa-calendar-day"></i>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                    >

                </div>

            </div>


            {{-- DATE TO --}}

            <div class="report-filter-group">

                <label>
                    Sampai Tanggal
                </label>

                <div class="report-input-wrapper">

                    <i class="fas fa-calendar-check"></i>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                    >

                </div>

            </div>


            {{-- DEPARTMENT --}}

            <div class="report-filter-group">

                <label>
                    Department
                </label>

                <div class="report-input-wrapper">

                    <i class="fas fa-building"></i>

                    <select name="department_id">

                        <option value="">
                            Semua Department
                        </option>

                        @foreach($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                @selected(
                                    (string) request('department_id')
                                    ===
                                    (string) $department->id
                                )
                            >
                                {{ $department->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- POSITION --}}

            <div class="report-filter-group">

                <label>
                    Position
                </label>

                <div class="report-input-wrapper">

                    <i class="fas fa-user-tie"></i>

                    <select name="position_id">

                        <option value="">
                            Semua Position
                        </option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(
                                    (string) request('position_id')
                                    ===
                                    (string) $position->id
                                )
                            >
                                {{ $position->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- LEAVE TYPE --}}

            <div class="report-filter-group">

                <label>
                    Jenis Cuti
                </label>

                <div class="report-input-wrapper">

                    <i class="fas fa-calendar-days"></i>

                    <select name="leave_type_id">

                        <option value="">
                            Semua Jenis Cuti
                        </option>

                        @foreach($leaveTypes as $leaveType)

                            <option
                                value="{{ $leaveType->id }}"
                                @selected(
                                    (string) request('leave_type_id')
                                    ===
                                    (string) $leaveType->id
                                )
                            >
                                {{ $leaveType->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- STATUS --}}

            <div class="report-filter-group">

                <label>
                    Status
                </label>

                <div class="report-input-wrapper">

                    <i class="fas fa-filter"></i>

                    <select name="status">

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="{{ \App\Models\LeaveRequest::STATUS_PENDING }}"
                            @selected(
                                request('status')
                                ===
                                \App\Models\LeaveRequest::STATUS_PENDING
                            )
                        >
                            Pending
                        </option>

                        <option
                            value="{{ \App\Models\LeaveRequest::STATUS_APPROVED }}"
                            @selected(
                                request('status')
                                ===
                                \App\Models\LeaveRequest::STATUS_APPROVED
                            )
                        >
                            Approved
                        </option>

                        <option
                            value="{{ \App\Models\LeaveRequest::STATUS_REJECTED }}"
                            @selected(
                                request('status')
                                ===
                                \App\Models\LeaveRequest::STATUS_REJECTED
                            )
                        >
                            Rejected
                        </option>

                    </select>

                </div>

            </div>


            {{-- ACTIONS --}}

            <div class="report-filter-actions">

                <button
                    type="submit"
                    class="btn-filter"
                >
                    <i class="fas fa-search"></i>
                    Terapkan Filter
                </button>

                @if(request()->hasAny([
                    'search',
                    'date_from',
                    'date_to',
                    'department_id',
                    'position_id',
                    'leave_type_id',
                    'status',
                ]))

                    <a
                        href="{{ route('hrd.reports.leave') }}"
                        class="btn-reset"
                    >
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- =========================================================
        RECAP GRID — 3 CARD SEJAJAR
    ========================================================== --}}

    <div class="leave-report-recap-grid">


        {{-- =====================================================
            EMPLOYEE RECAP
        ====================================================== --}}

        <div class="leave-report-card">

            <div class="leave-report-card-header">

                <div>

                    <span class="report-card-label">
                        REKAP KARYAWAN
                    </span>

                    <h2>
                        Jumlah Hari per Karyawan
                    </h2>

                    <p>
                        Total pengajuan dan penggunaan hari cuti setiap karyawan.
                    </p>

                </div>

                <div class="report-card-header-icon employee">
                    <i class="fas fa-users"></i>
                </div>

            </div>


            <div class="report-recap-table-wrapper">

                <table class="report-recap-table">

                    <thead>

                        <tr>

                            <th>
                                Karyawan
                            </th>

                            <th>
                                Pengajuan
                            </th>

                            <th>
                                Total Hari
                            </th>

                            <th>
                                Approved
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($employeeRecap as $item)

                            <tr>

                                <td>

                                    <div class="recap-person">

                                        <div class="recap-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <div>

                                            <strong>
                                                {{ $item->name }}
                                            </strong>

                                            <span>
                                                NIK: {{ $item->nik }}
                                            </span>

                                            <small>
                                                {{ $item->department }}
                                                ·
                                                {{ $item->position }}
                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <span class="recap-count">
                                        {{ number_format($item->request_count) }}
                                    </span>

                                </td>

                                <td>

                                    <div class="recap-number days">

                                        <strong>
                                            {{ number_format($item->total_days) }}
                                        </strong>

                                        <span>
                                            hari
                                        </span>

                                    </div>

                                </td>

                                <td>

                                    <div class="recap-number approved-days">

                                        <strong>
                                            {{ number_format($item->approved_days) }}
                                        </strong>

                                        <span>
                                            hari
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="recap-empty"
                                >

                                    <i class="fas fa-users-slash"></i>

                                    <span>
                                        Tidak ada data karyawan.
                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            DEPARTMENT RECAP
        ====================================================== --}}

        <div class="leave-report-card">

            <div class="leave-report-card-header">

                <div>

                    <span class="report-card-label">
                        REKAP DEPARTMENT
                    </span>

                    <h2>
                        Karyawan yang Mengajukan Cuti
                    </h2>

                    <p>
                        Jumlah pengajuan dan karyawan yang mengajukan cuti per department.
                    </p>

                </div>

                <div class="report-card-header-icon department">
                    <i class="fas fa-building"></i>
                </div>

            </div>


            <div class="report-recap-table-wrapper">

                <table class="report-recap-table department-recap-table">

                    <thead>

                        <tr>

                            <th>
                                Department
                            </th>

                            <th>
                                Pengajuan
                            </th>

                            <th>
                                Karyawan Cuti
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($departmentRecap as $item)

                            <tr>

                                <td>

                                    <div class="recap-category">

                                        <div class="recap-category-icon">
                                            <i class="fas fa-building"></i>
                                        </div>

                                        <div>

                                            <strong>
                                                {{ $item->name }}
                                            </strong>

                                            <span>
                                                Department
                                            </span>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <span class="recap-count">
                                        {{ number_format($item->request_count) }}
                                    </span>

                                </td>

                                <td>

                                    <div class="employee-count">

                                        <div class="employee-count-icon">
                                            <i class="fas fa-user-group"></i>
                                        </div>

                                        <div>

                                            <strong>
                                                {{ number_format($item->employee_count) }}
                                            </strong>

                                            <span>
                                                karyawan
                                            </span>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="recap-empty"
                                >

                                    <i class="fas fa-building-circle-exclamation"></i>

                                    <span>
                                        Tidak ada data department.
                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            LEAVE TYPE RECAP
        ====================================================== --}}

        <div class="leave-report-card">

            <div class="leave-report-card-header">

                <div>

                    <span class="report-card-label">
                        REKAP JENIS CUTI
                    </span>

                    <h2>
                        Jumlah Hari per Jenis Cuti
                    </h2>

                    <p>
                        Distribusi pengajuan berdasarkan jenis cuti.
                    </p>

                </div>

                <div class="report-card-header-icon leave-type">
                    <i class="fas fa-calendar-days"></i>
                </div>

            </div>


            <div class="report-recap-table-wrapper">

                <table class="report-recap-table">

                    <thead>

                        <tr>

                            <th>
                                Jenis Cuti
                            </th>

                            <th>
                                Pengajuan
                            </th>

                            <th>
                                Total Hari
                            </th>

                            <th>
                                Approved
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($leaveTypeRecap as $item)

                            <tr>

                                <td>

                                    <div class="recap-category">

                                        <div class="recap-category-icon leave-type">
                                            <i class="fas fa-calendar-days"></i>
                                        </div>

                                        <div>

                                            <strong>
                                                {{ $item->name }}
                                            </strong>

                                            <span>
                                                Jenis cuti
                                            </span>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <span class="recap-count">
                                        {{ number_format($item->request_count) }}
                                    </span>

                                </td>

                                <td>

                                    <div class="recap-number days">

                                        <strong>
                                            {{ number_format($item->total_days) }}
                                        </strong>

                                        <span>
                                            hari
                                        </span>

                                    </div>

                                </td>

                                <td>

                                    <div class="recap-number approved-days">

                                        <strong>
                                            {{ number_format($item->approved_days) }}
                                        </strong>

                                        <span>
                                            hari
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="recap-empty"
                                >

                                    <i class="fas fa-calendar-xmark"></i>

                                    <span>
                                        Tidak ada data jenis cuti.
                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        DETAIL REPORT
    ========================================================== --}}

    <div class="leave-report-card leave-detail-report-card">

        <div class="leave-report-card-header">

            <div>

                <span class="report-card-label">
                    DETAIL LAPORAN
                </span>

                <h2>
                    Daftar Pengajuan Cuti
                </h2>

                <p>
                    Detail seluruh pengajuan berdasarkan filter yang dipilih.
                </p>

            </div>

            <div class="table-count">

                <i class="fas fa-database"></i>

                {{ number_format($leaveRequests->total()) }}
                Data

            </div>

        </div>


        <div class="leave-report-table-wrapper">

            <table class="leave-report-table">

                <thead>

                    <tr>

                        <th width="55">
                            No
                        </th>

                        <th>
                            Karyawan
                        </th>

                        <th>
                            Pengajuan
                        </th>

                        <th>
                            Department
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

                    </tr>

                </thead>


                <tbody>

                    @forelse($leaveRequests as $leave)

                        <tr>

                            <td>

                                <span class="row-number">
                                    {{ $leaveRequests->firstItem() + $loop->index }}
                                </span>

                            </td>


                            {{-- EMPLOYEE --}}

                            <td>

                                <div class="report-employee-info">

                                    <div class="report-employee-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $leave->employee?->user?->name ?? 'Tidak diketahui' }}
                                        </strong>

                                        <span>
                                            NIK:
                                            {{ $leave->employee?->nik ?? '-' }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- REQUEST --}}

                            <td>

                                <div class="report-request-info">

                                    <strong>
                                        {{ $leave->request_number }}
                                    </strong>

                                    <span>

                                        @if($leave->submitted_at)

                                            {{ $leave->submitted_at->format('d M Y, H:i') }}

                                        @else

                                            Belum disubmit

                                        @endif

                                    </span>

                                </div>

                            </td>


                            {{-- DEPARTMENT --}}

                            <td>

                                <span class="report-department">

                                    <i class="fas fa-building"></i>

                                    {{ $leave->employee?->department?->name ?? '-' }}

                                </span>

                            </td>


                            {{-- LEAVE TYPE --}}

                            <td>

                                <span class="report-leave-type">

                                    <i class="fas fa-calendar-days"></i>

                                    {{ $leave->leaveType?->name ?? '-' }}

                                </span>

                            </td>


                            {{-- PERIOD --}}

                            <td>

                                <div class="report-period">

                                    <span>
                                        {{ $leave->start_date?->format('d M Y') ?? '-' }}
                                    </span>

                                    <i class="fas fa-arrow-right"></i>

                                    <span>
                                        {{ $leave->end_date?->format('d M Y') ?? '-' }}
                                    </span>

                                </div>

                            </td>


                            {{-- DAYS --}}

                            <td>

                                <div class="report-duration">

                                    <strong>
                                        {{ number_format($leave->total_days) }}
                                    </strong>

                                    <span>
                                        hari
                                    </span>

                                </div>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if(
                                    $leave->status
                                    ===
                                    \App\Models\LeaveRequest::STATUS_APPROVED
                                )

                                    <span class="leave-status-badge approved">

                                        <span class="leave-status-dot"></span>

                                        Approved

                                    </span>

                                @elseif(
                                    $leave->status
                                    ===
                                    \App\Models\LeaveRequest::STATUS_REJECTED
                                )

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

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="report-empty-cell"
                            >

                                <div class="report-empty-state">

                                    <div class="report-empty-icon">
                                        <i class="fas fa-chart-column"></i>
                                    </div>

                                    <h3>
                                        Data laporan tidak ditemukan
                                    </h3>

                                    <p>
                                        Tidak terdapat pengajuan cuti
                                        yang sesuai dengan filter yang dipilih.
                                    </p>

                                    <a
                                        href="{{ route('hrd.reports.leave') }}"
                                        class="btn-reset-empty"
                                    >
                                        <i class="fas fa-rotate-left"></i>
                                        Reset Filter
                                    </a>

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

            <div class="leave-report-pagination">

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


                <div class="custom-pagination">

                    @if($leaveRequests->onFirstPage())

                        <span class="pagination-button disabled">
                            <i class="fas fa-chevron-left"></i>
                        </span>

                    @else

                        <a
                            href="{{ $leaveRequests->previousPageUrl() }}"
                            class="pagination-button"
                        >
                            <i class="fas fa-chevron-left"></i>
                        </a>

                    @endif


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


                    @for(
                        $page = $startPage;
                        $page <= $endPage;
                        $page++
                    )

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


                    @if($leaveRequests->hasMorePages())

                        <a
                            href="{{ $leaveRequests->nextPageUrl() }}"
                            class="pagination-button"
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


    {{-- =========================================================
        INFORMATION
    ========================================================== --}}

    <div class="leave-report-information">

        <div class="report-information-icon">
            <i class="fas fa-circle-info"></i>
        </div>

        <div>

            <strong>
                Informasi Laporan
            </strong>

            <p>
                Seluruh rekapitulasi mengikuti filter laporan yang sedang aktif.
                Rekap karyawan menunjukkan jumlah pengajuan dan total hari cuti,
                sedangkan rekap department menunjukkan jumlah pengajuan serta
                jumlah karyawan unik yang mengajukan cuti.
            </p>

        </div>

    </div>

</div>

@endsection