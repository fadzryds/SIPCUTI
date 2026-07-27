@extends('hrd.layouts.app')

@section('title', 'Profile HRD')

@section('content')

<div class="profile-page">

    @if(session('success'))

        <div class="alert-success">

            <i class="fa-solid fa-circle-check"></i>

            <span>{{ session('success') }}</span>

        </div>

    @endif

{{-- ========================================================= --}}
{{-- HERO --}}
{{-- ========================================================= --}}

<section class="profile-hero">

    <div class="profile-hero-left">

        <div class="profile-avatar">

            {{ strtoupper(substr($employee->user->name,0,1)) }}

        </div>

        <div class="profile-user">

            <span class="hero-badge">

                <i class="fa-solid fa-user-shield"></i>

                Human Resource Department

            </span>

            <h1>

                {{ $employee->user->name }}

            </h1>

            <h4>

                {{ $employee->position->name }}

            </h4>

            <p>

                {{ $employee->department->name }}

            </p>

        </div>

    </div>

    <div class="profile-hero-right">

        <div class="hero-info-card">

            <small>Email</small>

            <strong>{{ $employee->user->email }}</strong>

        </div>

        <div class="hero-info-card">

            <small>NIK</small>

            <strong>{{ $employee->nik }}</strong>

        </div>

        <div class="hero-info-card">

            <small>Status</small>

            <span class="hero-status">

                <i class="fa-solid fa-circle-check"></i>

                {{ $employee->status }}

            </span>

        </div>

    </div>

</section>

    {{-- ========================================================= --}}
    {{-- STATISTIC --}}
    {{-- ========================================================= --}}

    <section class="profile-statistics">

        <div class="stat-card pending">

            <div class="stat-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div class="stat-content">

                <span>

                    Pending Approval

                </span>

                <h2>

                    {{ $pending }}

                </h2>

                <small>

                    Menunggu Persetujuan

                </small>

            </div>

        </div>

        <div class="stat-card approved">

            <div class="stat-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div class="stat-content">

                <span>

                    Approved

                </span>

                <h2>

                    {{ $approved }}

                </h2>

                <small>

                    Telah Disetujui

                </small>

            </div>

        </div>

        <div class="stat-card rejected">

            <div class="stat-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>

            <div class="stat-content">

                <span>

                    Rejected

                </span>

                <h2>

                    {{ $rejected }}

                </h2>

                <small>

                    Ditolak

                </small>

            </div>

        </div>

        <div class="stat-card total">

            <div class="stat-icon">

                <i class="fa-solid fa-file-signature"></i>

            </div>

            <div class="stat-content">

                <span>

                    Total Approval

                </span>

                <h2>

                    {{ $totalApproval }}

                </h2>

                <small>

                    Seluruh Approval

                </small>

            </div>

        </div>

    </section>

    {{-- ========================================================= --}}
    {{-- PROFILE GRID --}}
    {{-- ========================================================= --}}

    <section class="profile-grid">

        <div class="profile-card">

            <div class="card-header">

                <div class="card-title">

                    <div class="title-icon">

                        <i class="fa-solid fa-id-card"></i>

                    </div>

                    <div>

                        <h3>

                            Informasi Personal

                        </h3>

                        <small>

                            Data identitas pegawai

                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="profile-info">

                    <label>

                        Nama Lengkap

                    </label>

                    <span>

                        {{ $employee->user->name }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Email

                    </label>

                    <span>

                        {{ $employee->user->email }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Nomor Induk Karyawan

                    </label>

                    <span>

                        {{ $employee->nik }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Jenis Kelamin

                    </label>

                    <span>

                        {{ $employee->gender ?: '-' }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Tanggal Lahir

                    </label>

                    <span>

                        {{ optional($employee->birth_date)->format('d F Y') ?: '-' }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Status Pegawai

                    </label>

                    <span class="status-badge active">

                        <i class="fa-solid fa-circle-check"></i>

                        {{ $employee->status }}

                    </span>

                </div>

            </div>

        </div>

        <div class="profile-card">

            <div class="card-header">

                <div class="card-title">

                    <div class="title-icon">

                        <i class="fa-solid fa-building-user"></i>

                    </div>

                    <div>

                        <h3>

                            Informasi Pekerjaan

                        </h3>

                        <small>

                            Informasi posisi dan divisi

                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="profile-info">

                    <label>

                        Department

                    </label>

                    <span>

                        {{ $employee->department->name }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Jabatan

                    </label>

                    <span>

                        {{ $employee->position->name }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Bergabung Sejak

                    </label>

                    <span>

                        {{ optional($employee->join_date)->format('d F Y') }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Email Login

                    </label>

                    <span>

                        {{ $employee->user->email }}

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Approval Role

                    </label>

                    <span class="role-badge">

                        HRD

                    </span>

                </div>

                <div class="profile-info">

                    <label>

                        Total Approval

                    </label>

                    <span>

                        {{ $totalApproval }}

                    </span>

                </div>

            </div>

        </div>

    </section>

    {{-- ========================================================= --}}
    {{-- ADDRESS --}}
    {{-- ========================================================= --}}

    <section class="profile-card address-card">

        <div class="card-header">

            <div class="card-title">

                <div class="title-icon">

                    <i class="fa-solid fa-location-dot"></i>

                </div>

                <div>

                    <h3>

                        Alamat Lengkap

                    </h3>

                    <small>

                        Data alamat pegawai

                    </small>

                </div>

            </div>

            @if($employee->address)

                <button
                    type="button"
                    class="btn-edit-address"
                    onclick="toggleAddress()">

                    <i class="fa-solid fa-pen"></i>

                    Edit

                </button>

            @endif

        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('hrd.profile.update') }}">

                @csrf
                @method('PUT')

                <div
                    id="addressView"
                    style="{{ $employee->address ? '' : 'display:none' }}">

                    <div class="address-view">

                        <i class="fa-solid fa-location-dot"></i>

                        <p>

                            {{ $employee->address }}

                        </p>

                    </div>

                </div>

                <div
                    id="addressEdit"
                    style="{{ $employee->address ? 'display:none' : '' }}">

                    <textarea
                        name="address"
                        rows="6"
                        placeholder="Masukkan alamat lengkap...">{{ old('address',$employee->address) }}</textarea>

                    @error('address')

                        <small class="text-danger">

                            {{ $message }}

                        </small>

                    @enderror

                    <div class="address-action">

                        <button
                            type="submit"
                            class="btn-save">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Simpan Perubahan

                        </button>

                        @if($employee->address)

                            <button
                                type="button"
                                class="btn-cancel"
                                onclick="cancelAddress()">

                                <i class="fa-solid fa-xmark"></i>

                                Batal

                            </button>

                        @endif

                    </div>

                </div>

            </form>

        </div>

    </section>

        {{-- ========================================================= --}}
    {{-- QUICK INFORMATION --}}
    {{-- ========================================================= --}}

    <section class="profile-bottom-grid">

        <div class="profile-card activity-card">

            <div class="card-header">

                <div class="card-title">

                    <div class="title-icon">

                        <i class="fa-solid fa-chart-line"></i>

                    </div>

                    <div>

                        <h3>

                            Ringkasan Aktivitas

                        </h3>

                        <small>

                            Statistik approval yang telah dilakukan

                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="activity-item">

                    <div class="activity-icon approved">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div class="activity-content">

                        <strong>

                            {{ $approved }}

                        </strong>

                        <span>

                            Pengajuan telah disetujui

                        </span>

                    </div>

                </div>

                <div class="activity-item">

                    <div class="activity-icon pending">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div class="activity-content">

                        <strong>

                            {{ $pending }}

                        </strong>

                        <span>

                            Menunggu proses approval

                        </span>

                    </div>

                </div>

                <div class="activity-item">

                    <div class="activity-icon rejected">

                        <i class="fa-solid fa-circle-xmark"></i>

                    </div>

                    <div class="activity-content">

                        <strong>

                            {{ $rejected }}

                        </strong>

                        <span>

                            Pengajuan ditolak

                        </span>

                    </div>

                </div>

                <div class="activity-item">

                    <div class="activity-icon total">

                        <i class="fa-solid fa-file-signature"></i>

                    </div>

                    <div class="activity-content">

                        <strong>

                            {{ $totalApproval }}

                        </strong>

                        <span>

                            Total seluruh approval

                        </span>

                    </div>

                </div>

            </div>

        </div>

        <div class="profile-card information-card">

            <div class="card-header">

                <div class="card-title">

                    <div class="title-icon">

                        <i class="fa-solid fa-circle-info"></i>

                    </div>

                    <div>

                        <h3>

                            Informasi

                        </h3>

                        <small>

                            Keterangan sistem

                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="info-box">

                    <i class="fa-solid fa-shield-halved"></i>

                    <div>

                        <h4>

                            Hak Akses HRD

                        </h4>

                        <p>

                            HRD memiliki hak melakukan approval akhir,
                            melihat seluruh riwayat pengajuan,
                            mengelola data profil,
                            serta mengakses histori approval.

                        </p>

                    </div>

                </div>

                <div class="info-box">

                    <i class="fa-solid fa-lock"></i>

                    <div>

                        <h4>

                            Keamanan Akun

                        </h4>

                        <p>

                            Seluruh aktivitas approval dicatat lengkap
                            beserta tanda tangan digital
                            untuk menjaga integritas dokumen perusahaan.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

@push('scripts')

<script>

const addressView = document.getElementById('addressView');
const addressEdit = document.getElementById('addressEdit');

function toggleAddress()
{
    addressView.style.display = 'none';
    addressEdit.style.display = 'block';
}

function cancelAddress()
{
    addressEdit.style.display = 'none';
    addressView.style.display = 'block';
}

</script>

@endpush

@endsection