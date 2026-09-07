@extends('hrd.layouts.app')

@section('title', 'Edit Karyawan')

@section('content')

<div class="employee-edit-page">

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

                <a href="{{ route('hrd.employees.show', $employee) }}">
                    Detail
                </a>

                <span>/</span>

                <span>Edit</span>

            </div>


            <h1>
                Edit Karyawan
            </h1>


            <p>
                Perbarui informasi dan struktur organisasi karyawan.
            </p>

        </div>


        <a
            href="{{ route('hrd.employees.show', $employee) }}"
            class="btn-back"
        >
            <span>←</span>
            Kembali
        </a>

    </div>


    {{-- =========================================================
        SUCCESS
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
        ERROR
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">

            <div class="alert-icon">
                !
            </div>

            <div>

                <strong>
                    Data belum dapat diperbarui.
                </strong>

                <p>
                    {{ session('error') }}
                </p>

            </div>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <div class="alert-icon">
                !
            </div>

            <div>

                <strong>
                    Terdapat kesalahan pada data.
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
        FORM
    ========================================================== --}}

    <form
        action="{{ route('hrd.employees.update', $employee) }}"
        method="POST"
        class="employee-form"
    >

        @csrf

        @method('PUT')


        {{-- =====================================================
            DATA PERSONAL
        ====================================================== --}}

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    👤
                </div>

                <div>

                    <h2>
                        Data Personal
                    </h2>

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
                        value="{{ old('nik', $employee->nik) }}"
                        placeholder="Contoh: EMP003"
                        required
                    >

                    <small>
                        NIK harus unik dan belum digunakan karyawan lain.
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
                        value="{{ old('name', $employee->user?->name) }}"
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
                        value="{{ old('email', $employee->user?->email) }}"
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
                        value="{{ old('phone', $employee->user?->phone) }}"
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
                        value="{{ old(
                            'birth_date',
                            $employee->birth_date?->format('Y-m-d')
                        ) }}"
                    >

                </div>


                {{-- GENDER --}}

                <div class="form-group">

                    <label for="gender">
                        Jenis Kelamin
                        <span>*</span>
                    </label>

                    <select
                        id="gender"
                        name="gender"
                        required
                    >

                        <option value="">
                            Pilih jenis kelamin
                        </option>

                        <option
                            value="Male"
                            @selected(
                                old('gender', $employee->gender) === 'Male'
                            )
                        >
                            Male
                        </option>

                        <option
                            value="Female"
                            @selected(
                                old('gender', $employee->gender) === 'Female'
                            )
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
                    >{{ old('address', $employee->address) }}</textarea>

                </div>

            </div>

        </section>


        {{-- =====================================================
            AKUN & ROLE
        ====================================================== --}}

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    🔐
                </div>

                <div>

                    <h2>
                        Akun & Role
                    </h2>

                    <p>
                        Informasi login dan hak akses karyawan.
                    </p>

                </div>

            </div>


            <div class="form-grid">


                {{-- PASSWORD --}}

                <div class="form-group">

                    <label for="password">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                    >

                    <small>
                        Kosongkan jika password tidak ingin diubah.
                        Minimal 8 karakter jika diisi.
                    </small>

                </div>


                {{-- PASSWORD CONFIRMATION --}}

                <div class="form-group">

                    <label for="password_confirmation">
                        Konfirmasi Password Baru
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password baru"
                    >

                    <small>
                        Wajib sama dengan password baru.
                    </small>

                </div>


                {{-- ROLE --}}

                <div class="form-group">

                    <label for="role">
                        Role
                        <span>*</span>
                    </label>

                    @php

                        $currentRole =
                            old(
                                'role',
                                $employee->user?->getRoleNames()->first()
                                ?? 'Employee'
                            );

                    @endphp

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
                            @selected($currentRole === 'Employee')
                        >
                            Employee
                        </option>

                        <option
                            value="Supervisor"
                            @selected($currentRole === 'Supervisor')
                        >
                            Supervisor
                        </option>

                        <option
                            value="Manager"
                            @selected($currentRole === 'Manager')
                        >
                            Manager
                        </option>

                        <option
                            value="Director"
                            @selected($currentRole === 'Director')
                        >
                            Director
                        </option>

                    </select>

                    <small>
                        Role menentukan posisi karyawan dalam struktur organisasi.
                    </small>

                </div>

            </div>

        </section>


        {{-- =====================================================
            DATA PEKERJAAN
        ====================================================== --}}

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    💼
                </div>

                <div>

                    <h2>
                        Data Pekerjaan
                    </h2>

                    <p>
                        Department, posisi dan status kepegawaian.
                    </p>

                </div>

            </div>


            <div class="form-grid">


                {{-- DEPARTMENT --}}

                <div class="form-group">

                    <label for="department_id">
                        Department
                        <span>*</span>
                    </label>

                    <select
                        id="department_id"
                        name="department_id"
                        required
                    >

                        <option value="">
                            Pilih department
                        </option>

                        @foreach($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                @selected(
                                    old(
                                        'department_id',
                                        $employee->department_id
                                    ) == $department->id
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
                        <span>*</span>
                    </label>

                    <select
                        id="position_id"
                        name="position_id"
                        required
                    >

                        <option value="">
                            Pilih position
                        </option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(
                                    old(
                                        'position_id',
                                        $employee->position_id
                                    ) == $position->id
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
                        <span>*</span>
                    </label>

                    <input
                        type="date"
                        id="join_date"
                        name="join_date"
                        value="{{ old(
                            'join_date',
                            $employee->join_date?->format('Y-m-d')
                        ) }}"
                        required
                    >

                </div>


                {{-- STATUS --}}

                <div class="form-group">

                    <label for="status">
                        Status
                        <span>*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                    >

                        <option
                            value="Active"
                            @selected(
                                old(
                                    'status',
                                    $employee->status
                                ) === 'Active'
                            )
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            @selected(
                                old(
                                    'status',
                                    $employee->status
                                ) === 'Inactive'
                            )
                        >
                            Inactive
                        </option>

                    </select>

                </div>

            </div>

        </section>


        {{-- =====================================================
            STRUKTUR ORGANISASI
        ====================================================== --}}

        <section class="form-card">

            <div class="form-card-header">

                <div class="section-icon">
                    🏢
                </div>

                <div>

                    <h2>
                        Struktur Organisasi
                    </h2>

                    <p>
                        Tentukan hubungan karyawan berdasarkan role.
                    </p>

                </div>

            </div>


            <div class="hierarchy-info">

                <div class="hierarchy-info-icon">
                    ℹ
                </div>

                <div>

                    <strong>
                        Struktur mengikuti role
                    </strong>

                    <p>
                        Employee membutuhkan Supervisor,
                        Supervisor membutuhkan Manager,
                        sedangkan Manager membutuhkan Director.
                    </p>

                </div>

            </div>


            <div class="form-grid">


                {{-- =================================================
                    SUPERVISOR
                ================================================== --}}

                <div
                    class="form-group hierarchy-field hierarchy-supervisor"
                >

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

                        @foreach($supervisors as $supervisor)

                            <option
                                value="{{ $supervisor->id }}"
                                @selected(
                                    old(
                                        'supervisor_id',
                                        $employee->supervisor_id
                                    ) == $supervisor->id
                                )
                            >
                                {{ $supervisor->nik }}
                                —
                                {{ $supervisor->user?->name ?? '-' }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Wajib diisi jika role adalah Employee.
                    </small>

                </div>


                {{-- =================================================
                    MANAGER
                ================================================== --}}

                <div
                    class="form-group hierarchy-field hierarchy-manager"
                >

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

                        @foreach($managers as $manager)

                            <option
                                value="{{ $manager->id }}"
                                @selected(
                                    old(
                                        'manager_id',
                                        $employee->manager_id
                                    ) == $manager->id
                                )
                            >
                                {{ $manager->nik }}
                                —
                                {{ $manager->user?->name ?? '-' }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Wajib diisi jika role adalah Supervisor.
                    </small>

                </div>


                {{-- =================================================
                    DIRECTOR
                ================================================== --}}

                <div
                    class="form-group hierarchy-field hierarchy-director"
                >

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

                        @foreach($directors as $director)

                            <option
                                value="{{ $director->id }}"
                                @selected(
                                    old(
                                        'director_id',
                                        $employee->director_id
                                    ) == $director->id
                                )
                            >
                                {{ $director->nik }}
                                —
                                {{ $director->user?->name ?? '-' }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Wajib diisi jika role adalah Manager.
                    </small>

                </div>

            </div>

        </section>


        {{-- =====================================================
            ACTION
        ====================================================== --}}

        <div class="form-actions">

            <a
                href="{{ route('hrd.employees.show', $employee) }}"
                class="btn-cancel"
            >
                Batal
            </a>


            <button
                type="submit"
                class="btn-submit"
            >

                <span>
                    ✓
                </span>

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

@endsection


@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/hrd/employees/edit.css') }}"
>

@endpush


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const roleSelect =
        document.getElementById('role');

    const supervisorField =
        document.querySelector('.hierarchy-supervisor');

    const managerField =
        document.querySelector('.hierarchy-manager');

    const directorField =
        document.querySelector('.hierarchy-director');

    const supervisorSelect =
        document.getElementById('supervisor_id');

    const managerSelect =
        document.getElementById('manager_id');

    const directorSelect =
        document.getElementById('director_id');


    function updateHierarchy() {

        const role =
            roleSelect.value;


        /*
        |--------------------------------------------------------------------------
        | HIDE ALL
        |--------------------------------------------------------------------------
        */

        supervisorField.style.display = 'none';
        managerField.style.display = 'none';
        directorField.style.display = 'none';


        supervisorSelect.removeAttribute('required');
        managerSelect.removeAttribute('required');
        directorSelect.removeAttribute('required');


        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE
        |--------------------------------------------------------------------------
        */

        if (role === 'Employee') {

            supervisorField.style.display = '';
            managerField.style.display = '';
            directorField.style.display = '';

            supervisorSelect.setAttribute(
                'required',
                'required'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */

        else if (role === 'Supervisor') {

            managerField.style.display = '';

            managerSelect.setAttribute(
                'required',
                'required'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */

        else if (role === 'Manager') {

            directorField.style.display = '';

            directorSelect.setAttribute(
                'required',
                'required'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DIRECTOR
        |--------------------------------------------------------------------------
        */

        else if (role === 'Director') {

            /*
             * Tidak mempunyai atasan.
             */

            supervisorSelect.value = '';
            managerSelect.value = '';
            directorSelect.value = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    updateHierarchy();


    /*
    |--------------------------------------------------------------------------
    | ROLE CHANGE
    |--------------------------------------------------------------------------
    */

    roleSelect.addEventListener(
        'change',
        updateHierarchy
    );

});

</script>

@endpush