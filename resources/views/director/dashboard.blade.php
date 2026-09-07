@extends('director.layouts.app')

@section('title', 'Dashboard Director')

@section('page-name', 'Dashboard')


@section('content')

<div class="director-dashboard">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <section class="director-dashboard-header">

        <div>

            <span class="dashboard-eyebrow">
                DIRECTOR CONTROL CENTER
            </span>

            <h1>
                Selamat Datang,
                {{ auth()->user()->name ?? 'Director' }}
            </h1>

            <p>
                Pantau dan kelola proses persetujuan cuti
                pada tingkat manajemen.
            </p>

        </div>


        <div class="dashboard-date">

            <i class="fa-regular fa-calendar"></i>

            <div>

                <span>
                    Hari ini
                </span>

                <strong>
                    {{ now()->translatedFormat('d F Y') }}
                </strong>

            </div>

        </div>

    </section>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <section class="director-stat-grid">


        {{-- PENDING --}}
        <div class="director-stat-card pending">

            <div class="director-stat-top">

                <div class="director-stat-icon">

                    <i class="fa-regular fa-clock"></i>

                </div>

                <span class="director-stat-label">
                    Menunggu
                </span>

            </div>

            <strong class="director-stat-number">
                {{ $pending ?? 0 }}
            </strong>

            <p>
                Pengajuan menunggu approval Director
            </p>

        </div>


        {{-- APPROVED --}}
        <div class="director-stat-card approved">

            <div class="director-stat-top">

                <div class="director-stat-icon">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <span class="director-stat-label">
                    Disetujui
                </span>

            </div>

            <strong class="director-stat-number">
                {{ $approved ?? 0 }}
            </strong>

            <p>
                Pengajuan yang telah disetujui
            </p>

        </div>


        {{-- REJECTED --}}
        <div class="director-stat-card rejected">

            <div class="director-stat-top">

                <div class="director-stat-icon">

                    <i class="fa-solid fa-circle-xmark"></i>

                </div>

                <span class="director-stat-label">
                    Ditolak
                </span>

            </div>

            <strong class="director-stat-number">
                {{ $rejected ?? 0 }}
            </strong>

            <p>
                Pengajuan yang telah ditolak
            </p>

        </div>


        {{-- TOTAL --}}
        <div class="director-stat-card total">

            <div class="director-stat-top">

                <div class="director-stat-icon">

                    <i class="fa-solid fa-layer-group"></i>

                </div>

                <span class="director-stat-label">
                    Total
                </span>

            </div>

            <strong class="director-stat-number">
                {{ $total ?? 0 }}
            </strong>

            <p>
                Total approval Director
            </p>

        </div>

    </section>


    {{-- =====================================================
         MAIN GRID
    ====================================================== --}}

    <section class="director-dashboard-grid">


        {{-- QUICK ACTION --}}
        <div class="director-dashboard-card">

            <div class="director-card-header">

                <div>

                    <span>
                        QUICK ACTION
                    </span>

                    <h2>
                        Approval Cuti
                    </h2>

                </div>

                <div class="director-card-header-icon">

                    <i class="fa-solid fa-file-signature"></i>

                </div>

            </div>


            <div class="director-approval-action">

                <div class="approval-action-info">

                    <div class="approval-action-icon">

                        <i class="fa-solid fa-clipboard-check"></i>

                    </div>

                    <div>

                        <strong>
                            {{ $pending ?? 0 }}
                            Pengajuan
                        </strong>

                        <span>
                            Menunggu keputusan Anda
                        </span>

                    </div>

                </div>


                <a
                    href="{{ route('director.approval.index') }}"
                    class="director-primary-button"
                >

                    Lihat Pengajuan

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>

        </div>


        {{-- SYSTEM INFO --}}
        <div class="director-dashboard-card system-card">

            <div class="director-card-header">

                <div>

                    <span>
                        SYSTEM
                    </span>

                    <h2>
                        Informasi Sistem
                    </h2>

                </div>

                <div class="director-card-header-icon">

                    <i class="fa-solid fa-shield-halved"></i>

                </div>

            </div>


            <div class="system-info-list">

                <div>

                    <span>
                        Status Sistem
                    </span>

                    <strong class="system-online">

                        <i class="fa-solid fa-circle"></i>

                        Online

                    </strong>

                </div>


                <div>

                    <span>
                        Role
                    </span>

                    <strong>
                        Director
                    </strong>

                </div>


                <div>

                    <span>
                        Tanggal
                    </span>

                    <strong>
                        {{ now()->format('d/m/Y') }}
                    </strong>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         RECENT APPROVAL
    ====================================================== --}}

    <section class="director-dashboard-card recent-card">

        <div class="director-card-header">

            <div>

                <span>
                    RECENT ACTIVITY
                </span>

                <h2>
                    Pengajuan Terbaru
                </h2>

            </div>


            <a
                href="{{ route('director.approval.index') }}"
                class="director-view-all"
            >

                Lihat Semua

                <i class="fa-solid fa-arrow-right"></i>

            </a>

        </div>


        @if(isset($recentLeaves) && $recentLeaves->count())

            <div class="director-recent-list">

                @foreach($recentLeaves as $leave)

                    <a
                        href="{{ route('director.approval.show', $leave) }}"
                        class="director-recent-item"
                    >

                        <div class="recent-avatar">

                            {{ strtoupper(
                                substr(
                                    $leave->employee->user->name ?? 'U',
                                    0,
                                    1
                                )
                            ) }}

                        </div>


                        <div class="recent-info">

                            <strong>

                                {{ $leave->employee->user->name ?? '-' }}

                            </strong>

                            <span>

                                {{ $leave->request_number }}

                                ·

                                {{ $leave->leaveType->name ?? '-' }}

                            </span>

                        </div>


                        <div class="recent-status">

                            @if($leave->status === 'Approved')

                                <span class="status approved">
                                    Disetujui
                                </span>

                            @elseif($leave->status === 'Rejected')

                                <span class="status rejected">
                                    Ditolak
                                </span>

                            @else

                                <span class="status pending">
                                    Menunggu
                                </span>

                            @endif

                        </div>


                        <i class="fa-solid fa-chevron-right recent-arrow"></i>

                    </a>

                @endforeach

            </div>

        @else

            <div class="director-empty">

                <i class="fa-regular fa-folder-open"></i>

                <h3>
                    Belum Ada Aktivitas
                </h3>

                <p>
                    Belum terdapat pengajuan cuti yang perlu ditampilkan.
                </p>

            </div>

        @endif

    </section>

</div>

@endsection