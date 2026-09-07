@extends('hrd.layouts.app')

@section('title', 'Data Positions')

@section('content')

<div class="hrd-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon">
                <i class="fas fa-user-tie"></i>
            </div>

            <div>

                <span class="page-header-label">
                    MASTER DATA
                </span>

                <h1>
                    Data Positions
                </h1>

                <p>
                    Kelola seluruh data position dalam sistem.
                </p>

            </div>

        </div>


        <div class="page-header-actions">

            <a
                href="{{ route('hrd.positions.create') }}"
                class="btn-primary"
            >
                <i class="fas fa-plus"></i>
                <span>Tambah Position</span>
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS ALERT
    ========================================================== --}}

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
        ERROR ALERT
    ========================================================== --}}

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
    ========================================================== --}}

    <div class="position-statistics">

        {{-- TOTAL --}}

        <div class="position-stat-card">

            <div class="position-stat-icon total">
                <i class="fas fa-user-tie"></i>
            </div>

            <div class="position-stat-content">

                <span>
                    Total Positions
                </span>

                <h2>
                    {{ $totalPositions }}
                </h2>

                <small>
                    Seluruh position
                </small>

            </div>

        </div>


        {{-- ACTIVE --}}

        <div class="position-stat-card">

            <div class="position-stat-icon active">
                <i class="fas fa-user-check"></i>
            </div>

            <div class="position-stat-content">

                <span>
                    Position Aktif
                </span>

                <h2>
                    {{ $activePositions }}
                </h2>

                <small>
                    Position aktif
                </small>

            </div>

        </div>


        {{-- INACTIVE --}}

        <div class="position-stat-card">

            <div class="position-stat-icon inactive">
                <i class="fas fa-user-xmark"></i>
            </div>

            <div class="position-stat-content">

                <span>
                    Position Tidak Aktif
                </span>

                <h2>
                    {{ $inactivePositions }}
                </h2>

                <small>
                    Tidak aktif
                </small>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TABLE CARD
    ========================================================== --}}

    <div class="position-table-card">


        {{-- TABLE HEADER --}}

        <div class="table-card-header">

            <div>

                <h2>
                    Daftar Positions
                </h2>

                <span>
                    Kelola data position yang terdaftar.
                </span>

            </div>

            <div class="table-count">

                <i class="fas fa-database"></i>

                {{ $positions->total() }} Data

            </div>

        </div>


        {{-- =====================================================
            FILTER
        ====================================================== --}}

        <div class="position-filter-wrapper">

            <form
                method="GET"
                action="{{ route('hrd.positions.index') }}"
                class="position-filter-form"
            >

                {{-- SEARCH --}}

                <div class="position-search">

                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode, nama, atau deskripsi position..."
                    >

                    @if(request('search'))

                        <a
                            href="{{ route('hrd.positions.index') }}"
                            class="search-clear"
                            title="Reset pencarian"
                        >
                            <i class="fas fa-times"></i>
                        </a>

                    @endif

                </div>


                {{-- STATUS --}}

                <div class="position-filter">

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
                        href="{{ route('hrd.positions.index') }}"
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
        ====================================================== --}}

        <div class="position-table-wrapper">

            <table class="position-table">

                <thead>

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Position
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

                    @forelse($positions as $position)

                        <tr>

                            {{-- NO --}}

                            <td>

                                <span class="row-number">
                                    {{ $positions->firstItem() + $loop->index }}
                                </span>

                            </td>


                            {{-- POSITION --}}

                            <td>

                                <div class="position-info">

                                    <div class="position-avatar">
                                        <i class="fas fa-user-tie"></i>
                                    </div>

                                    <div class="position-details">

                                        <strong>
                                            {{ $position->name }}
                                        </strong>

                                        <span>
                                            Position
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- CODE --}}

                            <td>

                                <span class="code-text">
                                    {{ $position->code }}
                                </span>

                            </td>


                            {{-- DESCRIPTION --}}

                            <td>

                                @if($position->description)

                                    <span class="description-text">
                                        {{ $position->description }}
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
                                            {{ $position->employees_count }}
                                        </strong>

                                        <span>
                                            Karyawan
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if($position->is_active)

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

                                <div class="position-actions">

                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route(
                                            'hrd.positions.show',
                                            $position
                                        ) }}"
                                        class="position-action view"
                                        title="Lihat detail"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'hrd.positions.edit',
                                            $position
                                        ) }}"
                                        class="position-action edit"
                                        title="Edit position"
                                    >
                                        <i class="fas fa-pen"></i>
                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route(
                                            'hrd.positions.destroy',
                                            $position
                                        ) }}"
                                        method="POST"
                                        class="delete-position-form"
                                        data-name="{{ $position->name }}"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="position-action delete"
                                            title="Hapus position"
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

                                <div class="position-empty-state">

                                    <div class="empty-icon">
                                        <i class="fas fa-user-tie"></i>
                                    </div>

                                    <h3>
                                        Data position tidak ditemukan
                                    </h3>

                                    <p>

                                        @if(
                                            request('search') ||
                                            request('status')
                                        )

                                            Tidak ada position yang sesuai
                                            dengan filter yang dipilih.

                                        @else

                                            Belum ada data position
                                            yang terdaftar.

                                        @endif

                                    </p>


                                    @if(
                                        request('search') ||
                                        request('status')
                                    )

                                        <a
                                            href="{{ route(
                                                'hrd.positions.index'
                                            ) }}"
                                            class="btn-reset-empty"
                                        >
                                            <i class="fas fa-rotate-left"></i>
                                            Reset Filter
                                        </a>

                                    @else

                                        <a
                                            href="{{ route(
                                                'hrd.positions.create'
                                            ) }}"
                                            class="btn-primary"
                                        >
                                            <i class="fas fa-plus"></i>
                                            Tambah Position
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
        ====================================================== --}}

        @if($positions->hasPages())

            <div class="position-pagination">

                <div class="pagination-info">

                    Menampilkan

                    <strong>
                        {{ $positions->firstItem() }}
                    </strong>

                    sampai

                    <strong>
                        {{ $positions->lastItem() }}
                    </strong>

                    dari

                    <strong>
                        {{ $positions->total() }}
                    </strong>

                    position

                </div>

                <div>
                    {{ $positions->links() }}
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

    const deleteForms =
        document.querySelectorAll('.delete-position-form');


    deleteForms.forEach(function (deleteForm) {

        deleteForm.addEventListener('submit', function (event) {

            event.preventDefault();


            const positionName =
                deleteForm.dataset.name || 'Position';


            Swal.fire({

                title: 'Hapus Position?',

                html: `
                    <div class="delete-alert-content">

                        <div class="delete-alert-icon">
                            <i class="fas fa-trash-alt"></i>
                        </div>

                        <p class="delete-alert-text">
                            Anda akan menghapus data position:
                        </p>

                        <strong class="delete-alert-name">
                            ${positionName}
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