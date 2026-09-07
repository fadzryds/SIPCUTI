@extends('hrd.layouts.app')

@section('title', 'Edit Department')

@section('content')

<div class="hrd-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================= --}}

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon">
                <i class="fas fa-pen-to-square"></i>
            </div>

            <div>

                <span class="page-header-label">
                    MASTER DATA
                </span>

                <h1>
                    Edit Department
                </h1>

                <p>
                    Perbarui informasi department yang terdaftar dalam sistem.
                </p>

            </div>

        </div>


        {{-- HEADER ACTION --}}

        <div class="page-header-actions">

            <a
                href="{{ route('hrd.departments.index') }}"
                class="btn-secondary"
            >
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

        </div>

    </div>


    {{-- =========================================================
        VALIDATION ERROR
    ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-error">

            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <div class="alert-content">

                <strong>
                    Terjadi Kesalahan
                </strong>

                <span>
                    Periksa kembali data yang Anda masukkan.
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
        EDIT FORM CARD
    ========================================================= --}}

    <div class="department-form-card">


        {{-- =====================================================
            FORM HEADER
        ===================================================== --}}

        <div class="form-card-header">

            <div class="form-card-header-left">

                <div class="form-card-icon">
                    <i class="fas fa-building"></i>
                </div>

                <div>

                    <h2>
                        Informasi Department
                    </h2>

                    <span>
                        Ubah informasi department sesuai kebutuhan.
                    </span>

                </div>

            </div>


            {{-- STATUS INDICATOR --}}

            <div class="form-status-indicator">

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
            FORM
        ===================================================== --}}

        <form
            action="{{ route('hrd.departments.update', $department) }}"
            method="POST"
            class="department-form"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                BASIC INFORMATION
            ================================================= --}}

            <div class="form-section">

                <div class="form-section-title">

                    <div class="form-section-icon">
                        <i class="fas fa-circle-info"></i>
                    </div>

                    <div>

                        <h3>
                            Informasi Dasar
                        </h3>

                        <span>
                            Informasi utama department.
                        </span>

                    </div>

                </div>


                <div class="form-grid">


                    {{-- CODE --}}

                    <div class="form-group">

                        <label for="code">
                            Kode Department
                            <span class="required">*</span>
                        </label>

                        <div class="input-wrapper">

                            <div class="input-icon">
                                <i class="fas fa-hashtag"></i>
                            </div>

                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code', $department->code) }}"
                                placeholder="Contoh: HRD"
                                maxlength="50"
                                autocomplete="off"
                                class="@error('code') is-invalid @enderror"
                            >

                        </div>

                        @error('code')

                            <span class="field-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- NAME --}}

                    <div class="form-group">

                        <label for="name">
                            Nama Department
                            <span class="required">*</span>
                        </label>

                        <div class="input-wrapper">

                            <div class="input-icon">
                                <i class="fas fa-building"></i>
                            </div>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $department->name) }}"
                                placeholder="Contoh: Human Resources"
                                maxlength="255"
                                autocomplete="off"
                                class="@error('name') is-invalid @enderror"
                            >

                        </div>

                        @error('name')

                            <span class="field-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- DESCRIPTION --}}

                    <div class="form-group form-group-full">

                        <label for="description">
                            Deskripsi Department
                        </label>

                        <div class="input-wrapper textarea-wrapper">

                            <div class="input-icon textarea-icon">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                maxlength="1000"
                                placeholder="Masukkan deskripsi department..."
                                class="@error('description') is-invalid @enderror"
                            >{{ old('description', $department->description) }}</textarea>

                        </div>

                        @error('description')

                            <span class="field-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </span>

                        @enderror

                        <span class="field-hint">
                            <i class="fas fa-circle-info"></i>
                            Deskripsi bersifat opsional.
                        </span>

                    </div>

                </div>

            </div>


            {{-- =================================================
                STATUS
            ================================================= --}}

            <div class="form-section">

                <div class="form-section-title">

                    <div class="form-section-icon">
                        <i class="fas fa-toggle-on"></i>
                    </div>

                    <div>

                        <h3>
                            Status Department
                        </h3>

                        <span>
                            Tentukan apakah department aktif digunakan.
                        </span>

                    </div>

                </div>


                <div class="status-form-box">

                    <div class="status-form-content">

                        <div class="status-form-icon">

                            @if(old('is_active', $department->is_active))

                                <i class="fas fa-circle-check"></i>

                            @else

                                <i class="fas fa-circle-xmark"></i>

                            @endif

                        </div>

                        <div>

                            <strong>
                                Status Department
                            </strong>

                            <span>
                                Department yang aktif dapat digunakan
                                dalam data karyawan.
                            </span>

                        </div>

                    </div>


                    <label class="switch">

                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $department->is_active) ? 'checked' : '' }}
                        >

                        <span class="slider"></span>

                    </label>

                </div>

            </div>


            {{-- =================================================
                FORM FOOTER
            ================================================= --}}

            <div class="form-card-footer">

                <div class="form-required-info">

                    <span class="required">*</span>

                    Field wajib diisi

                </div>


                <div class="form-actions">

                    <a
                        href="{{ route('hrd.departments.index') }}"
                        class="btn-form-cancel"
                    >
                        <i class="fas fa-xmark"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn-form-submit"
                    >
                        <i class="fas fa-floppy-disk"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection