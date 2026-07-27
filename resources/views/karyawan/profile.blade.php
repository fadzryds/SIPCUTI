@extends('layouts.app')

@section('title', 'Profile-Page')

@vite([
    'resources/css/profile.css'
])

@section('content')

@if(session('success'))

<div class="alert-success">

    {{ session('success') }}

</div>

@endif

<div class="profile-page">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <section class="profile-banner">

        <div class="banner-overlay"></div>

        <div class="banner-content">

            <div class="employee-profile">

                <div class="employee-avatar">

                    {{ strtoupper(substr($employee->user->name,0,1)) }}

                </div>

                <div class="employee-information">

                    <span class="employee-label">

                        Employee Profile

                    </span>

                    <h1>

                        {{ $employee->user->name }}

                    </h1>

                    <p>

                        {{ $employee->position->name }}

                        <span>•</span>

                        {{ $employee->department->name }}

                    </p>

                    <div class="employee-meta">

                        <div class="meta-item">

                            <i class="fa-solid fa-id-card"></i>

                            {{ $employee->nik }}

                        </div>

                        <div class="meta-item">

                            <i class="fa-solid fa-calendar-days"></i>

                            Bergabung
                            {{ optional($employee->join_date)->format('d M Y') }}

                        </div>

                        <div class="meta-item active">

                            <i class="fa-solid fa-circle-check"></i>

                            {{ $employee->status }}

                        </div>

                    </div>

                </div>

            </div>

            <div class="profile-actions">

                <a href="#" class="btn-password">

                    <i class="fa-solid fa-key"></i>

                    Ganti Password

                </a>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    id="logoutForm">

                    @csrf

                    <button
                        class="btn-logout"
                        type="submit">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        Logout

                    </button>

                </form>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- STATISTICS --}}
    {{-- ========================================================= --}}

    <section class="profile-statistics">

        <div class="summary-card">

            <div class="summary-icon summary-green">

                <img
                width="42"
                src="https://img.icons8.com/ios-filled/50/FFFFFF/today.png">

            </div>

            <div class="summary-content">

                <span>Sisa Cuti</span>

                <h2>

                    {{ $remainingLeave }}

                </h2>

                <small>

                    Hari cuti tersedia

                </small>

            </div>

        </div>

        <div class="summary-card">

            <div class="summary-icon summary-yellow">

                <img
                width="42"
                src="https://img.icons8.com/ios/50/FFFFFF/clock--v1.png">

            </div>

            <div class="summary-content">

                <span>

                    Pending

                </span>

                <h2>

                    {{ $pending }}

                </h2>

                <small>

                    Menunggu Approval

                </small>

            </div>

        </div>

        <div class="summary-card">

            <div class="summary-icon summary-blue">

                <img
                width="42"
                src="https://img.icons8.com/ios/50/FFFFFF/instagram-check-mark.png">

            </div>

            <div class="summary-content">

                <span>

                    Approved

                </span>

                <h2>

                    {{ $approved }}

                </h2>

                <small>

                    Pengajuan Disetujui

                </small>

            </div>

        </div>

        <div class="summary-card">

            <div class="summary-icon summary-red">

                <img
                width="42"
                src="https://img.icons8.com/external-tanah-basah-basic-outline-tanah-basah/24/FFFFFF/external-rejected-approved-and-rejected-tanah-basah-basic-outline-tanah-basah-10.png">

            </div>

            <div class="summary-content">

                <span>

                    Rejected

                </span>

                <h2>

                    {{ $rejected }}

                </h2>

                <small>

                    Pengajuan Ditolak

                </small>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- CONTENT --}}
    {{-- ========================================================= --}}

    <section class="profile-content">

        <div class="profile-left-column">


            <div class="profile-card">

                <div class="card-header">

                    <div>

                        <h3>

                            Informasi Pribadi

                        </h3>

                        <span>

                            Data personal karyawan

                        </span>

                    </div>

                    <img width="50" height="50" src="https://img.icons8.com/scribby/50/user.png" alt="user"/>

                </div>

                <div class="info-list">

                    <div class="info-item">

                        <label>

                            NIK

                        </label>

                        <strong>

                            {{ $employee->nik }}

                        </strong>

                    </div>

                    <div class="info-item">

                        <label>

                            Nama Lengkap

                        </label>

                        <strong>

                            {{ $employee->user->name }}

                        </strong>

                    </div>

                    <div class="info-item">

                        <label>

                            Email

                        </label>

                        <strong>

                            {{ $employee->user->email }}

                        </strong>

                    </div>

                    <div class="info-item">

                        <label>

                            Gender

                        </label>

                        <strong>

                            {{ $employee->gender }}

                        </strong>

                    </div>

                    <div class="info-item">

                        <label>

                            Tanggal Lahir

                        </label>

                        <strong>

                            {{ optional($employee->birth_date)->format('d F Y') }}

                        </strong>

                    </div>

                    <div class="info-item full">

                        <div class="address-header">
                    
                            <label>
                                <i class="fa-solid fa-location-dot"></i>
                                Alamat
                            </label>
                    
                            <button
                                type="button"
                                id="editAddressBtn"
                                class="address-edit-btn">
                    
                                <i class="fa-solid fa-pen"></i>
                    
                                Edit
                    
                            </button>
                    
                        </div>
                    
                        {{-- MODE VIEW --}}
                        <div id="addressView" class="address-view">
                    
                            @if($employee->address)
                    
                                {{ $employee->address }}
                    
                            @else
                    
                                <span class="empty-address">
                    
                                    Belum ada alamat.
                    
                                </span>
                    
                            @endif
                    
                        </div>
                    
                        {{-- MODE EDIT --}}
                        <form
                            id="addressForm"
                            action="{{ route('employee.profile.address.update') }}"
                            method="POST"
                            class="address-form">
                    
                            @csrf
                            @method('PATCH')
                    
                            <textarea
                                id="addressInput"
                                name="address"
                                rows="5"
                                placeholder="Masukkan alamat lengkap...">{{ old('address', $employee->address) }}
                            </textarea>
                    
                            <div class="address-actions">
                    
                                <button
                                    type="button"
                                    id="cancelEdit"
                                    class="btn-cancel">
                    
                                    Batal
                    
                                </button>
                    
                                <button
                                    type="submit"
                                    class="btn-save">
                    
                                    Simpan Perubahan
                    
                                </button>
                    
                            </div>
                    
                        </form>
                    
                    </div>
                </div>

            </div>

                        {{-- ========================================================= --}}
            {{-- INFORMASI PEKERJAAN --}}
            {{-- ========================================================= --}}

            <div class="profile-card">

                <div class="card-header">

                    <div>

                        <h3>Informasi Pekerjaan</h3>

                        <span>Informasi kepegawaian SIPCUTI</span>

                    </div>

                    <img width="48" height="48" src="https://img.icons8.com/doodle/48/information.png" alt="information"/>

                </div>

                <div class="info-list">

                    <div class="info-item">

                        <label>Department</label>

                        <strong>

                            {{ $employee->department->name }}

                        </strong>

                    </div>

                    <div class="info-item">

                        <label>Jabatan</label>

                        <strong>

                            {{ $employee->position->name }}

                        </strong>

                    </div>

                    <div class="info-item">

                        <label>Manager</label>

                        <strong>

                            {{ $employee->manager_name }}

                        </strong>

                    </div>

                    <div class="info-item">

                        <label>Tanggal Bergabung</label>

                        <strong>

                            {{ optional($employee->join_date)->format('d F Y') }}

                        </strong>

                    </div>

                    <div class="info-item full">

                        <label>Status</label>

                        <span class="status-success">

                            <i class="fa-solid fa-circle"></i>

                            {{ $employee->status }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- RIGHT SIDEBAR --}}
        {{-- ========================================================= --}}

        <div class="profile-right-column">

            {{-- Leave Progress --}}

            <div class="profile-card">

                <div class="card-header">

                    <div>

                        <h3>Progress Cuti</h3>

                        <span>Ringkasan penggunaan</span>

                    </div>

                    <img width="48" height="48" src="https://img.icons8.com/doodle/48/positive-dynamic--v1.png" alt="positive-dynamic--v1"/>

                </div>

                @php

                    $used = $approved;

                    $total = max($remainingLeave + $approved,1);

                    $percentage = ($used / $total) * 100;

                @endphp

                <div class="leave-progress">

                    <div class="progress-circle">

                        {{ round($percentage) }}%

                    </div>

                    <h4>

                        Penggunaan Hak Cuti

                    </h4>

                    <p>

                        {{ $approved }}

                        Hari telah digunakan dari

                        {{ $total }}

                        Hari.

                    </p>

                    <div class="progress-bar">

                        <div class="progress-fill"

                             style="width:{{ $percentage }}%">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection

@push('scripts')

<script>

document.getElementById('logoutForm').addEventListener('submit',function(e){

    e.preventDefault();

    if(confirm('Yakin ingin logout dari SIPCUTI?')){

        this.submit();

    }

});

document.addEventListener('DOMContentLoaded', function () {

const editBtn = document.getElementById('editAddressBtn');
const cancelBtn = document.getElementById('cancelEdit');
const addressView = document.getElementById('addressView');
const addressForm = document.getElementById('addressForm');
const addressInput = document.getElementById('addressInput');

if (!editBtn || !cancelBtn || !addressView || !addressForm || !addressInput) {
    console.log('Elemen Address tidak ditemukan');
    return;
}

editBtn.addEventListener('click', function () {

    addressView.classList.add('hidden');

    addressForm.classList.add('active');

    editBtn.classList.add('hidden');

    addressInput.focus();

});

cancelBtn.addEventListener('click', function () {

    addressForm.classList.remove('active');

    addressView.classList.remove('hidden');

    editBtn.classList.remove('hidden');

});

});
</script>

@endpush