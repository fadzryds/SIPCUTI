@extends('hrd.layouts.app')

@section('title', 'Detail Department')

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
                    Detail Department
                </h1>

                <p>
                    Informasi lengkap mengenai department.
                </p>

            </div>

        </div>


        <div class="page-header-actions">

            <a
                href="{{ route('hrd.departments.index') }}"
                class="btn-secondary"
            >
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            <a
                href="{{ route('hrd.departments.edit', $department) }}"
                class="btn-primary"
            >
                <i class="fas fa-pen"></i>
                <span>Edit Department</span>
            </a>

        </div>

    </div>


    {{-- =========================================================
        DEPARTMENT PROFILE
    ========================================================= --}}

    <div class="department-detail-card">

        <div class="department-detail-header">

            <div class="department-detail-identity">

                <div class="department-detail-avatar">
                    <i class="fas fa-building"></i>
                </div>

                <div>

                    <span class="department-detail-label">
                        DEPARTMENT
                    </span>

                    <h2>
                        {{ $department->name }}
                    </h2>

                    <div class="department-detail-code">

                        <i class="fas fa-code"></i>

                        {{ $department->code }}

                    </div>

                </div>

            </div>


            <div>

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

            </div>

        </div>


        {{-- =====================================================
            STATISTICS
        ===================================================== --}}

        <div class="department-detail-statistics">

            <div class="department-detail-stat">

                <div class="department-detail-stat-icon">
                    <i class="fas fa-users"></i>
                </div>

                <div>

                    <span>
                        Total Karyawan
                    </span>

                    <strong>
                        {{ $department->employees->count() }}
                    </strong>

                </div>

            </div>


            <div class="department-detail-stat">

                <div class="department-detail-stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>

                <div>

                    <span>
                        Status
                    </span>

                    <strong>
                        {{ $department->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </strong>

                </div>

            </div>


            <div class="department-detail-stat">

                <div class="department-detail-stat-icon">
                    <i class="fas fa-fingerprint"></i>
                </div>

                <div>

                    <span>
                        Kode
                    </span>

                    <strong>
                        {{ $department->code }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        INFORMATION GRID
    ========================================================= --}}

    <div class="department-detail-grid">


        {{-- =====================================================
            INFORMATION
        ===================================================== --}}

        <div class="department-detail-section">

            <div class="department-detail-section-header">

                <div class="department-detail-section-icon">
                    <i class="fas fa-circle-info"></i>
                </div>

                <div>

                    <h2>
                        Informasi Department
                    </h2>

                    <span>
                        Informasi dasar department.
                    </span>

                </div>

            </div>


            <div class="department-info-list">


                {{-- CODE --}}

                <div class="department-info-item">

                    <span class="department-info-label">
                        <i class="fas fa-code"></i>
                        Kode Department
                    </span>

                    <strong class="department-info-code">
                        {{ $department->code }}
                    </strong>

                </div>


                {{-- NAME --}}

                <div class="department-info-item">

                    <span class="department-info-label">
                        <i class="fas fa-building"></i>
                        Nama Department
                    </span>

                    <strong>
                        {{ $department->name }}
                    </strong>

                </div>


                {{-- STATUS --}}

                <div class="department-info-item">

                    <span class="department-info-label">
                        <i class="fas fa-toggle-on"></i>
                        Status
                    </span>

                    <div>

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

                    </div>

                </div>


                {{-- CREATED --}}

                <div class="department-info-item">

                    <span class="department-info-label">
                        <i class="fas fa-calendar-plus"></i>
                        Dibuat
                    </span>

                    <strong>
                        {{ $department->created_at?->format('d F Y, H:i') ?? '-' }}
                    </strong>

                </div>


                {{-- UPDATED --}}

                <div class="department-info-item">

                    <span class="department-info-label">
                        <i class="fas fa-clock-rotate-left"></i>
                        Terakhir Diperbarui
                    </span>

                    <strong>
                        {{ $department->updated_at?->format('d F Y, H:i') ?? '-' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- =====================================================
            DESCRIPTION
        ===================================================== --}}

        <div class="department-detail-section">

            <div class="department-detail-section-header">

                <div class="department-detail-section-icon">
                    <i class="fas fa-align-left"></i>
                </div>

                <div>

                    <h2>
                        Deskripsi
                    </h2>

                    <span>
                        Penjelasan mengenai department.
                    </span>

                </div>

            </div>


            <div class="department-description-box">

                @if($department->description)

                    <p>
                        {{ $department->description }}
                    </p>

                @else

                    <div class="department-description-empty">

                        <i class="fas fa-file-circle-exclamation"></i>

                        <span>
                            Belum ada deskripsi untuk department ini.
                        </span>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
        EMPLOYEES
    ========================================================= --}}

    <div class="department-employees-card">

        <div class="department-employees-header">

            <div>

                <h2>
                    Karyawan Department
                </h2>

                <span>
                    Daftar karyawan yang terdaftar pada department ini.
                </span>

            </div>

            <div class="table-count">

                <i class="fas fa-users"></i>

                {{ $department->employees->count() }} Karyawan

            </div>

        </div>


        @if($department->employees->count())

            <div class="department-employees-table-wrapper">

                <table class="department-employees-table">

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
                                Jabatan
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($department->employees as $employee)

                            <tr>

                                <td>

                                    <span class="row-number">
                                        {{ $loop->iteration }}
                                    </span>

                                </td>


                                <td>

                                    <div class="department-employee-info">

                                        <div class="department-employee-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <div>

                                            <strong>
                                                {{ $employee->user?->name ?? '-' }}
                                            </strong>

                                            <span>
                                                {{ $employee->user?->email ?? '-' }}
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

                                    {{ $employee->position?->name ?? '-' }}

                                </td>


                                <td>

                                    @if($employee->status)

                                        <span class="status-badge active">

                                            <span class="status-dot"></span>

                                            {{ ucfirst($employee->status) }}

                                        </span>

                                    @else

                                        <span class="empty-value">
                                            -
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="department-employees-empty">

                <div class="empty-icon">
                    <i class="fas fa-users-slash"></i>
                </div>

                <h3>
                    Belum ada karyawan
                </h3>

                <p>
                    Belum ada karyawan yang terdaftar pada department ini.
                </p>

            </div>

        @endif

    </div>


    {{-- =========================================================
        BOTTOM ACTION
    ========================================================= --}}

    <div class="department-detail-actions">

        <a
            href="{{ route('hrd.departments.index') }}"
            class="btn-secondary"
        >
            <i class="fas fa-arrow-left"></i>
            Kembali ke Departments
        </a>

        <a
            href="{{ route('hrd.departments.edit', $department) }}"
            class="btn-primary"
        >
            <i class="fas fa-pen"></i>
            Edit Department
        </a>

    </div>

</div>

@endsection