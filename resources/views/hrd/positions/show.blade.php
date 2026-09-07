@extends('hrd.layouts.app')

@section('title', 'Detail Position')

@section('content')

<div class="hrd-page">

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon">
                <i class="fas fa-briefcase"></i>
            </div>

            <div>

                <span class="page-header-label">
                    MASTER DATA
                </span>

                <h1>
                    Detail Position
                </h1>

                <p>
                    Informasi lengkap position yang terdaftar.
                </p>

            </div>

        </div>

        <div class="page-header-actions">

            <a
                href="{{ route('hrd.positions.index') }}"
                class="btn-back"
            >
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>

            <a
                href="{{ route('hrd.positions.edit', $position) }}"
                class="btn-primary"
            >
                <i class="fas fa-pen"></i>
                Edit Position
            </a>

        </div>

    </div>


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


    <div class="position-detail-grid">


        {{-- MAIN INFORMATION --}}

        <div class="position-detail-card">

            <div class="detail-card-header">

                <div class="detail-profile">

                    <div class="detail-avatar">
                        <i class="fas fa-briefcase"></i>
                    </div>

                    <div>

                        <span>
                            POSITION
                        </span>

                        <h2>
                            {{ $position->name }}
                        </h2>

                    </div>

                </div>


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

            </div>


            <div class="detail-card-body">

                <div class="detail-info-grid">


                    <div class="detail-item">

                        <span class="detail-label">
                            Kode Position
                        </span>

                        <span class="code-text">
                            {{ $position->code }}
                        </span>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Jumlah Karyawan
                        </span>

                        <strong class="detail-value">
                            {{ $position->employees_count }}
                            <small>Karyawan</small>
                        </strong>

                    </div>


                    <div class="detail-item full">

                        <span class="detail-label">
                            Deskripsi
                        </span>

                        <p class="detail-description">

                            @if($position->description)

                                {{ $position->description }}

                            @else

                                <span class="empty-value">
                                    Tidak ada deskripsi.
                                </span>

                            @endif

                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- EMPLOYEE SUMMARY --}}

        <div class="position-summary-card">

            <div class="summary-icon">
                <i class="fas fa-users"></i>
            </div>

            <div>

                <span>
                    TOTAL KARYAWAN
                </span>

                <strong>
                    {{ $position->employees_count }}
                </strong>

                <small>
                    Karyawan menggunakan position ini.
                </small>

            </div>

        </div>

    </div>


    {{-- EMPLOYEES --}}

    <div class="position-employees-card">

        <div class="table-card-header">

            <div>

                <h2>
                    Karyawan
                </h2>

                <span>
                    Daftar karyawan dengan position {{ $position->name }}.
                </span>

            </div>

            <div class="table-count">

                <i class="fas fa-users"></i>

                {{ $position->employees_count }} Karyawan

            </div>

        </div>


        <div class="position-employees-wrapper">

            <table class="position-employees-table">

                <thead>

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Karyawan
                        </th>

                        <th>
                            NIK
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($position->employees as $employee)

                        <tr>

                            <td>

                                <span class="row-number">
                                    {{ $loop->iteration }}
                                </span>

                            </td>

                            <td>

                                <div class="employee-info">

                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($employee->user->name ?? 'K', 0, 1)) }}
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

                            <td>

                                <span class="code-text">
                                    {{ $employee->nik ?? '-' }}
                                </span>

                            </td>

                            <td>

                                {{ $employee->department->name ?? '-' }}

                            </td>

                            <td>

                                @if($employee->status)

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

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="empty-table-cell"
                            >

                                <div class="department-empty-state">

                                    <div class="empty-icon">
                                        <i class="fas fa-users-slash"></i>
                                    </div>

                                    <h3>
                                        Belum ada karyawan
                                    </h3>

                                    <p>
                                        Belum terdapat karyawan yang menggunakan position ini.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection