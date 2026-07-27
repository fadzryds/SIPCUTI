@extends('hrd.layouts.app')

@section('title','Riwayat Cuti')

@section('content')

<div class="hrd-history-page">

    {{-- ========================================================= --}}
    {{-- HERO --}}
    {{-- ========================================================= --}}

    <section class="history-hero">

        <div class="history-hero-left">

            <span class="hero-badge">

                <i class="fa-solid fa-clock-rotate-left"></i>

                Human Resource Department

            </span>

            <h1>

                Riwayat Pengajuan Cuti

            </h1>

            <p>

                Seluruh pengajuan cuti yang telah diproses oleh HRD baik disetujui maupun ditolak.

            </p>

        </div>

        <div class="history-hero-right">

            <div class="hero-icon">

                <i class="fa-solid fa-folder-open"></i>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <section class="history-summary">

        <div class="summary-card approved">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>

                <small>Approved</small>

                <h2>{{ $approved }}</h2>

                <span>Pengajuan Disetujui</span>

            </div>

        </div>

        <div class="summary-card rejected">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>

            <div>

                <small>Rejected</small>

                <h2>{{ $rejected }}</h2>

                <span>Pengajuan Ditolak</span>

            </div>

        </div>

        <div class="summary-card total">

            <div class="summary-icon">

                <i class="fa-solid fa-file-lines"></i>

            </div>

            <div>

                <small>Total</small>

                <h2>{{ $leaveRequests->total() }}</h2>

                <span>Riwayat Pengajuan</span>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <section class="history-toolbar">

        <form method="GET" class="search-form">

            <div class="search-box">

                <i class="fa-solid fa-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau nomor pengajuan...">

            </div>

        </form>

        <form method="GET">

            <select
                name="status"
                onchange="this.form.submit()">

                <option value="">Semua Status</option>

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


    {{-- ========================================================= --}}
    {{-- TABLE --}}
    {{-- ========================================================= --}}

    <section class="history-table-card">

        <div class="table-header">

            <div>

                <h3>

                    Daftar Riwayat

                </h3>

                <p>

                    Pengajuan cuti yang telah selesai diproses.

                </p>

            </div>

        </div>

        <div class="table-responsive">

            <table>

                <thead>

                <tr>

                    <th>No</th>

                    <th>Karyawan</th>

                    <th>Jenis Cuti</th>

                    <th>Periode</th>

                    <th>Status</th>

                    <th>Aksi</th>

                </tr>

                </thead>

                <tbody>

                @forelse($leaveRequests as $leave)

                    @php

                        $hrdApproval = $leave->approvals
                            ->where('approval_level', \App\Models\LeaveApproval::LEVEL_HRD)
                            ->first();

                    @endphp

                    <tr>

                        <td>

                            {{ $leaveRequests->firstItem() + $loop->index }}

                        </td>

                        <td>

                            <div class="employee-cell">

                                <div class="employee-avatar">

                                    {{ strtoupper(substr($leave->employee->user->name,0,1)) }}

                                </div>

                                <div>

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

                            {{ $leave->leaveType->name }}

                        </td>

                        <td>

                            <strong>

                                {{ $leave->start_date->format('d M Y') }}

                            </strong>

                            <br>

                            <small>

                                s/d {{ $leave->end_date->format('d M Y') }}

                            </small>

                        </td>

                        <td>

                            <span class="status-badge {{ strtolower($hrdApproval->status) }}">

                                {{ $hrdApproval->status }}

                            </span>

                        </td>

                        <td>

                            <a
                                href="{{ route('hrd.approval.show',$leave->id) }}"
                                class="btn-detail">

                                <i class="fa-solid fa-eye"></i>

                                Detail

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6">

                            <div class="empty-state">

                                <i class="fa-solid fa-folder-open"></i>

                                <h3>

                                    Belum Ada Riwayat

                                </h3>

                                <p>

                                    Riwayat pengajuan cuti akan muncul setelah proses approval selesai.

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

{{-- ===================================================== --}}
{{-- ADDRESS --}}
{{-- ===================================================== --}}

<section class="address-card">

    <div class="card-header">

        <div class="card-title">

            <i class="fa-solid fa-location-dot"></i>

            <div>

                <h3>Alamat</h3>

                <small>
                    Alamat domisili HRD
                </small>

            </div>

        </div>

    </div>

    {{-- =============================== --}}
    {{-- READ ONLY --}}
    {{-- =============================== --}}

    <div
        id="addressView"

        @if(empty($employee->address))
            style="display:none"
        @endif
    >

        <div class="address-display">

            {{ $employee->address }}

        </div>

        <div class="action-buttons">

            <button
                class="btn btn-warning"
                onclick="editAddress()">

                <i class="fa-solid fa-pen"></i>

                Edit Alamat

            </button>

        </div>

    </div>

    {{-- =============================== --}}
    {{-- FORM --}}
    {{-- =============================== --}}

    <div
        id="addressForm"

        @if(!empty($employee->address))
            style="display:none"
        @endif
    >

        <form
            method="POST"
            action="{{ route('hrd.profile.update') }}">

            @csrf

            @method('PUT')

            <div class="address-form">

                <textarea
                    name="address"
                    placeholder="Masukkan alamat lengkap..."
                    required>{{ old('address',$employee->address) }}</textarea>

            </div>

            <div class="action-buttons">

                <button
                    class="btn btn-success">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan

                </button>

                @if($employee->address)

                    <button
                        type="button"
                        onclick="cancelEdit()"
                        class="btn btn-secondary">

                        <i class="fa-solid fa-xmark"></i>

                        Batal

                    </button>

                @endif

            </div>

        </form>

    </div>

</section>

@if(session('success'))

<div class="alert-success">

    <i class="fa-solid fa-circle-check"></i>

    <span>

        {{ session('success') }}

    </span>

</div>

@endif

@if($errors->any())

<div class="alert-error">

    <i class="fa-solid fa-circle-exclamation"></i>

    <span>

        {{ $errors->first() }}

    </span>

</div>

@endif

@push('scripts')

<script>

function editAddress(){

    document.getElementById('addressView').style.display='none';

    document.getElementById('addressForm').style.display='block';

}

function cancelEdit(){

    document.getElementById('addressView').style.display='block';

    document.getElementById('addressForm').style.display='none';

}

</script>

@endpush
@endsection