@extends('hrd.layouts.app')

@section('title', 'Data Departments')

@section('content')

<div class="hrd-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================= --}}

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon">
                <i class="fas fa-building"></i>
            </div>

            <div>

                <span class="page-header-label">
                    MASTER DATA
                </span>

                <h1>
                    Data Departments
                </h1>

                <p>
                    Kelola seluruh data department dalam sistem.
                </p>

            </div>

        </div>


        {{-- HEADER ACTIONS --}}

        <div class="page-header-actions">

            <a
                href="{{ route('hrd.departments.create') }}"
                class="btn-primary"
            >
                <i class="fas fa-plus"></i>
                <span>Tambah Department</span>
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

    <div class="department-statistics">

        {{-- TOTAL --}}

        <div class="department-stat-card">

            <div class="department-stat-icon total">
                <i class="fas fa-building"></i>
            </div>

            <div class="department-stat-content">

                <span>
                    Total Departments
                </span>

                <h2>
                    {{ $totalDepartments }}
                </h2>

                <small>
                    Seluruh department
                </small>

            </div>

        </div>


        {{-- ACTIVE --}}

        <div class="department-stat-card">

            <div class="department-stat-icon active">
                <i class="fas fa-building-circle-check"></i>
            </div>

            <div class="department-stat-content">

                <span>
                    Department Aktif
                </span>

                <h2>
                    {{ $activeDepartments }}
                </h2>

                <small>
                    Department aktif
                </small>

            </div>

        </div>


        {{-- INACTIVE --}}

        <div class="department-stat-card">

            <div class="department-stat-icon inactive">
                <i class="fas fa-building-circle-xmark"></i>
            </div>

            <div class="department-stat-content">

                <span>
                    Department Tidak Aktif
                </span>

                <h2>
                    {{ $inactiveDepartments }}
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

    <div class="department-table-card">


        {{-- =====================================================
            TABLE HEADER
        ===================================================== --}}

        <div class="table-card-header">

            <div>

                <h2>
                    Daftar Departments
                </h2>

                <span>
                    Kelola data department yang terdaftar.
                </span>

            </div>

            <div class="table-count">

                <i class="fas fa-database"></i>

                {{ $departments->total() }} Data

            </div>

        </div>


        {{-- =====================================================
            FILTER AREA
        ===================================================== --}}

        <div class="department-filter-wrapper">

            <form
                method="GET"
                action="{{ route('hrd.departments.index') }}"
                class="department-filter-form"
            >

                {{-- SEARCH --}}

                <div class="department-search">

                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode, nama, atau deskripsi department..."
                    >

                    @if(request('search'))

                        <a
                            href="{{ route('hrd.departments.index') }}"
                            class="search-clear"
                            title="Reset pencarian"
                        >
                            <i class="fas fa-times"></i>
                        </a>

                    @endif

                </div>


                {{-- STATUS --}}

                <div class="department-filter">

                    <i class="fas fa-toggle-on"></i>

                    <select
                        name="status"
                        onchange="this.form.submit()"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="1"
                            @selected(request('status') === '1')
                        >
                            Active
                        </option>

                        <option
                            value="0"
                            @selected(request('status') === '0')
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

                @if(request('search') || request('status'))

                    <a
                        href="{{ route('hrd.departments.index') }}"
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

        <div class="department-table-wrapper">

            <table class="department-table">

                <thead>

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Code
                        </th>

                        <th>
                            Description
                        </th>

                        <th>
                            Employees
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

                    @forelse($departments as $department)

                        <tr>

                            {{-- NO --}}

                            <td>

                                <span class="row-number">
                                    {{ $departments->firstItem() + $loop->index }}
                                </span>

                            </td>


                            {{-- DEPARTMENT --}}

                            <td>

                                <div class="department-info">

                                    <div class="department-avatar">
                                        <i class="fas fa-building"></i>
                                    </div>

                                    <div class="department-details">

                                        <strong>
                                            {{ $department->name }}
                                        </strong>

                                        <span>
                                            Department
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- CODE --}}

                            <td>

                                <span class="code-text">
                                    {{ $department->code }}
                                </span>

                            </td>


                            {{-- DESCRIPTION --}}

                            <td>

                                @if($department->description)

                                    <span class="description-text">
                                        {{ $department->description }}
                                    </span>

                                @else

                                    <span class="empty-value">
                                        Tidak ada deskripsi
                                    </span>

                                @endif

                            </td>


                            {{-- EMPLOYEES --}}

                            <td>

                                <div class="employee-count">

                                    <div class="employee-count-icon">
                                        <i class="fas fa-users"></i>
                                    </div>

                                    <div class="employee-count-content">

                                        <strong>
                                            {{ $department->employees_count }}
                                        </strong>

                                        <span>
                                            Karyawan
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if($department->is_active)

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

                                <div class="department-actions">

                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route(
                                            'hrd.departments.show',
                                            $department
                                        ) }}"
                                        class="department-action view"
                                        title="Lihat detail"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'hrd.departments.edit',
                                            $department
                                        ) }}"
                                        class="department-action edit"
                                        title="Edit department"
                                    >
                                        <i class="fas fa-pen"></i>
                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route(
                                            'hrd.departments.destroy',
                                            $department
                                        ) }}"
                                        method="POST"
                                        class="delete-department-form"
                                        data-name="{{ $department->name }}"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="department-action delete"
                                            title="Hapus department"
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
                                colspan="7"
                                class="empty-table-cell"
                            >

                                <div class="department-empty-state">

                                    <div class="empty-icon">
                                        <i class="fas fa-building-circle-exclamation"></i>
                                    </div>

                                    <h3>
                                        Data department tidak ditemukan
                                    </h3>

                                    <p>

                                        @if(
                                            request('search') ||
                                            request('status')
                                        )

                                            Tidak ada department yang sesuai
                                            dengan filter yang dipilih.

                                        @else

                                            Belum ada data department
                                            yang terdaftar.

                                        @endif

                                    </p>


                                    @if(
                                        request('search') ||
                                        request('status')
                                    )

                                        <a
                                            href="{{ route(
                                                'hrd.departments.index'
                                            ) }}"
                                            class="btn-reset-empty"
                                        >
                                            <i class="fas fa-rotate-left"></i>
                                            Reset Filter
                                        </a>

                                    @else

                                        <a
                                            href="{{ route(
                                                'hrd.departments.create'
                                            ) }}"
                                            class="btn-primary"
                                        >
                                            <i class="fas fa-plus"></i>
                                            Tambah Department
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

        @if($departments->hasPages())

            <div class="department-pagination">

                <div class="pagination-info">

                    Menampilkan

                    <strong>
                        {{ $departments->firstItem() }}
                    </strong>

                    sampai

                    <strong>
                        {{ $departments->lastItem() }}
                    </strong>

                    dari

                    <strong>
                        {{ $departments->total() }}
                    </strong>

                    department

                </div>

                <div>

                    {{ $departments->links() }}

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
    | DELETE DEPARTMENT CONFIRMATION
    |--------------------------------------------------------------------------
    */

    const deleteForms =
        document.querySelectorAll('.delete-department-form');


    deleteForms.forEach(function (deleteForm) {

        deleteForm.addEventListener('submit', function (event) {

            event.preventDefault();


            const departmentName =
                deleteForm.dataset.name || 'Department';


            Swal.fire({

                title: 'Hapus Department?',

                html: `
                    <div class="delete-alert-content">

                        <div class="delete-alert-icon">
                            <i class="fas fa-trash-alt"></i>
                        </div>

                        <p class="delete-alert-text">
                            Anda akan menghapus data department:
                        </p>

                        <strong class="delete-alert-name">
                            ${departmentName}
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