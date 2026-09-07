@extends('layouts.app')

@section('title', 'Pengajuan Cuti')

@vite([
'resources/css/karyawan/leave.css',
'resources/js/leave.js'
])

@section('content')

<div class="dashboard-content">

    {{-- ================= HEADER ================= --}}

    <div class="page-header">

        <div>

            <h2>Pengajuan Cuti</h2>

            <p>Kelola seluruh pengajuan cuti Anda.</p>

        </div>

        <div>

    @if ($remainingLeave > 0)

        <a
            href="{{ route('employee.leave.create') }}"
            class="btn-primary"
        >
            <i class="fas fa-plus"></i>
            Ajukan Cuti
        </a>

    @else

        <button
            type="button"
            class="btn-primary"
            id="btnLeaveBalanceAlert"
        >
            <i class="fas fa-plus"></i>
            Ajukan Cuti
        </button>

    @endif

</div>

    </div>


    {{-- ================= SUMMARY ================= --}}

    <div class="leave-summary">

    {{-- Sisa Cuti --}}
    <div class="summary-card">

        <div class="summary-icon summary-green">
            <img
                width="40"
                height="40"
                src="https://img.icons8.com/ios-filled/50/FFFFFF/today.png"
                alt="today"
            >
        </div>

        <div class="summary-content">

            <span class="summary-title">
                Sisa Cuti
            </span>

            <h2>
                {{ $remainingLeave }}
            </h2>

            <small>
                Hari cuti tersedia
            </small>

            <div class="summary-progress">

                <div
                    class="summary-progress-bar"
                    style="width: {{ $totalLeave > 0 ? min(($remainingLeave / $totalLeave) * 100, 100) : 0 }}%"
                ></div>

            </div>

        </div>

    </div>


    {{-- Pending --}}
    <div class="summary-card">

        <div class="summary-icon summary-yellow">

            <img
                width="40"
                height="40"
                src="https://img.icons8.com/ios/50/FFFFFF/clock--v1.png"
                alt="clock"
            >

        </div>

        <div class="summary-content">

            <span class="summary-title">
                Menunggu Approval
            </span>

            <h2>
                {{ $pending }}
            </h2>

            <small>
                Pengajuan diproses
            </small>

        </div>

    </div>


    {{-- Approved --}}
    <div class="summary-card">

        <div class="summary-icon summary-blue">

            <img
                width="40"
                height="40"
                src="https://img.icons8.com/ios/50/FFFFFF/instagram-check-mark.png"
                alt="approved"
            >

        </div>

        <div class="summary-content">

            <span class="summary-title">
                Disetujui
            </span>

            <h2>
                {{ $approved }}
            </h2>

            <small>
                Pengajuan berhasil
            </small>

        </div>

    </div>


    {{-- Rejected --}}
    <div class="summary-card">

        <div class="summary-icon summary-red">

            <img
                width="45"
                height="45"
                src="https://img.icons8.com/external-tanah-basah-basic-outline-tanah-basah/24/FFFFFF/external-rejected-approved-and-rejected-tanah-basah-basic-outline-tanah-basah-10.png"
                alt="rejected"
            >

        </div>

        <div class="summary-content">

            <span class="summary-title">
                Ditolak
            </span>

            <h2>
                {{ $rejected }}
            </h2>

            <small>
                Pengajuan ditolak
            </small>

        </div>

    </div>

</div>

    {{-- ================= TABLE ================= --}}

    <div class="table-card">

        <div class="table-top">

            <form method="GET" class="search-form">
        
                <div class="search-box">
        
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nomor pengajuan...">
        
                    <button type="submit">
        
                        <img
                            src="https://img.icons8.com/fluency-systems-filled/48/search.png"
                            width="18">
        
                    </button>
        
                </div>
        
            </form>
        
            <form method="GET" class="filter-form">
        
                <div class="filter-dropdown">
        
                    <button
                        type="button"
                        class="filter-btn"
                        onclick="this.parentNode.classList.toggle('active')">
        
                        <img
                            src="https://img.icons8.com/ios-filled/50/filter.png"
                            width="18">
        
                    </button>
        
                    <div class="filter-menu">
        
                        <button
                            type="submit"
                            name="status"
                            value="">
        
                            Semua
        
                        </button>
        
                        <button
                            type="submit"
                            name="status"
                            value="Pending">
        
                            Pending
        
                        </button>
        
                        <button
                            type="submit"
                            name="status"
                            value="Approved">
        
                            Approved
        
                        </button>
        
                        <button
                            type="submit"
                            name="status"
                            value="Rejected">
        
                            Rejected
        
                        </button>
        
                    </div>
        
                </div>
        
            </form>
        
        </div>

        <table class="leave-table">

            <thead>

            <tr>

                <th>No</th>

                <th>No Request</th>

                <th>Jenis Cuti</th>

                <th>Tanggal</th>

                <th>Durasi</th>

                <th>Status</th>

                <th>Aksi</th>

            </tr>

            </thead>

            <tbody>

                @forelse($leaveRequests as $leave)
                
                <tr>
                
                    <td data-label="No">
                        {{ $loop->iteration }}
                    </td>
                
                    <td data-label="No Request">
                        {{ $leave->request_number }}
                    </td>
                
                    <td data-label="Jenis Cuti">
                        {{ $leave->leaveType->name }}
                    </td>
                
                    <td data-label="Tanggal">
                        {{ $leave->start_date->format('d M Y') }}
                        -
                        {{ $leave->end_date->format('d M Y') }}
                    </td>
                
                    <td data-label="Durasi">
                        {{ $leave->total_days }} Hari
                    </td>
                
                    <td data-label="Status">
                
                        @switch($leave->status)
                
                            @case('Pending')
                                <span class="badge warning">
                                    Pending
                                </span>
                            @break
                
                            @case('Approved')
                                <span class="badge success">
                                    Approved
                                </span>
                            @break
                
                            @case('Rejected')
                                <span class="badge danger">
                                    Rejected
                                </span>
                            @break
                
                            @default
                
                                <span class="badge">
                                    {{ $leave->status }}
                                </span>
                
                        @endswitch
                
                    </td>
                
                    <td data-label="Aksi">
                
                        <div class="action-group">

    <a
        href="{{ route('employee.leave.show', $leave) }}"
        class="btn-action btn-view"
    >
        <img
            src="https://img.icons8.com/fluency-systems-filled/48/FFFFFF/visible.png"
            width="22"
            alt="View"
        >
    </a>

    @if($leave->status == 'Pending')

        <a
            href="{{ route('employee.leave.edit', $leave) }}"
            class="btn-action btn-edit"
        >
            <img
                src="https://img.icons8.com/windows/32/FFFFFF/create-new.png"
                width="20"
                alt="Edit"
            >
        </a>

        <form
            action="{{ route('employee.leave.destroy', $leave) }}"
            method="POST"
        >
            @csrf
            @method('DELETE')

            <button
                class="btn-action btn-delete"
                type="submit"
                onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan cuti ini?')"
            >
                <img
                    src="https://img.icons8.com/pulsar-line/48/FFFFFF/trash.png"
                    width="20"
                    alt="Delete"
                >
            </button>
        </form>

    @endif

    @if($leave->status == 'Approved')

        <a
            href="{{ route('employee.leave.download', $leave) }}"
            class="btn-action btn-download"
        >
            <img
                src="https://img.icons8.com/fluency-systems-filled/48/FFFFFF/download.png"
                width="22"
                alt="Download"
            >
        </a>

    @endif

</div>
                
                    </td>
                
                </tr>
                
                @empty
                
                <tr>
                
                <td colspan="7">
                
                <div class="empty-state">
                
                <img
                src="{{ asset('assets/images/empty.png') }}">
                
                <h4>
                
                Belum ada pengajuan cuti
                
                </h4>
                
                <p>
                
                Silakan buat pengajuan cuti pertama Anda.
                
                </p>
                
                </div>
                
                </td>
                
                </tr>
                
                @endforelse
                
                </tbody>

        </table>

        <div class="pagination-wrapper">

            {{ $leaveRequests->links() }}

        </div>

    </div>

</div>

{{-- =========================================================
     MODAL SALDO CUTI HABIS
========================================================= --}}

@if ((int) $remainingLeave <= 0)

    <div
        id="leaveBalanceModal"
        class="leave-balance-modal"
        aria-hidden="true"
    >

        <div
            class="leave-balance-overlay"
            id="leaveBalanceOverlay"
        ></div>


        <div
            class="leave-balance-dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="leaveBalanceTitle"
        >

            {{-- Close --}}
            <button
                type="button"
                class="leave-balance-close"
                id="closeLeaveBalance"
                aria-label="Tutup"
            >
                &times;
            </button>


            {{-- Icon --}}
            <div class="leave-balance-icon">

                <div class="leave-balance-icon-inner">

                    <img width="50" height="50" src="https://img.icons8.com/carbon-copy/100/FA5252/calendar--v1.png" alt="calendar--v1"/>

                </div>

            </div>


            {{-- Content --}}
            <div class="leave-balance-content">

                <span class="leave-balance-label">
                    INFORMASI CUTI
                </span>

                <h3 id="leaveBalanceTitle">
                    Saldo Cuti Sudah Habis
                </h3>

                <p>
                    Anda belum dapat mengajukan cuti karena
                    <strong>saldo cuti Anda sudah mencapai 0 hari.</strong>
                </p>

            </div>


            {{-- Balance --}}
            <div class="leave-balance-info">

                <div class="leave-balance-info-icon">

                    <img width="30" height="30" src="https://img.icons8.com/external-tanah-basah-basic-outline-tanah-basah/24/FA5252/external-rejected-approved-and-rejected-tanah-basah-basic-outline-tanah-basah-16.png" alt="external-rejected-approved-and-rejected-tanah-basah-basic-outline-tanah-basah-16"/>

                </div>

                <div>

                    <span>
                        Sisa saldo cuti
                    </span>

                    <strong>
                        {{ $remainingLeave }} Hari
                    </strong>

                </div>

            </div>


            {{-- Action --}}
            <div class="leave-balance-action">

                <button
                    type="button"
                    class="leave-balance-button"
                    id="understandLeaveBalance"
                >
                    <i class="fas fa-check"></i>
                    Mengerti
                </button>

            </div>

        </div>

    </div>

@endif

@if ((int) $remainingLeave <= 0)

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('leaveBalanceModal');
    const button = document.getElementById('btnLeaveBalanceAlert');
    const closeButton = document.getElementById('closeLeaveBalance');
    const overlay = document.getElementById('leaveBalanceOverlay');
    const understandButton = document.getElementById('understandLeaveBalance');

    if (!modal || !button) {
        return;
    }

    function openLeaveBalanceModal() {

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('leave-modal-open');

    }

    function closeLeaveBalanceModal() {

        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');

        document.body.classList.remove('leave-modal-open');

    }

    button.addEventListener('click', function (event) {

        event.preventDefault();

        openLeaveBalanceModal();

    });

    if (closeButton) {

        closeButton.addEventListener('click', function () {

            closeLeaveBalanceModal();

        });

    }

    if (overlay) {

        overlay.addEventListener('click', function () {

            closeLeaveBalanceModal();

        });

    }

    if (understandButton) {

        understandButton.addEventListener('click', function () {

            closeLeaveBalanceModal();

        });

    }

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeLeaveBalanceModal();

        }

    });

});
</script>

@endif

@endsection