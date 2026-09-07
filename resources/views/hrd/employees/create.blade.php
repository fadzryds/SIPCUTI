@extends('hrd.layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')

<div class="employee-create-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('hrd.employees.index') }}">
                    Karyawan
                </a>

                <span>/</span>

                <span>Tambah Karyawan</span>
            </div>

            <h1>Tambah Karyawan</h1>

            <p>
                Tambahkan data karyawan baru ke dalam sistem.
            </p>
        </div>

        <a href="{{ route('hrd.employees.index') }}" class="btn-back">
            <span>←</span>
            Kembali
        </a>
    </div>


    {{-- ERROR --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="alert-icon">
                !
            </div>

            <div>
                <strong>Data belum dapat disimpan.</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    @endif


    {{-- FORM --}}
    <form
        action="{{ route('hrd.employees.store') }}"
        method="POST"
        class="employee-form"
    >

        @csrf


        {{-- =========================================================
            DATA PERSONAL
        ========================================================== --}}

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    👤
                </div>

                <div>
                    <h2>Data Personal</h2>

                    <p>
                        Informasi dasar karyawan.
                    </p>
                </div>

            </div>


            <div class="form-grid">

                {{-- NIK --}}
                <div class="form-group">

                    <label for="nik">
                        NIK
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="nik"
                        name="nik"
                        value="{{ old('nik') }}"
                        placeholder="Contoh: EMP003"
                        required
                    >

                    <small>
                        NIK harus unik dan belum terdaftar di database.
                    </small>

                </div>


                {{-- NAME --}}
                <div class="form-group">

                    <label for="name">
                        Nama Lengkap
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap"
                        required
                    >

                </div>


                {{-- EMAIL --}}
                <div class="form-group">

                    <label for="email">
                        Email
                        <span>*</span>
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@gmail.com"
                        required
                    >

                </div>


                {{-- PHONE --}}
                <div class="form-group">

                    <label for="phone">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="081234567890"
                    >

                </div>


                {{-- BIRTH DATE --}}
                <div class="form-group">

                    <label for="birth_date">
                        Tanggal Lahir
                    </label>

                    <input
                        type="date"
                        id="birth_date"
                        name="birth_date"
                        value="{{ old('birth_date') }}"
                    >

                </div>


                {{-- GENDER --}}
                <div class="form-group">

                    <label for="gender">
                        Jenis Kelamin
                    </label>

                    <select
                        id="gender"
                        name="gender"
                    >

                        <option value="">
                            Pilih jenis kelamin
                        </option>

                        <option
                            value="Male"
                            @selected(old('gender') === 'Male')
                        >
                            Male
                        </option>

                        <option
                            value="Female"
                            @selected(old('gender') === 'Female')
                        >
                            Female
                        </option>

                    </select>

                </div>


                {{-- ADDRESS --}}
                <div class="form-group form-group-full">

                    <label for="address">
                        Alamat
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        placeholder="Masukkan alamat lengkap"
                    >{{ old('address') }}</textarea>

                </div>

            </div>

        </section>


        {{-- =========================================================
            AKUN & ROLE
        ========================================================== --}}

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    🔐
                </div>

                <div>
                    <h2>Akun & Role</h2>

                    <p>
                        Informasi login dan hak akses karyawan.
                    </p>
                </div>

            </div>


            <div class="form-grid">

                {{-- PASSWORD --}}
                <div class="form-group">

                    <label for="password">
                        Password
                        <span>*</span>
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        required
                    >

                    <small>
                        Password minimal 8 karakter.
                    </small>

                </div>

                {{-- PASSWORD CONFIRMATION --}}
                <div class="form-group">
                
                    <label for="password_confirmation">
                        Konfirmasi Password
                        <span>*</span>
                    </label>
                
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        required
                    >
                
                    <small>
                        Masukkan kembali password untuk memastikan password benar.
                    </small>
                
                </div>

                {{-- ROLE --}}
                <div class="form-group">

                    <label for="role">
                        Role
                        <span>*</span>
                    </label>

                    <select
                        id="role"
                        name="role"
                        required
                    >

                        <option value="">
                            Pilih role
                        </option>

                        <option
                            value="Employee"
                            @selected(old('role') === 'Employee')
                        >
                            Employee
                        </option>

                        <option
                            value="Supervisor"
                            @selected(old('role') === 'Supervisor')
                        >
                            Supervisor
                        </option>

                        <option
                            value="Manager"
                            @selected(old('role') === 'Manager')
                        >
                            Manager
                        </option>

                        <option
                            value="Director"
                            @selected(old('role') === 'Director')
                        >
                            Director
                        </option>

                    </select>

                </div>

            </div>

        </section>


        {{-- =========================================================
            DATA PEKERJAAN
        ========================================================== --}}

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    💼
                </div>

                <div>
                    <h2>Data Pekerjaan</h2>

                    <p>
                        Department, posisi dan tanggal bergabung.
                    </p>
                </div>

            </div>


            <div class="form-grid">

                {{-- DEPARTMENT --}}
                <div class="form-group">

                    <label for="department_id">
                        Department
                    </label>

                    <select
                        id="department_id"
                        name="department_id"
                    >

                        <option value="">
                            Pilih department
                        </option>

                        @foreach ($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                @selected(
                                    old('department_id') == $department->id
                                )
                            >
                                {{ $department->code }}
                                — {{ $department->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- POSITION --}}
                <div class="form-group">

                    <label for="position_id">
                        Position
                    </label>

                    <select
                        id="position_id"
                        name="position_id"
                    >

                        <option value="">
                            Pilih position
                        </option>

                        @foreach ($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(
                                    old('position_id') == $position->id
                                )
                            >
                                {{ $position->code }}
                                — {{ $position->name }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Satu position dapat digunakan oleh lebih dari satu karyawan.
                    </small>

                </div>


                {{-- JOIN DATE --}}
                <div class="form-group">

                    <label for="join_date">
                        Tanggal Bergabung
                    </label>

                    <input
                        type="date"
                        id="join_date"
                        name="join_date"
                        value="{{ old('join_date') }}"
                    >

                </div>


                {{-- STATUS --}}
                <div class="form-group">

                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                    >

                        <option
                            value="Active"
                            @selected(old('status', 'Active') === 'Active')
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            @selected(old('status') === 'Inactive')
                        >
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

        </section>


        {{-- =========================================================
            HIERARCHY
        ========================================================== --}}

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    🏢
                </div>

                <div>
                    <h2>Struktur Organisasi</h2>

                    <p>
                        Tentukan hubungan atasan berdasarkan NIK.
                    </p>
                </div>

            </div>


            <div class="hierarchy-info">

                <div class="hierarchy-info-icon">
                    ℹ
                </div>

                <div>
                    <strong>Relasi menggunakan NIK</strong>

                    <p>
                        Atasan yang sudah terdaftar di database tidak perlu
                        dibuat ulang. Pilih NIK yang sudah tersedia.
                    </p>
                </div>

            </div>


            <div class="form-grid">

                {{-- SUPERVISOR --}}
                <div class="form-group">

                    <label for="supervisor_id">
                        Supervisor
                    </label>

                    <select
                        id="supervisor_id"
                        name="supervisor_id"
                    >

                        <option value="">
                            Pilih Supervisor
                        </option>

                        @foreach ($supervisors as $supervisor)

                            <option
                                value="{{ $supervisor->id }}"
                                @selected(
                                    old('supervisor_id') == $supervisor->id
                                )
                            >
                                {{ $supervisor->nik }}
                                — {{ $supervisor->user->name ?? '-' }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Untuk Employee, Supervisor wajib diisi.
                    </small>

                </div>


                {{-- MANAGER --}}
                <div class="form-group">

                    <label for="manager_id">
                        Manager
                    </label>

                    <select
                        id="manager_id"
                        name="manager_id"
                    >

                        <option value="">
                            Pilih Manager
                        </option>

                        @foreach ($managers as $manager)

                            <option
                                value="{{ $manager->id }}"
                                @selected(
                                    old('manager_id') == $manager->id
                                )
                            >
                                {{ $manager->nik }}
                                — {{ $manager->user->name ?? '-' }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Untuk Supervisor, Manager wajib diisi.
                    </small>

                </div>


                {{-- DIRECTOR --}}
                <div class="form-group">

                    <label for="director_id">
                        Director
                    </label>

                    <select
                        id="director_id"
                        name="director_id"
                    >

                        <option value="">
                            Pilih Director
                        </option>

                        @foreach ($directors as $director)

                            <option
                                value="{{ $director->id }}"
                                @selected(
                                    old('director_id') == $director->id
                                )
                            >
                                {{ $director->nik }}
                                — {{ $director->user->name ?? '-' }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Untuk Manager, Director wajib diisi.
                    </small>

                </div>

            </div>

        </section>


        {{-- =========================================================
            ACTION
        ========================================================== --}}

        <div class="form-actions">

            <a
                href="{{ route('hrd.employees.index') }}"
                class="btn-cancel"
            >
                Batal
            </a>

            <button
                type="submit"
                class="btn-submit"
            >
                <span>✓</span>
                Simpan Karyawan
            </button>

        </div>

    </form>

</div>

@endsection


@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/hrd/employees/create.css') }}"
>

@endpush