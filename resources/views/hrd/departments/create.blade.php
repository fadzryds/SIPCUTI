@extends('hrd.layouts.app')

@section('title', 'Tambah Department')

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
                    Tambah Department
                </h1>

                <p>
                    Tambahkan data department baru ke dalam sistem.
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
                    Terdapat kesalahan
                </strong>

                <span>
                    Silakan periksa kembali data yang Anda masukkan.
                </span>

                <ul class="form-error-list">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

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
        FORM CARD
    ========================================================= --}}

    <div class="department-form-card">

        {{-- FORM HEADER --}}

        <div class="department-form-header">

            <div class="department-form-header-icon">
                <i class="fas fa-building"></i>
            </div>

            <div>

                <h2>
                    Informasi Department
                </h2>

                <p>
                    Lengkapi informasi department yang akan ditambahkan.
                </p>

            </div>

        </div>


        {{-- FORM BODY --}}

        <form
            action="{{ route('hrd.departments.store') }}"
            method="POST"
            class="department-form"
        >

            @csrf


            {{-- =================================================
                BASIC INFORMATION
            ================================================== --}}

            <div class="department-form-section">

                <div class="department-section-title">

                    <div class="department-section-icon">
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


                <div class="department-form-grid">


                    {{-- CODE --}}

                    <div class="form-group">

                        <label for="code">
                            Kode Department
                            <span class="required">*</span>
                        </label>

                        <div class="input-wrapper">

                            <i class="fas fa-code"></i>

                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
                                placeholder="Contoh: HRD"
                                maxlength="50"
                                required
                            >

                        </div>

                        <small>
                            Gunakan kode unik untuk department.
                        </small>

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

                            <i class="fas fa-building"></i>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Human Resource Department"
                                maxlength="255"
                                required
                            >

                        </div>

                        <small>
                            Masukkan nama department secara lengkap.
                        </small>

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
                            Deskripsi
                        </label>

                        <div class="textarea-wrapper">

                            <i class="fas fa-align-left"></i>

                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                maxlength="1000"
                                placeholder="Masukkan deskripsi department..."
                            >{{ old('description') }}</textarea>

                        </div>

                        <small>
                            Deskripsi bersifat opsional.
                        </small>

                        @error('description')

                            <span class="field-error">
                                <i class="fas fa-circle-exclamation"></i>
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                STATUS
            ================================================== --}}

            <div class="department-form-section">

                <div class="department-section-title">

                    <div class="department-section-icon">
                        <i class="fas fa-toggle-on"></i>
                    </div>

                    <div>

                        <h3>
                            Status Department
                        </h3>

                        <span>
                            Tentukan status department saat dibuat.
                        </span>

                    </div>

                </div>


                <label class="department-status-option">

                    <div class="department-status-info">

                        <div class="department-status-icon">
                            <i class="fas fa-building-circle-check"></i>
                        </div>

                        <div>

                            <strong>
                                Department Aktif
                            </strong>

                            <span>
                                Department dapat digunakan dalam sistem.
                            </span>

                        </div>

                    </div>


                    <div class="department-toggle">

                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            @checked(old('is_active', true))
                        >

                        <span class="department-toggle-slider"></span>

                    </div>

                </label>

            </div>


            {{-- =================================================
                FORM ACTION
            ================================================== --}}

            <div class="department-form-actions">

                <a
                    href="{{ route('hrd.departments.index') }}"
                    class="btn-secondary"
                >
                    <i class="fas fa-times"></i>
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    <i class="fas fa-save"></i>
                    Simpan Department
                </button>

            </div>

        </form>

    </div>

</div>

@endsection