@extends('layouts.app')

@section('title', 'Pengajuan Cuti')

@vite([
    'resources/css/dashboard.css',
    'resources/css/leave.css',
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

            <a href="{{ route('employee.leave.create') }}" class="btn-primary">

                <i class="fas fa-plus"></i>

                Ajukan Cuti

            </a>

        </div>

    </div>


    {{-- ================= SUMMARY ================= --}}

    <div class="leave-summary">

        {{-- Sisa Cuti --}}
        <div class="summary-card">
            <div class="summary-icon summary-green">
                <img width="40" height="40" src="https://img.icons8.com/ios-filled/50/FFFFFF/today.png" alt="today"/>
            </div>
    
            <div class="summary-content">
                <span class="summary-title">Sisa Cuti</span>
    
                <h2>{{ $remainingLeave }}</h2>
    
                <small>Hari cuti tersedia</small>
    
                <div class="summary-progress">
                    <div class="summary-progress-bar" style="width:75%"></div>
                </div>
            </div>
        </div>
    
        {{-- Pending --}}
        <div class="summary-card">
            <div class="summary-icon summary-yellow">
                <img width="40" height="40" src="https://img.icons8.com/ios/50/FFFFFF/clock--v1.png" alt="clock--v1"/>
            </div>
    
            <div class="summary-content">
                <span class="summary-title">Menunggu Approval</span>
    
                <h2>{{ $pending }}</h2>
    
                <small>Pengajuan diproses</small>
            </div>
        </div>
    
        {{-- Approved --}}
        <div class="summary-card">
            <div class="summary-icon summary-blue">
                <img width="40" height="40" src="https://img.icons8.com/ios/50/FFFFFF/instagram-check-mark.png" alt="instagram-check-mark"/>
            </div>
    
            <div class="summary-content">
                <span class="summary-title">Disetujui</span>
    
                <h2>{{ $approved }}</h2>
    
                <small>Pengajuan berhasil</small>
            </div>
        </div>
    
        {{-- Rejected --}}
        <div class="summary-card">
            <div class="summary-icon summary-red">
                <img width="45" height="45" src="https://img.icons8.com/external-tanah-basah-basic-outline-tanah-basah/24/FFFFFF/external-rejected-approved-and-rejected-tanah-basah-basic-outline-tanah-basah-10.png" alt="external-rejected-approved-and-rejected-tanah-basah-basic-outline-tanah-basah-10"/>
            </div>
    
            <div class="summary-content">
                <span class="summary-title">Ditolak</span>
    
                <h2>{{ $rejected }}</h2>
    
                <small>Pengajuan ditolak</small>
            </div>
        </div>
    
    </div>

    {{-- ================= TABLE ================= --}}

    <div class="table-card">

        <div class="table-top">

            <form method="GET">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nomor pengajuan..."
                    class="search-input">
            
            </form>

            <form method="GET">

                <select
                    name="status"
                    class="status-filter"
                    onchange="this.form.submit()">
            
                    <option value="">Semua Status</option>
            
                    <option value="Pending"
                        @selected(request('status')=='Pending')>
                        Pending
                    </option>
            
                    <option value="Approved"
                        @selected(request('status')=='Approved')>
                        Approved
                    </option>
            
                    <option value="Rejected"
                        @selected(request('status')=='Rejected')>
                        Rejected
                    </option>
            
                </select>
            
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

                    <td>{{ $loop->iteration }}</td>

                    <td>

                        {{ $leave->request_number }}

                    </td>

                    <td>

                        {{ $leave->leaveType->name }}

                    </td>

                    <td>

                        {{ $leave->start_date->format('d M Y') }}

                        -

                        {{ $leave->end_date->format('d M Y') }}

                    </td>

                    <td>

                        {{ $leave->total_days }} Hari

                    </td>

                    <td>

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

                    <td>

                        <div class="action-group">

                            <a
                                href="{{ route('employee.leave.download', $leave->id) }}"

                                class="btn-download">
                                                    
                                <i class="fa-solid fa-download"></i>
                                                    
                                Download PDF
                                                    
                            </a>

                            @if($leave->status=='Pending')

                            <a

                                href="#"

                                class="btn-icon">

                                <i class="fas fa-edit"></i>

                            </a>

                            <form

                                action="#"

                                method="POST">

                                @csrf

                                @method('DELETE')

                                <button

                                    class="btn-icon danger"

                                    onclick="return confirm('Hapus pengajuan?')">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </form>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7">

                        <div class="empty-state">

                            <img

                                src="{{ asset('assets/images/empty.png') }}"

                                width="150">

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

@endsection