@extends('supervisor.layouts.app')

@section('title', 'Dashboard Supervisor')

@push('styles')
    @vite('resources/css/supervisor/dashboard.css')
@endpush

@section('content')

{{-- =========================================================
   MOBILE MENU
========================================================= --}}

<button
    type="button"
    class="mobile-menu-toggle"
    id="supervisorMenuToggle"
    aria-label="Buka menu"
    aria-expanded="false"
>
    <span></span>
    <span></span>
    <span></span>
</button>


{{-- =========================================================
   SIDEBAR OVERLAY
========================================================= --}}

<div
    class="sidebar-overlay"
    id="supervisorSidebarOverlay"
    aria-hidden="true"
></div>


<div class="supervisor-dashboard">

    {{-- =====================================================
       HEADER
    ====================================================== --}}

    <div class="dashboard-header">

        <div class="dashboard-header-content">

            <span class="dashboard-eyebrow">
                SUPERVISOR PANEL
            </span>

            <h1>
                Selamat Datang,
                {{ auth()->user()->name }}
            </h1>

            <p>
                Pantau dan proses pengajuan cuti karyawan yang berada
                di bawah supervisi Anda.
            </p>

        </div>


        <div class="header-date">

            <span>
                {{ now()->translatedFormat('l') }}
            </span>

            <strong>
                {{ now()->translatedFormat('d F Y') }}
            </strong>

        </div>

    </div>


    {{-- =====================================================
       STATISTICS
    ====================================================== --}}

    <div class="stats-grid">

        {{-- TOTAL --}}
        <div class="stat-card total-card">

            <div class="stat-icon total">

                <svg viewBox="0 0 24 24" fill="none">

                    <path
                        d="M8 6H21M8 12H21M8 18H21"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    />

                    <path
                        d="M3 6H3.01M3 12H3.01M3 18H3.01"
                        stroke="currentColor"
                        stroke-width="3"
                        stroke-linecap="round"
                    />

                </svg>

            </div>

            <div class="stat-content">

                <span>
                    Total Pengajuan
                </span>

                <strong>
                    {{ $total ?? 0 }}
                </strong>

                <small>
                    Seluruh pengajuan
                </small>

            </div>

        </div>


        {{-- PENDING --}}
        <div class="stat-card pending">

            <div class="stat-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke="currentColor"
                        stroke-width="2"
                    />

                    <path
                        d="M12 7V12L15 14"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    />

                </svg>

            </div>

            <div class="stat-content">

                <span>
                    Menunggu Approval
                </span>

                <strong>
                    {{ $pending ?? 0 }}
                </strong>

                <small>
                    Perlu ditinjau
                </small>

            </div>

        </div>


        {{-- APPROVED --}}
        <div class="stat-card approved">

            <div class="stat-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke="currentColor"
                        stroke-width="2"
                    />

                    <path
                        d="M8 12L11 15L16 9"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                </svg>

            </div>

            <div class="stat-content">

                <span>
                    Disetujui
                </span>

                <strong>
                    {{ $approved ?? 0 }}
                </strong>

                <small>
                    Pengajuan disetujui
                </small>

            </div>

        </div>


        {{-- REJECTED --}}
        <div class="stat-card rejected">

            <div class="stat-icon">

                <svg viewBox="0 0 24 24" fill="none">

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                        stroke="currentColor"
                        stroke-width="2"
                    />

                    <path
                        d="M9 9L15 15M15 9L9 15"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    />

                </svg>

            </div>

            <div class="stat-content">

                <span>
                    Ditolak
                </span>

                <strong>
                    {{ $rejected ?? 0 }}
                </strong>

                <small>
                    Pengajuan ditolak
                </small>

            </div>

        </div>

    </div>


    {{-- =====================================================
       MAIN GRID
    ====================================================== --}}

    <div class="dashboard-grid">

        {{-- =================================================
           PENGAJUAN MENUNGGU
        ================================================== --}}

        <section class="dashboard-card">

            <div class="card-header">

                <div>

                    <span class="card-label">
                        APPROVAL
                    </span>

                    <h2>
                        Pengajuan Menunggu
                    </h2>

                </div>

                <a
                    href="{{ route('supervisor.leave.index') }}"
                    class="view-all"
                >
                    Lihat Semua
                </a>

            </div>


            @if(isset($recentLeaves) && $recentLeaves->count())

                <div class="leave-list">

                    @foreach($recentLeaves as $leave)

                        <a
                            href="{{ route('supervisor.leave.show', $leave) }}"
                            class="leave-item"
                        >

                            <div class="employee-avatar">

                                {{ strtoupper(
                                    substr(
                                        $leave->employee->user->name ?? 'U',
                                        0,
                                        1
                                    )
                                ) }}

                            </div>


                            <div class="leave-info">

                                <strong>
                                    {{ $leave->employee->user->name ?? '-' }}
                                </strong>

                                <span>
                                    {{ $leave->leaveType->name ?? '-' }}
                                </span>

                                <small>

                                    {{ \Carbon\Carbon::parse($leave->start_date)->translatedFormat('d M Y') }}

                                    <span class="date-separator">
                                        —
                                    </span>

                                    {{ \Carbon\Carbon::parse($leave->end_date)->translatedFormat('d M Y') }}

                                </small>

                            </div>


                            <div class="leave-days">

                                <strong>
                                    {{ $leave->total_days }}
                                </strong>

                                <span>
                                    Hari
                                </span>

                            </div>


                            <div class="arrow">
                                →
                            </div>

                        </a>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-icon">
                        ✓
                    </div>

                    <strong>
                        Tidak ada pengajuan
                    </strong>

                    <p>
                        Saat ini tidak ada pengajuan cuti
                        yang menunggu persetujuan Anda.
                    </p>

                </div>

            @endif

        </section>


        {{-- =================================================
           RINGKASAN
        ================================================== --}}

        <section class="dashboard-card summary-card">

            <div class="card-header">

                <div>

                    <span class="card-label">
                        RINGKASAN
                    </span>

                    <h2>
                        Status Pengajuan
                    </h2>

                </div>

            </div>


            <div class="summary-list">

                <div class="summary-row">

                    <div class="summary-name">

                        <span class="summary-dot pending-dot"></span>

                        <span>
                            Menunggu
                        </span>

                    </div>

                    <strong>
                        {{ $pending ?? 0 }}
                    </strong>

                </div>


                <div class="summary-row">

                    <div class="summary-name">

                        <span class="summary-dot approved-dot"></span>

                        <span>
                            Disetujui
                        </span>

                    </div>

                    <strong>
                        {{ $approved ?? 0 }}
                    </strong>

                </div>


                <div class="summary-row">

                    <div class="summary-name">

                        <span class="summary-dot rejected-dot"></span>

                        <span>
                            Ditolak
                        </span>

                    </div>

                    <strong>
                        {{ $rejected ?? 0 }}
                    </strong>

                </div>

            </div>


            <div class="summary-footer">

                <a href="{{ route('supervisor.leave.index') }}">

                    <span>
                        Kelola Pengajuan
                    </span>

                    <span class="summary-arrow">
                        →
                    </span>

                </a>

            </div>

        </section>

    </div>


    {{-- =====================================================
       WORKFLOW
    ====================================================== --}}

    <section class="workflow-card">

        <div class="workflow-header">

            <div>

                <span class="card-label">
                    WORKFLOW
                </span>

                <h2>
                    Alur Persetujuan Cuti
                </h2>

            </div>

        </div>


        <div class="workflow">

            <div class="workflow-step active">

                <div class="workflow-number">
                    01
                </div>

                <div class="workflow-text">

                    <strong>
                        Karyawan
                    </strong>

                    <span>
                        Mengajukan cuti
                    </span>

                </div>

            </div>


            <div class="workflow-line"></div>


            <div class="workflow-step current">

                <div class="workflow-number">
                    02
                </div>

                <div class="workflow-text">

                    <strong>
                        Supervisor
                    </strong>

                    <span>
                        Meninjau pengajuan
                    </span>

                </div>

            </div>


            <div class="workflow-line"></div>


            <div class="workflow-step">

                <div class="workflow-number">
                    03
                </div>

                <div class="workflow-text">

                    <strong>
                        Manager
                    </strong>

                    <span>
                        Persetujuan lanjutan
                    </span>

                </div>

            </div>


            <div class="workflow-line"></div>


            <div class="workflow-step">

                <div class="workflow-number">
                    04
                </div>

                <div class="workflow-text">

                    <strong>
                        Director
                    </strong>

                    <span>
                        Persetujuan akhir
                    </span>

                </div>

            </div>

        </div>

    </section>

</div>


{{-- =========================================================
   SIDEBAR JAVASCRIPT
========================================================= --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const menuToggle = document.getElementById(
        'supervisorMenuToggle'
    );

    const sidebar = document.querySelector(
        '.sidebar'
    );

    const overlay = document.getElementById(
        'supervisorSidebarOverlay'
    );

    if (!menuToggle || !sidebar || !overlay) {
        return;
    }


    function openSidebar() {

        sidebar.classList.add('is-open');

        overlay.classList.add('is-visible');

        menuToggle.classList.add('is-active');

        menuToggle.setAttribute(
            'aria-expanded',
            'true'
        );

        document.body.classList.add(
            'sidebar-menu-open'
        );
    }


    function closeSidebar() {

        sidebar.classList.remove('is-open');

        overlay.classList.remove('is-visible');

        menuToggle.classList.remove('is-active');

        menuToggle.setAttribute(
            'aria-expanded',
            'false'
        );

        document.body.classList.remove(
            'sidebar-menu-open'
        );
    }


    menuToggle.addEventListener(
        'click',
        function () {

            if (
                sidebar.classList.contains(
                    'is-open'
                )
            ) {

                closeSidebar();

            } else {

                openSidebar();

            }

        }
    );


    overlay.addEventListener(
        'click',
        closeSidebar
    );


    sidebar.querySelectorAll('a').forEach(
        function (link) {

            link.addEventListener(
                'click',
                function () {

                    if (
                        window.innerWidth <= 1050
                    ) {

                        closeSidebar();

                    }

                }
            );

        }
    );


    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape'
            ) {

                closeSidebar();

            }

        }
    );


    window.addEventListener(
        'resize',
        function () {

            if (
                window.innerWidth > 1050
            ) {

                closeSidebar();

            }

        }
    );

});
</script>

@endsection