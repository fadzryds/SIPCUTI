@extends('hrd.layouts.app')

@section('title', 'Pengelolaan Saldo Cuti')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/hrd/leave-balances.css') }}"
    >
@endpush

@section('content')

<div class="leave-balance-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="leave-balance-header">

        <div class="leave-balance-header-content">

            <div class="leave-balance-eyebrow">
                <i class="fas fa-calendar-check"></i>
                HRD MANAGEMENT
            </div>

            <h1>
                Pengelolaan Saldo Cuti
            </h1>

            <p>
                Kelola hak, penggunaan, dan sisa cuti tahunan
                setiap karyawan dalam satu tahun.
            </p>

        </div>

        <div class="leave-balance-year">

            <span>
                TAHUN
            </span>

            <strong>
                {{ $year }}
            </strong>

        </div>

    </div>


    {{-- =====================================================
         ALERT SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="balance-alert success">

            <div class="alert-icon">
                <i class="fas fa-check"></i>
            </div>

            <div>
                <strong>
                    Berhasil
                </strong>

                <p>
                    {{ session('success') }}
                </p>
            </div>

        </div>

    @endif


    {{-- =====================================================
         ALERT ERROR
    ====================================================== --}}

    @if($errors->any())

        <div class="balance-alert error">

            <div class="alert-icon">
                <i class="fas fa-exclamation"></i>
            </div>

            <div>

                <strong>
                    Terjadi Kesalahan
                </strong>

                <p>
                    Silakan periksa kembali data yang dimasukkan.
                </p>

            </div>

        </div>

    @endif


    {{-- =====================================================
         FILTER
    ====================================================== --}}

    <div class="balance-filter-card">

        <div class="filter-title">

            <div class="filter-title-icon">
                <i class="fas fa-sliders-h"></i>
            </div>

            <div>

                <strong>
                    Filter Data
                </strong>

                <span>
                    Cari karyawan atau sesuaikan data saldo cuti.
                </span>

            </div>

        </div>


        <form
            method="GET"
            action="{{ route('hrd.leave-balances.index') }}"
            class="balance-filter-form"
        >

            {{-- SEARCH --}}

            <div class="filter-group search-group">
            
                <div class="input-icon">
            
                    <i class="fas fa-search"></i>
            
                    <input
                        id="leave-balance-search"
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari NIK atau nama karyawan..."
                        autocomplete="off"
                    >
            
                </div>
            
            </div>


            {{-- YEAR --}}

            <div class="filter-group">

                <label for="leave-balance-year">
                    Tahun
                </label>

                <select
                    id="leave-balance-year"
                    name="year"
                >

                    @for(
                        $y = now()->year - 2;
                        $y <= now()->year + 2;
                        $y++
                    )

                        <option
                            value="{{ $y }}"
                            @selected($year == $y)
                        >
                            {{ $y }}
                        </option>

                    @endfor

                </select>

            </div>


            {{-- DEPARTMENT --}}

            <div class="filter-group">

                <label for="leave-balance-department">
                    Department
                </label>

                <select
                    id="leave-balance-department"
                    name="department_id"
                >

                    <option value="">
                        Semua Department
                    </option>

                    @foreach($departments as $department)

                        <option
                            value="{{ $department->id }}"
                            @selected(
                                $departmentId == $department->id
                            )
                        >
                            {{ $department->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- TYPE --}}

            <div class="filter-group">

                <label>
                    Jenis Saldo
                </label>

                <div class="fixed-filter-value">

                    <i class="fas fa-calendar-alt"></i>

                    Cuti Tahunan

                </div>

            </div>


            {{-- ACTION --}}

            <div class="filter-actions">

                <button
                    type="submit"
                    class="btn-filter"
                >

                    <i class="fas fa-filter"></i>

                    Terapkan

                </button>


                <a
                    href="{{ route('hrd.leave-balances.index') }}"
                    class="btn-reset"
                >

                    <i class="fas fa-undo"></i>

                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="balance-table-card">

        <div class="table-card-header">

            <div>

                <h2>
                    Daftar Saldo Cuti
                </h2>

                <p>
                    Saldo cuti tahunan setiap karyawan
                    tahun {{ $year }}.
                </p>

            </div>

            <div class="record-count">

                <i class="fas fa-users"></i>

                {{ $employees->total() }}

                karyawan

            </div>

        </div>


        <div class="balance-table-wrapper">

            <table class="balance-table">

                <thead>

                    <tr>

                        <th class="col-no">
                            #
                        </th>

                        <th>
                            KARYAWAN
                        </th>

                        <th>
                            DEPARTMENT
                        </th>

                        <th class="center">
                            HAK CUTI
                        </th>

                        <th class="center">
                            TERPAKAI
                        </th>

                        <th class="center">
                            SISA
                        </th>

                        <th class="center">
                            CARRY FORWARD
                        </th>

                        <th class="center">
                            STATUS
                        </th>

                        <th class="center">
                            AKSI
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse(
                        $employees
                        as $index => $employee
                    )

                        @php

                            $quota =
                                (int) $employee->leave_quota;

                            $used =
                                (int) $employee->leave_used;

                            $remaining =
                                (int) $employee->leave_remaining;

                            $carryForward =
                                (int) $employee->leave_carry_forward;

                            $percentage =
                                (int) $employee->leave_percentage;

                        @endphp


                        <tr>

                            {{-- NO --}}

                            <td class="col-no">

                                {{
                                    $employees->firstItem()
                                    + $index
                                }}

                            </td>


                            {{-- EMPLOYEE --}}

                            <td>

                                <div class="employee-cell">

                                    <div class="employee-avatar">

                                        {{
                                            strtoupper(
                                                substr(
                                                    $employee
                                                        ->user
                                                        ?->name
                                                        ?? 'K',
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </div>


                                    <div class="employee-info">

                                        <strong>

                                            {{
                                                $employee
                                                    ->user
                                                    ?->name
                                                    ?? 'Tidak diketahui'
                                            }}

                                        </strong>

                                        <span>

                                            NIK:

                                            {{
                                                $employee->nik
                                                ?? '-'
                                            }}

                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- DEPARTMENT --}}

                            <td>

                                <span class="department-badge">

                                    {{
                                        $employee
                                            ->department
                                            ?->name
                                            ?? '-'
                                    }}

                                </span>

                            </td>


                            {{-- QUOTA --}}

                            <td class="center">

                                <div class="balance-value">

                                    <strong class="quota-number">
                                        {{ $quota }}
                                    </strong>

                                    <span>
                                        hari
                                    </span>

                                </div>

                            </td>


                            {{-- USED --}}

                            <td class="center">

                                <div class="balance-value">

                                    <strong class="used-number">
                                        {{ $used }}
                                    </strong>

                                    <span>
                                        hari
                                    </span>

                                </div>

                            </td>


                            {{-- REMAINING --}}

                            <td class="center">

                                <div class="remaining-cell">

                                    <strong
                                        class="
                                            @if($remaining <= 2)
                                                danger
                                            @elseif($remaining <= 5)
                                                warning
                                            @else
                                                safe
                                            @endif
                                        "
                                    >
                                        {{ $remaining }}
                                    </strong>

                                    <span>
                                        hari
                                    </span>

                                </div>

                            </td>


                            {{-- CARRY FORWARD --}}

                            <td class="center">

                                <div class="balance-value">

                                    <strong class="carry-number">
                                        {{ $carryForward }}
                                    </strong>

                                    <span>
                                        hari
                                    </span>

                                </div>

                            </td>


                            {{-- STATUS --}}

                            <td class="center">

                                @if($remaining <= 0)

                                    <span class="usage-status danger">

                                        <i class="fas fa-circle"></i>

                                        Habis

                                    </span>

                                @elseif($percentage >= 75)

                                    <span class="usage-status warning">

                                        <i class="fas fa-circle"></i>

                                        Hampir Habis

                                    </span>

                                @else

                                    <span class="usage-status safe">

                                        <i class="fas fa-circle"></i>

                                        Tersedia

                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}

                            <td class="center">

                                @if($employee->leave_balance)

                                    <a
                                        href="{{
                                            route(
                                                'hrd.leave-balances.edit',
                                                $employee->leave_balance
                                            )
                                        }}"
                                        class="btn-edit-balance"
                                        title="Edit saldo cuti"
                                    >

                                        <i class="fas fa-pen"></i>

                                    </a>

                                @else

                                    <span class="btn-edit-disabled">

                                        <i class="fas fa-lock"></i>

                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="empty-state"
                            >

                                <div class="empty-icon">

                                    <i class="fas fa-calendar-times"></i>

                                </div>

                                <strong>
                                    Data karyawan tidak ditemukan
                                </strong>

                                <span>
                                    Tidak ada karyawan yang sesuai
                                    dengan filter yang dipilih.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =================================================
             PAGINATION
        ================================================== --}}

        @if($employees->hasPages())

            <div class="balance-pagination">

                <div class="pagination-info">

                    <span>
                        Menampilkan
                    </span>

                    <strong>
                        {{ $employees->firstItem() }}
                    </strong>

                    <span>
                        -
                    </span>

                    <strong>
                        {{ $employees->lastItem() }}
                    </strong>

                    <span>
                        dari
                    </span>

                    <strong>
                        {{ $employees->total() }}
                    </strong>

                    <span>
                        karyawan
                    </span>

                </div>


                <div class="pagination-nav">

                    {{ $employees->links() }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection