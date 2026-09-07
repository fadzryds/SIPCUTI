@extends('hrd.layouts.app')

@section('title', 'Data Employees')

@section('content')

<div class="hrd-page">

    {{-- =========================================================
    PAGE HEADER
========================================================= --}}

<div class="page-header">

    {{-- =====================================================
        HEADER CONTENT
    ===================================================== --}}

    <div class="page-header-content">

        <div class="page-header-icon">
            <i class="fas fa-users"></i>
        </div>

        <div>

            <span class="page-header-label">
                MASTER DATA
            </span>

            <h1>
                Data Employees
            </h1>

            <p>
                Kelola seluruh data karyawan dalam sistem.
            </p>

        </div>

    </div>


    {{-- =====================================================
        HEADER ACTIONS
    ===================================================== --}}

    <div class="page-header-actions">

        {{-- IMPORT EMPLOYEE --}}
        <a
            href="{{ route('hrd.employees.import.form') }}"
            class="btn-import"
        >
            <i class="fas fa-file-import"></i>
            <span>Import Employee</span>
        </a>


        {{-- TAMBAH EMPLOYEE --}}
        <a
            href="{{ route('hrd.employees.create') }}"
            class="btn-primary"
        >
            <i class="fas fa-plus"></i>
            <span>Tambah Employee</span>
        </a>

    </div>

</div>


    {{-- =========================================================
        ALERT SUCCESS
    ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">

            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>

            <div class="alert-content">

                <strong>
                    Berhasil
                </strong>

                <span>
                    {{ session('success') }}
                </span>

            </div>

            <button
                type="button"
                class="alert-close"
                onclick="this.parentElement.remove()"
            >
                &times;
            </button>

        </div>

    @endif


    {{-- =========================================================
        ALERT ERROR
    ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-error">

            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <div class="alert-content">

                <strong>
                    Terjadi Kesalahan
                </strong>

                <span>
                    {{ session('error') }}
                </span>

            </div>

            <button
                type="button"
                class="alert-close"
                onclick="this.parentElement.remove()"
            >
                &times;
            </button>

        </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================= --}}

    <div class="employee-statistics">

        {{-- TOTAL --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon total">
                <i class="fas fa-users"></i>
            </div>

            <div class="employee-stat-content">

                <span>
                    Total Employees
                </span>

                <h2>
                    {{ $totalEmployees }}
                </h2>

                <small>
                    Seluruh karyawan
                </small>

            </div>

        </div>


        {{-- ACTIVE --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon active">
                <i class="fas fa-user-check"></i>
            </div>

            <div class="employee-stat-content">

                <span>
                    Employee Aktif
                </span>

                <h2>
                    {{ $activeEmployees }}
                </h2>

                <small>
                    Karyawan aktif
                </small>

            </div>

        </div>


        {{-- INACTIVE --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon inactive">
                <i class="fas fa-user-slash"></i>
            </div>

            <div class="employee-stat-content">

                <span>
                    Employee Tidak Aktif
                </span>

                <h2>
                    {{ $inactiveEmployees }}
                </h2>

                <small>
                    Tidak aktif
                </small>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TABLE CARD
    ========================================================= --}}

    <div class="employee-table-card">


        {{-- =====================================================
            TABLE HEADER
        ===================================================== --}}

        <div class="table-card-header">

            <div>

                <h2>
                    Daftar Employees
                </h2>

                <span>
                    Kelola data karyawan yang terdaftar.
                </span>

            </div>

            <div class="table-count">

                <i class="fas fa-database"></i>

                {{ $employees->total() }} Data

            </div>

        </div>


        {{-- =====================================================
            FILTER AREA
        ===================================================== --}}

        <div class="employee-filter-wrapper">

            <form
                method="GET"
                action="{{ route('hrd.employees.index') }}"
                class="employee-filter-form"
            >

                {{-- SEARCH --}}
                <div class="employee-search">

                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari NIK, nama, email, atau nomor HP..."
                    >

                    @if(request('search'))

                        <a
                            href="{{ route('hrd.employees.index') }}"
                            class="search-clear"
                            title="Reset pencarian"
                        >
                            <i class="fas fa-times"></i>
                        </a>

                    @endif

                </div>


                {{-- DEPARTMENT --}}
                <div class="employee-filter">

                    <i class="fas fa-building"></i>

                    <select
                        name="department_id"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            Semua Department
                        </option>

                        @foreach($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                @selected(
                                    request('department_id') == $department->id
                                )
                            >
                                {{ $department->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- POSITION --}}
                <div class="employee-filter">

                    <i class="fas fa-briefcase"></i>

                    <select
                        name="position_id"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            Semua Position
                        </option>

                        @foreach($positions as $position)

                            <option
                                value="{{ $position->id }}"
                                @selected(
                                    request('position_id') == $position->id
                                )
                            >
                                {{ $position->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- STATUS --}}
                <div class="employee-filter">

                    <i class="fas fa-toggle-on"></i>

                    <select
                        name="status"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="Active"
                            @selected(request('status') === 'Active')
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            @selected(request('status') === 'Inactive')
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- SEARCH BUTTON --}}
                <button
                    type="submit"
                    class="btn-filter"
                >
                    <i class="fas fa-search"></i>
                    Cari
                </button>


                {{-- RESET --}}
                @if(
                    request('search') ||
                    request('department_id') ||
                    request('position_id') ||
                    request('status')
                )

                    <a
                        href="{{ route('hrd.employees.index') }}"
                        class="btn-reset"
                    >
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>

                @endif

            </form>

        </div>


        {{-- =====================================================
            TABLE
        ===================================================== --}}

        <div class="employee-table-wrapper">

            <table class="employee-table">

                <thead>

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Employee
                        </th>

                        <th>
                            NIK
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Position
                        </th>

                        <th>
                            Manager
                        </th>

                        <th>
                            Status
                        </th>

                        <th width="150">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($employees as $employee)

                        <tr>

                            {{-- NO --}}
                            <td>

                                <span class="row-number">
                                    {{ $employees->firstItem() + $loop->index }}
                                </span>

                            </td>


                            {{-- EMPLOYEE --}}
                            <td>

                                <div class="employee-info">

                                    <div class="employee-avatar">

                                        @if(
                                            $employee->user &&
                                            $employee->user->name
                                        )

                                            {{ strtoupper(
                                                substr(
                                                    $employee->user->name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        @else

                                            <i class="fas fa-user"></i>

                                        @endif

                                    </div>

                                    <div class="employee-details">

                                        <strong>

                                            {{ $employee->user->name ?? '-' }}

                                        </strong>

                                        <span>

                                            {{ $employee->user->email ?? '-' }}

                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- NIK --}}
                            <td>

                                <span class="nik-text">

                                    {{ $employee->nik ?? '-' }}

                                </span>

                            </td>


                            {{-- DEPARTMENT --}}
                            <td>

                                @if($employee->department)

                                    <div class="relation-info">

                                        <span>
                                            {{ $employee->department->name }}
                                        </span>

                                        @if($employee->department->code)

                                            <small>
                                                {{ $employee->department->code }}
                                            </small>

                                        @endif

                                    </div>

                                @else

                                    <span class="empty-value">
                                        Belum ditentukan
                                    </span>

                                @endif

                            </td>


                            {{-- POSITION --}}
                            <td>

                                @if($employee->position)

                                    <div class="relation-info">

                                        <span>
                                            {{ $employee->position->name }}
                                        </span>

                                        @if($employee->position->code)

                                            <small>
                                                {{ $employee->position->code }}
                                            </small>

                                        @endif

                                    </div>

                                @else

                                    <span class="empty-value">
                                        Belum ditentukan
                                    </span>

                                @endif

                            </td>


                            {{-- MANAGER --}}
                            <td>

                                @if(
                                    $employee->manager &&
                                    $employee->manager->user
                                )

                                    <div class="manager-info">

                                        <div class="manager-avatar">

                                            {{
                                                strtoupper(
                                                    substr(
                                                        $employee->manager->user->name,
                                                        0,
                                                        1
                                                    )
                                                )
                                            }}

                                        </div>

                                        <span>

                                            {{
                                                $employee->manager->user->name
                                            }}

                                        </span>

                                    </div>

                                @else

                                    <span class="empty-value">
                                        Belum ditentukan
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @if($employee->status === 'Active')

                                    <span class="status-badge active">

                                        <span class="status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="status-badge inactive">

                                        <span class="status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}
                            <td>

                                <div class="employee-actions">

                                    {{-- VIEW --}}
                                    <a
                                        href="{{ route(
                                            'hrd.employees.show',
                                            $employee
                                        ) }}"
                                        class="employee-action view"
                                        title="Lihat detail"
                                    >

                                        <i class="fas fa-eye"></i>

                                    </a>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route(
                                            'hrd.employees.edit',
                                            $employee
                                        ) }}"
                                        class="employee-action edit"
                                        title="Edit employee"
                                    >

                                        <i class="fas fa-pen"></i>

                                    </a>

                                    {{-- DELETE --}}
                    <form
                        action="{{ route('hrd.employees.destroy', $employee) }}"
                        method="POST"
                        class="delete-employee-form"
                        data-name="{{ $employee->user?->name ?? 'Karyawan' }}"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="employee-action delete"
                            title="Hapus employee"
                        >

                            <i class="fas fa-trash"></i>

                        </button>

                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="empty-table-cell"
                            >

                                <div class="employee-empty-state">

                                    <div class="empty-icon">

                                        <i class="fas fa-users-slash"></i>

                                    </div>

                                    <h3>
                                        Data employee tidak ditemukan
                                    </h3>

                                    <p>

                                        @if(
                                            request('search') ||
                                            request('department_id') ||
                                            request('position_id') ||
                                            request('status')
                                        )

                                            Tidak ada employee yang sesuai
                                            dengan filter yang dipilih.

                                        @else

                                            Belum ada data employee
                                            yang terdaftar.

                                        @endif

                                    </p>

                                    @if(
                                        request('search') ||
                                        request('department_id') ||
                                        request('position_id') ||
                                        request('status')
                                    )

                                        <a
                                            href="{{ route(
                                                'hrd.employees.index'
                                            ) }}"
                                            class="btn-reset-empty"
                                        >
                                            <i class="fas fa-rotate-left"></i>
                                            Reset Filter
                                        </a>

                                    @else

                                        <a
                                            href="{{ route(
                                                'hrd.employees.create'
                                            ) }}"
                                            class="btn-primary"
                                        >
                                            <i class="fas fa-plus"></i>
                                            Tambah Employee
                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PAGINATION
        ===================================================== --}}

        @if($employees->hasPages())

            <div class="employee-pagination">

                <div class="pagination-info">

                    Menampilkan

                    <strong>
                        {{ $employees->firstItem() }}
                    </strong>

                    sampai

                    <strong>
                        {{ $employees->lastItem() }}
                    </strong>

                    dari

                    <strong>
                        {{ $employees->total() }}
                    </strong>

                    employee

                </div>

                <div>

                    {{ $employees->links() }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DELETE EMPLOYEE CONFIRMATION
    |--------------------------------------------------------------------------
    */

    const deleteForms = document.querySelectorAll('.delete-employee-form');

    deleteForms.forEach(function (deleteForm) {

        deleteForm.addEventListener('submit', function (event) {

            event.preventDefault();

            const employeeName =
                deleteForm.dataset.name || 'Karyawan';


            Swal.fire({

                title: 'Hapus Karyawan?',

                html: `
                    <div class="delete-alert-content">

                        <div class="delete-alert-icon">
                            <i class="fas fa-trash-alt"></i>
                        </div>

                        <p class="delete-alert-text">
                            Anda akan menghapus data karyawan:
                        </p>

                        <strong class="delete-alert-name">
                            ${employeeName}
                        </strong>

                        <p class="delete-alert-warning">
                            Tindakan ini tidak dapat dibatalkan.
                        </p>

                    </div>
                `,

                showCancelButton: true,

                confirmButtonText: `
                    <i class="fas fa-trash"></i>
                    Ya, Hapus
                `,

                cancelButtonText: `
                    <i class="fas fa-times"></i>
                    Batal
                `,

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

            }).then(function (result) {

                if (result.isConfirmed) {

                    deleteForm.submit();

                }

            });

        });

    });

});

</script>

@endpush