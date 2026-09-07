@extends('hrd.layouts.app')

@section('title', 'Detail Karyawan')

@section('content')

<div class="employee-show-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="page-header">

        <div>

            <div class="breadcrumb">

                <a href="{{ route('hrd.employees.index') }}">
                    Karyawan
                </a>

                <span>/</span>

                <span>Detail Karyawan</span>

            </div>

            <h1>Detail Karyawan</h1>

            <p>
                Informasi lengkap mengenai data karyawan.
            </p>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('hrd.employees.index') }}"
                class="btn-back"
            >
                <span>←</span>
                Kembali
            </a>

            <a
                href="{{ route('hrd.employees.edit', $employee) }}"
                class="btn-edit"
            >
                <span>✎</span>
                Edit
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <div class="alert-icon">
                ✓
            </div>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">

            <div class="alert-icon">
                !
            </div>

            <div>
                {{ session('error') }}
            </div>

        </div>

    @endif


    {{-- =========================================================
        PROFILE HERO
    ========================================================== --}}

    <section class="profile-card">

        <div class="profile-left">

            <div class="profile-avatar">

                {{ strtoupper(
                    substr(
                        $employee->user?->name ?? 'K',
                        0,
                        1
                    )
                ) }}

            </div>


            <div class="profile-info">

                <h2>
                    {{ $employee->user?->name ?? '-' }}
                </h2>

                <p class="profile-email">
                    {{ $employee->user?->email ?? '-' }}
                </p>

                <div class="profile-meta">

                    <span class="nik-badge">
                        NIK: {{ $employee->nik ?? '-' }}
                    </span>

                    @if($employee->status === 'Active')

                        <span class="status-badge status-active">
                            <span class="status-dot"></span>
                            Active
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            <span class="status-dot"></span>
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

        </div>


        <div class="profile-right">

            <div class="profile-summary">

                <span class="summary-label">
                    Department
                </span>

                <strong>
                    {{ $employee->department?->name ?? '-' }}
                </strong>

            </div>


            <div class="profile-summary">

                <span class="summary-label">
                    Position
                </span>

                <strong>
                    {{ $employee->position?->name ?? '-' }}
                </strong>

            </div>

        </div>

    </section>


    {{-- =========================================================
        PERSONAL INFORMATION
    ========================================================== --}}

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-icon">
                👤
            </div>

            <div>

                <h2>
                    Data Personal
                </h2>

                <p>
                    Informasi pribadi karyawan.
                </p>

            </div>

        </div>


        <div class="detail-grid">

            {{-- NIK --}}

            <div class="detail-item">

                <span class="detail-label">
                    NIK
                </span>

                <span class="detail-value">
                    {{ $employee->nik ?? '-' }}
                </span>

            </div>


            {{-- NAME --}}

            <div class="detail-item">

                <span class="detail-label">
                    Nama Lengkap
                </span>

                <span class="detail-value">
                    {{ $employee->user?->name ?? '-' }}
                </span>

            </div>


            {{-- EMAIL --}}

            <div class="detail-item">

                <span class="detail-label">
                    Email
                </span>

                <span class="detail-value">
                    {{ $employee->user?->email ?? '-' }}
                </span>

            </div>


            {{-- PHONE --}}

            <div class="detail-item">

                <span class="detail-label">
                    Nomor Telepon
                </span>

                <span class="detail-value">
                    {{ $employee->user?->phone ?? '-' }}
                </span>

            </div>


            {{-- BIRTH DATE --}}

            <div class="detail-item">

                <span class="detail-label">
                    Tanggal Lahir
                </span>

                <span class="detail-value">

                    @if($employee->birth_date)

                        {{ $employee->birth_date->format('d F Y') }}

                    @else

                        -

                    @endif

                </span>

            </div>


            {{-- GENDER --}}

            <div class="detail-item">

                <span class="detail-label">
                    Jenis Kelamin
                </span>

                <span class="detail-value">

                    @if($employee->gender === 'Male')

                        Laki-laki

                    @elseif($employee->gender === 'Female')

                        Perempuan

                    @else

                        -

                    @endif

                </span>

            </div>


            {{-- ADDRESS --}}

            <div class="detail-item detail-item-full">

                <span class="detail-label">
                    Alamat
                </span>

                <span class="detail-value detail-address">

                    {{ $employee->address ?: '-' }}

                </span>

            </div>

        </div>

    </section>


    {{-- =========================================================
        ACCOUNT INFORMATION
    ========================================================== --}}

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-icon">
                🔐
            </div>

            <div>

                <h2>
                    Akun & Role
                </h2>

                <p>
                    Informasi akun dan hak akses sistem.
                </p>

            </div>

        </div>


        <div class="detail-grid">

            {{-- EMAIL --}}

            <div class="detail-item">

                <span class="detail-label">
                    Email Login
                </span>

                <span class="detail-value">
                    {{ $employee->user?->email ?? '-' }}
                </span>

            </div>


            {{-- ROLE --}}

            <div class="detail-item">

                <span class="detail-label">
                    Role
                </span>

                <span class="detail-value">

                    @if($employee->user)

                        @php
                            $roles = $employee->user->getRoleNames();
                        @endphp

                        @if($roles->count())

                            <div class="role-list">

                                @foreach($roles as $role)

                                    <span class="role-badge">
                                        {{ $role }}
                                    </span>

                                @endforeach

                            </div>

                        @else

                            -

                        @endif

                    @else

                        -

                    @endif

                </span>

            </div>


            {{-- ACCOUNT STATUS --}}

            <div class="detail-item">

                <span class="detail-label">
                    Status Akun
                </span>

                <span class="detail-value">

                    @if($employee->user)

                        @if($employee->user->email_verified_at)

                            <span class="verified-badge">
                                ✓ Terverifikasi
                            </span>

                        @else

                            <span class="unverified-badge">
                                Belum Terverifikasi
                            </span>

                        @endif

                    @else

                        -

                    @endif

                </span>

            </div>

        </div>

    </section>


    {{-- =========================================================
        JOB INFORMATION
    ========================================================== --}}

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-icon">
                💼
            </div>

            <div>

                <h2>
                    Data Pekerjaan
                </h2>

                <p>
                    Informasi pekerjaan dan status kepegawaian.
                </p>

            </div>

        </div>


        <div class="detail-grid">

            {{-- DEPARTMENT --}}

            <div class="detail-item">

                <span class="detail-label">
                    Department
                </span>

                <span class="detail-value">

                    @if($employee->department)

                        <strong>
                            {{ $employee->department->name }}
                        </strong>

                        @if($employee->department->code)

                            <small class="sub-value">
                                {{ $employee->department->code }}
                            </small>

                        @endif

                    @else

                        -

                    @endif

                </span>

            </div>


            {{-- POSITION --}}

            <div class="detail-item">

                <span class="detail-label">
                    Position
                </span>

                <span class="detail-value">

                    @if($employee->position)

                        <strong>
                            {{ $employee->position->name }}
                        </strong>

                        @if($employee->position->code)

                            <small class="sub-value">
                                {{ $employee->position->code }}
                            </small>

                        @endif

                    @else

                        -

                    @endif

                </span>

            </div>


            {{-- JOIN DATE --}}

            <div class="detail-item">

                <span class="detail-label">
                    Tanggal Bergabung
                </span>

                <span class="detail-value">

                    @if($employee->join_date)

                        {{ $employee->join_date->format('d F Y') }}

                    @else

                        -

                    @endif

                </span>

            </div>


            {{-- STATUS --}}

            <div class="detail-item">

                <span class="detail-label">
                    Status Karyawan
                </span>

                <span class="detail-value">

                    @if($employee->status === 'Active')

                        <span class="status-badge status-active">
                            <span class="status-dot"></span>
                            Active
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            <span class="status-dot"></span>
                            Inactive
                        </span>

                    @endif

                </span>

            </div>

        </div>

    </section>

    {{-- =========================================================
    ORGANIZATION STRUCTURE
========================================================== --}}

@php

    /*
    |--------------------------------------------------------------------------
    | CURRENT ROLE
    |--------------------------------------------------------------------------
    */

    $currentRole = $employee->user?->getRoleNames()->first() ?? 'Employee';


    /*
    |--------------------------------------------------------------------------
    | ROLE LABEL
    |--------------------------------------------------------------------------
    */

    $roleLabel = match ($currentRole) {

        'Director'   => 'Director',

        'Manager'    => 'Manager',

        'Supervisor' => 'Supervisor',

        default      => 'Karyawan',

    };

@endphp


<section class="detail-card">

    <div class="detail-card-header">

        <div class="section-icon">
            🏢
        </div>

        <div>

            <h2>
                Struktur Organisasi
            </h2>

            <p>
                Hubungan karyawan dalam struktur organisasi.
            </p>

        </div>

    </div>


    <div class="organization-tree">


        {{-- =====================================================
            DIRECTOR
        ====================================================== --}}

        @if($currentRole !== 'Director')

            <div class="organization-item">

                <div class="organization-icon director-icon">
                    D
                </div>

                <div class="organization-content">

                    <span class="organization-label">
                        Director
                    </span>

                    @if($employee->director)

                        <a
                            href="{{ route('hrd.employees.show', $employee->director) }}"
                            class="organization-name"
                        >
                            {{ $employee->director->user?->name ?? '-' }}
                        </a>

                        <span class="organization-nik">
                            NIK: {{ $employee->director->nik }}
                        </span>

                    @else

                        <span class="organization-empty">
                            Belum ditentukan
                        </span>

                    @endif

                </div>

            </div>


            <div class="organization-line"></div>

        @endif


        {{-- =====================================================
            MANAGER
        ====================================================== --}}

        @if(
            in_array(
                $currentRole,
                ['Supervisor', 'Employee']
            )
        )

            <div class="organization-item">

                <div class="organization-icon manager-icon">
                    M
                </div>

                <div class="organization-content">

                    <span class="organization-label">
                        Manager
                    </span>

                    @if($employee->manager)

                        <a
                            href="{{ route('hrd.employees.show', $employee->manager) }}"
                            class="organization-name"
                        >
                            {{ $employee->manager->user?->name ?? '-' }}
                        </a>

                        <span class="organization-nik">
                            NIK: {{ $employee->manager->nik }}
                        </span>

                    @else

                        <span class="organization-empty">
                            Belum ditentukan
                        </span>

                    @endif

                </div>

            </div>


            <div class="organization-line"></div>

        @endif


        {{-- =====================================================
            SUPERVISOR
        ====================================================== --}}

        @if($currentRole === 'Employee')

            <div class="organization-item">

                <div class="organization-icon supervisor-icon">
                    S
                </div>

                <div class="organization-content">

                    <span class="organization-label">
                        Supervisor
                    </span>

                    @if($employee->supervisor)

                        <a
                            href="{{ route('hrd.employees.show', $employee->supervisor) }}"
                            class="organization-name"
                        >
                            {{ $employee->supervisor->user?->name ?? '-' }}
                        </a>

                        <span class="organization-nik">
                            NIK: {{ $employee->supervisor->nik }}
                        </span>

                    @else

                        <span class="organization-empty">
                            Belum ditentukan
                        </span>

                    @endif

                </div>

            </div>


            <div class="organization-line"></div>

        @endif


        {{-- =====================================================
            CURRENT EMPLOYEE
        ====================================================== --}}

        <div class="organization-item organization-current">

            <div class="organization-icon employee-icon">

                {{ strtoupper(
                    substr(
                        $employee->user?->name ?? 'K',
                        0,
                        1
                    )
                ) }}

            </div>


            <div class="organization-content">

                <span class="organization-label">

                    {{ $roleLabel }}

                </span>


                <span class="organization-name current-name">

                    {{ $employee->user?->name ?? '-' }}

                </span>


                <span class="organization-nik">

                    NIK: {{ $employee->nik ?? '-' }}

                </span>

            </div>

        </div>


    </div>

</section>

    {{-- =========================================================
        TIMESTAMP
    ========================================================== --}}

    <section class="detail-card">

        <div class="detail-card-header">

            <div class="section-icon">
                🕒
            </div>

            <div>

                <h2>
                    Informasi Sistem
                </h2>

                <p>
                    Informasi pencatatan data karyawan.
                </p>

            </div>

        </div>


        <div class="detail-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Data Dibuat
                </span>

                <span class="detail-value">

                    {{ $employee->created_at
                        ? $employee->created_at->format('d F Y, H:i')
                        : '-' }}

                </span>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Terakhir Diperbarui
                </span>

                <span class="detail-value">

                    {{ $employee->updated_at
                        ? $employee->updated_at->format('d F Y, H:i')
                        : '-' }}

                </span>

            </div>

        </div>

    </section>


    {{-- =========================================================
        ACTION
    ========================================================== --}}

    <div class="bottom-actions">

        <a
            href="{{ route('hrd.employees.index') }}"
            class="btn-cancel"
        >
            ← Kembali
        </a>


        <div class="bottom-actions-right">

            <a
                href="{{ route('hrd.employees.edit', $employee) }}"
                class="btn-edit-large"
            >
                ✎
                Edit Karyawan
            </a>


            <form
            action="{{ route('hrd.employees.destroy', $employee) }}"
            method="POST"
            class="delete-employee-form"
        >
            @csrf

            @method('DELETE')

            <button
                type="submit"
                class="btn-delete"
            >
                <span class="btn-delete-icon">🗑</span>
                <span>Hapus Karyawan</span>
            </button>

        </form>

        </div>

    </div>

</div>

@endsection


@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/hrd/employees/show.css') }}"
>

@endpush

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const deleteForm = document.querySelector('.delete-employee-form');

    if (!deleteForm) {
        return;
    }

    deleteForm.addEventListener('submit', function (event) {

        event.preventDefault();

        Swal.fire({

            title: 'Hapus Karyawan?',

            html: `
                <div class="delete-alert-content">

                    <div class="delete-alert-icon">
                        🗑
                    </div>

                    <p class="delete-alert-text">
                        Anda akan menghapus data karyawan:
                    </p>

                    <strong class="delete-alert-name">
                        {{ $employee->user?->name ?? 'Karyawan' }}
                    </strong>

                    <p class="delete-alert-warning">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>

                </div>
            `,

            showCancelButton: true,

            confirmButtonText: 'Ya, Hapus',

            cancelButtonText: 'Batal',

            reverseButtons: true,

            focusCancel: true,

            customClass: {

                popup: 'delete-swal-popup',

                title: 'delete-swal-title',

                htmlContainer: 'delete-swal-html',

                confirmButton: 'delete-swal-confirm',

                cancelButton: 'delete-swal-cancel'

            },

            buttonsStyling: false

        }).then((result) => {

            if (result.isConfirmed) {

                deleteForm.submit();

            }

        });

    });

});

</script>

@endpush