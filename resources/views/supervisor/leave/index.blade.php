@extends('supervisor.layouts.app')

@section('title', 'Persetujuan Cuti')

@push('styles')
    @vite('resources/css/supervisor/leave.css')
@endpush

@push('scripts')
    @vite('resources/js/supervisor/leave.js')
@endpush

@section('content')

<div class="supervisor-leave-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="leave-page-header">

        <div class="leave-header-content">

            <span class="leave-eyebrow">
                LEAVE MANAGEMENT
            </span>

            <h1>
                Persetujuan Cuti
            </h1>

            <p>
                Kelola dan proses pengajuan cuti karyawan
                yang berada di bawah tanggung jawab Anda.
            </p>

        </div>

        <div class="leave-header-info">

            <div class="header-info-icon">
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M7 3V6M17 3V6M4 9H20"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />

                    <rect
                        x="4"
                        y="5"
                        width="16"
                        height="16"
                        rx="3"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M8 13H8.01M12 13H12.01M16 13H16.01M8 17H8.01M12 17H12.01"
                        stroke="currentColor"
                        stroke-width="2.4"
                        stroke-linecap="round"
                    />
                </svg>
            </div>

            <div>
                <span>
                    Hari ini
                </span>

                <strong>
                    {{ now()->translatedFormat('d F Y') }}
                </strong>
            </div>

        </div>

    </div>


    {{-- =====================================================
         FLASH MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="leave-alert leave-alert-success">

            <div class="alert-icon">
                ✓
            </div>

            <div>
                <strong>
                    Berhasil
                </strong>

                <p>
                    {{ session('success') }}
                </p>
            </div>

        </div>

    @endif


    @if(session('error'))

        <div class="leave-alert leave-alert-error">

            <div class="alert-icon">
                !
            </div>

            <div>
                <strong>
                    Terjadi Kesalahan
                </strong>

                <p>
                    {{ session('error') }}
                </p>
            </div>

        </div>

    @endif


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="leave-stats-grid">

        {{-- PENDING --}}
        <div class="leave-stat-card pending">

            <div class="leave-stat-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <circle
                        cx="12"
                        cy="12"
                        r="8.5"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M12 7V12L15 14"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />

                </svg>

            </div>

            <div class="leave-stat-content">

                <span>
                    Menunggu Persetujuan
                </span>

                <strong>
                    {{ $pending }}
                </strong>

                <small>
                    Perlu ditinjau
                </small>

            </div>

        </div>


        {{-- APPROVED --}}
        <div class="leave-stat-card approved">

            <div class="leave-stat-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <circle
                        cx="12"
                        cy="12"
                        r="8.5"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M8 12L11 15L16 9"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                </svg>

            </div>

            <div class="leave-stat-content">

                <span>
                    Disetujui
                </span>

                <strong>
                    {{ $approved }}
                </strong>

                <small>
                    Pengajuan disetujui
                </small>

            </div>

        </div>


        {{-- REJECTED --}}
        <div class="leave-stat-card rejected">

            <div class="leave-stat-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <circle
                        cx="12"
                        cy="12"
                        r="8.5"
                        stroke="currentColor"
                        stroke-width="1.8"
                    />

                    <path
                        d="M9 9L15 15M15 9L9 15"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />

                </svg>

            </div>

            <div class="leave-stat-content">

                <span>
                    Ditolak
                </span>

                <strong>
                    {{ $rejected }}
                </strong>

                <small>
                    Pengajuan ditolak
                </small>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FILTER
    ====================================================== --}}

    <section class="leave-panel filter-panel">

        <div class="panel-heading">

            <div>

                <span class="panel-eyebrow">
                    DATA PENGAJUAN
                    </span>

                    <h2>
                        Daftar Pengajuan Cuti
                    </h2>

                    <p>
                        Menampilkan pengajuan yang sedang menunggu,
                        telah disetujui, maupun telah ditolak.
                </p>

            </div>

        </div>


        <form
            method="GET"
            action="{{ route('supervisor.leave.index') }}"
            class="leave-filter-form"
        >

            {{-- SEARCH --}}
            <div class="filter-field search-field">

                <label for="leave-search">
                    Pencarian
                </label>

                <div class="input-wrapper">

                    <svg viewBox="0 0 24 24" fill="none">

                        <circle
                            cx="11"
                            cy="11"
                            r="7"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M16 16L21 21"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                    </svg>

                    <input
                        id="leave-search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nomor pengajuan atau nama karyawan"
                        autocomplete="off"
                    >

                </div>

            </div>


            {{-- STATUS --}}
            <div class="filter-field">

                <label for="leave-status">
                    Status
                </label>

                <select
                    id="leave-status"
                    name="status"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="Pending"
                        @selected(request('status') === 'Pending')
                    >
                        Menunggu
                    </option>

                    <option
                        value="Approved"
                        @selected(request('status') === 'Approved')
                    >
                        Disetujui
                    </option>

                    <option
                        value="Rejected"
                        @selected(request('status') === 'Rejected')
                    >
                        Ditolak
                    </option>

                </select>

            </div>


            {{-- ACTION --}}
            <div class="filter-actions">

                <button
                    type="submit"
                    class="btn-filter-primary"
                >
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M11 19A8 8 0 1 0 11 3a8 8 0 0 0 0 16Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="m17 17 4 4"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>

                    Cari
                </button>

                <a
                    href="{{ route('supervisor.leave.index') }}"
                    class="btn-filter-secondary"
                >
                    Reset
                </a>

            </div>

        </form>

    </section>


    {{-- =====================================================
         TABLE PANEL
    ====================================================== --}}

    <section class="leave-panel requests-panel">

        <div class="requests-header">

            <div>

                <span class="panel-eyebrow">
                    DATA PENGAJUAN
                </span>

                <h2>
                    Daftar Pengajuan Cuti
                </h2>

                <p>
                    Daftar pengajuan yang berkaitan dengan proses approval Anda.
                </p>

            </div>

            @if($leaveRequests->total())

                <div class="request-count">

                    <strong>
                        {{ $leaveRequests->total() }}
                    </strong>

                    <span>
                        pengajuan
                    </span>

                </div>

            @endif

        </div>


        @if($leaveRequests->count())

            {{-- DESKTOP / TABLET --}}
            <div class="leave-table-wrapper">

                <table class="leave-table">

                    <thead>

                        <tr>

                            <th>
                                Pengajuan
                            </th>

                            <th>
                                Karyawan
                            </th>

                            <th>
                                Jenis Cuti
                            </th>

                            <th>
                                Periode
                            </th>

                            <th>
                                Lama
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-right">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($leaveRequests as $leave)

                        @php
                            $supervisorApproval = $leave->approvals
                                ->first(function ($approval) use ($leave) {
                                    return $approval->approver_id === auth()->id()
                                        && $approval->approval_level === 'Supervisor';
                                });
                    
                            $supervisorStatus = $supervisorApproval?->status ?? 'Pending';
                        @endphp

                            <tr>

                                {{-- REQUEST --}}
                                <td>

                                    <div class="request-cell">

                                        <strong>
                                            {{ $leave->request_number }}
                                        </strong>

                                        <small>
                                            {{ optional($leave->submitted_at)->format('d M Y, H:i') }}
                                        </small>

                                    </div>

                                </td>


                                {{-- EMPLOYEE --}}
                                <td>

                                    <div class="employee-cell">

                                        <div class="employee-avatar">

                                            {{
                                                strtoupper(
                                                    substr(
                                                        $leave->employee->user->name ?? 'U',
                                                        0,
                                                        1
                                                    )
                                                )
                                            }}

                                        </div>

                                        <div class="employee-info">

                                            <strong>
                                                {{ $leave->employee->user->name ?? '-' }}
                                            </strong>

                                            <small>
                                                NIK:
                                                {{ $leave->employee->nik ?? '-' }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- LEAVE TYPE --}}
                                <td>

                                    <span class="leave-type">
                                        {{ $leave->leaveType->name ?? '-' }}
                                    </span>

                                </td>


                                {{-- PERIOD --}}
                                <td>

                                    <div class="period-cell">

                                        <strong>
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                        </strong>

                                        <span>
                                            →
                                        </span>

                                        <strong>
                                            {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- DAYS --}}
                                <td>

                                    <div class="days-cell">

                                        <strong>
                                            {{ $leave->total_days }}
                                        </strong>

                                        <span>
                                            Hari
                                        </span>

                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td>

                                @if($supervisorStatus === 'Pending')
                            
                                    <span class="leave-status pending">
                                        <span></span>
                                        Menunggu
                                    </span>
                            
                                @elseif($supervisorStatus === 'Approved')
                            
                                    <span class="leave-status approved">
                                        <span></span>
                                        Disetujui
                                    </span>
                            
                                @elseif($supervisorStatus === 'Rejected')
                            
                                    <span class="leave-status rejected">
                                        <span></span>
                                        Ditolak
                                    </span>
                            
                                @else
                            
                                    <span class="leave-status">
                                        {{ $supervisorStatus }}
                                    </span>
                            
                                @endif
                            
                            </td>


                                {{-- ACTION --}}
                                <td class="text-right">

                                    <a
                                        href="{{ route('supervisor.leave.show', $leave) }}"
                                        class="btn-detail"
                                    >

                                        <span>
                                            Detail
                                        </span>

                                        <svg viewBox="0 0 24 24" fill="none">

                                            <path
                                                d="M5 12H19"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                            />

                                            <path
                                                d="m13 6 6 6-6 6"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />

                                        </svg>

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- MOBILE --}}
            <div class="leave-mobile-list">

                @foreach($leaveRequests as $leave)

                    <a
                        href="{{ route('supervisor.leave.show', $leave) }}"
                        class="mobile-leave-card"
                    >

                        <div class="mobile-card-top">

                        <div>
                    
                            <span class="mobile-request-label">
                                {{ $leave->request_number }}
                            </span>
                    
                            <strong>
                                {{ $leave->employee->user->name ?? '-' }}
                            </strong>
                    
                        </div>
                    
                    
                        @if($supervisorStatus === 'Pending')
                    
                            <span class="leave-status pending">
                                <span></span>
                                Menunggu
                            </span>
                    
                        @elseif($supervisorStatus === 'Approved')
                    
                            <span class="leave-status approved">
                                <span></span>
                                Disetujui
                            </span>
                    
                        @elseif($supervisorStatus === 'Rejected')
                    
                            <span class="leave-status rejected">
                                <span></span>
                                Ditolak
                            </span>
                    
                        @else
                    
                            <span class="leave-status">
                                {{ $supervisorStatus }}
                            </span>
                    
                        @endif
                    
                    </div>


                        <div class="mobile-card-details">

                            <div>

                                <span>
                                    Jenis Cuti
                                </span>

                                <strong>
                                    {{ $leave->leaveType->name ?? '-' }}
                                </strong>

                            </div>


                            <div>

                                <span>
                                    Periode
                                </span>

                                <strong>
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                    →
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                </strong>

                            </div>


                            <div>

                                <span>
                                    Lama
                                </span>

                                <strong>
                                    {{ $leave->total_days }} Hari
                                </strong>

                            </div>

                        </div>


                        <div class="mobile-card-footer">

                            <small>
                                {{ optional($leave->submitted_at)->format('d M Y, H:i') }}
                            </small>

                            <span>
                                Lihat Detail →
                            </span>

                        </div>

                    </a>

                @endforeach

            </div>


            {{-- PAGINATION --}}
            <div class="leave-pagination">

                {{ $leaveRequests->links() }}

            </div>

        @else

            <div class="leave-empty-state">

                <div class="empty-state-icon">

                    <svg viewBox="0 0 24 24" fill="none">

                        <path
                            d="M7 3V6M17 3V6M4 9H20"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                        <rect
                            x="4"
                            y="5"
                            width="16"
                            height="16"
                            rx="3"
                            stroke="currentColor"
                            stroke-width="1.8"
                        />

                        <path
                            d="M9 14H15"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />

                    </svg>

                </div>

                <h3>
                    Tidak ada pengajuan cuti
                </h3>

                <p>
                    Belum terdapat pengajuan cuti yang sesuai
                    dengan filter yang Anda gunakan.
                </p>

                <a
                    href="{{ route('supervisor.leave.index') }}"
                    class="empty-reset"
                >
                    Tampilkan Semua
                </a>

            </div>

        @endif

    </section>

</div>

@endsection