@extends('director.layouts.app')

@section('title', 'Persetujuan Cuti')

@section('content')

<div class="approval-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <section class="approval-hero">

        <div class="hero-left">

            <span class="hero-tag">
                <i class="fa-solid fa-file-signature"></i>
                DIRECTOR APPROVAL
            </span>

            <h1>
                Persetujuan Cuti
            </h1>

            <p>
                Kelola dan proses pengajuan cuti
                yang telah melewati persetujuan Manager.
            </p>

        </div>

        <div class="hero-right">

            <div class="hero-icon">

                <i class="fa-solid fa-user-tie"></i>

            </div>

        </div>

    </section>


    {{-- =========================================================
         FLASH SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="approval-alert success">

            <i class="fa-solid fa-circle-check"></i>

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

        <div class="approval-alert danger">

            <i class="fa-solid fa-circle-xmark"></i>

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


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <section class="approval-summary">

        {{-- PENDING --}}

        <div class="summary-card warning">

            <div class="summary-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div>

                <small>
                    Menunggu
                </small>

                <h2>
                    {{ $pending }}
                </h2>

                <span>
                    Perlu diproses
                </span>

            </div>

        </div>


        {{-- APPROVED --}}

        <div class="summary-card success">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>

                <small>
                    Disetujui
                </small>

                <h2>
                    {{ $approved }}
                </h2>

                <span>
                    Telah disetujui
                </span>

            </div>

        </div>


        {{-- REJECTED --}}

        <div class="summary-card danger">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>

            <div>

                <small>
                    Ditolak
                </small>

                <h2>
                    {{ $rejected }}
                </h2>

                <span>
                    Telah ditolak
                </span>

            </div>

        </div>

    </section>


    {{-- =========================================================
         FILTER
    ========================================================== --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>
                    <i class="fa-solid fa-filter"></i>
                    Cari Pengajuan
                </h2>

                <p>
                    Gunakan filter untuk menemukan pengajuan cuti.
                </p>

            </div>

        </div>


        <form
            action="{{ route('director.approval.index') }}"
            method="GET"
            class="approval-filter"
        >

            <div class="filter-group">

                <label>
                    Pencarian
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nomor pengajuan atau nama karyawan"
                >

            </div>


            <div class="filter-group">

                <label>
                    Status
                </label>

                <select name="status">

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


            <div class="filter-actions">

                <button
                    type="submit"
                    class="btn-primary"
                >

                    <i class="fa-solid fa-magnifying-glass"></i>

                    Cari

                </button>


                <a
                    href="{{ route('director.approval.index') }}"
                    class="btn-secondary"
                >
                    Reset
                </a>

            </div>

        </form>

    </section>


    {{-- =========================================================
         TABLE
    ========================================================== --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-list-check"></i>

                    Daftar Pengajuan Cuti

                </h2>

                <p>

                    Pengajuan yang menjadi tanggung jawab Director.

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

            <div class="approval-table-wrapper">

                <table class="approval-table">

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

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($leaveRequests as $leave)

                            @php

                                $directorApproval =
                                    $leave->approvals
                                        ->where(
                                            'approval_level',
                                            \App\Models\LeaveApproval::LEVEL_DIRECTOR
                                        )
                                        ->where(
                                            'approver_id',
                                            auth()->id()
                                        )
                                        ->first();

                            @endphp


                            <tr>

                                <td>

                                    <div class="table-request">

                                        <strong>
                                            {{ $leave->request_number }}
                                        </strong>

                                        <small>
                                            {{ optional($leave->submitted_at)->format('d M Y, H:i') }}
                                        </small>

                                    </div>

                                </td>


                                <td>

                                    <div class="table-employee">

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

                                        <div>

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


                                <td>

                                    {{ $leave->leaveType->name ?? '-' }}

                                </td>


                                <td>

                                    <div class="table-period">

                                        <strong>
                                            {{ $leave->start_date->format('d M Y') }}
                                        </strong>

                                        <span>
                                            →
                                        </span>

                                        <strong>
                                            {{ $leave->end_date->format('d M Y') }}
                                        </strong>

                                    </div>

                                </td>


                                <td>

                                    <strong>
                                        {{ $leave->total_days }}
                                    </strong>

                                    Hari

                                </td>


                                <td>

                                    @if($directorApproval?->status === 'Pending')

                                        <span class="status-badge warning">

                                            <i class="fa-solid fa-clock"></i>

                                            Menunggu

                                        </span>

                                    @elseif($directorApproval?->status === 'Approved')

                                        <span class="status-badge success">

                                            <i class="fa-solid fa-check"></i>

                                            Disetujui

                                        </span>

                                    @elseif($directorApproval?->status === 'Rejected')

                                        <span class="status-badge danger">

                                            <i class="fa-solid fa-xmark"></i>

                                            Ditolak

                                        </span>

                                    @else

                                        <span class="status-badge">

                                            {{ $directorApproval?->status ?? '-' }}

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <a
                                        href="{{ route('director.approval.show', $leave) }}"
                                        class="btn-detail"
                                    >

                                        Detail

                                        <i class="fa-solid fa-arrow-right"></i>

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="approval-pagination">

                {{ $leaveRequests->links() }}

            </div>

        @else

            <div class="approval-empty">

                <i class="fa-solid fa-folder-open"></i>

                <h3>
                    Tidak ada pengajuan
                </h3>

                <p>
                    Tidak terdapat pengajuan cuti
                    yang sesuai dengan filter.
                </p>

            </div>

        @endif

    </section>

</div>

@endsection