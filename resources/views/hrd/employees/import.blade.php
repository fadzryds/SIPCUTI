@extends('hrd.layouts.app')

@section('title', 'Import Employees')

@section('content')

<div class="hrd-page">

    {{-- =========================================================
        HEADER
    ========================================================= --}}

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon">
                <i class="fas fa-file-import"></i>
            </div>

            <div>

                <span class="page-header-label">
                    MASTER DATA
                </span>

                <h1>
                    Import Employees
                </h1>

                <p>
                    Tambahkan data employee secara massal menggunakan
                    Excel atau CSV.
                </p>

            </div>

        </div>


        <a
            href="{{ route('hrd.employees.index') }}"
            class="btn-reset"
        >
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

    </div>


    {{-- =========================================================
        SUCCESS
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
        ERROR
    ========================================================= --}}

    @if(session('error'))

        <div class="alert alert-error">

            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <div class="alert-content">

                <strong>
                    Import Gagal
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
        VALIDATION ERRORS
    ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-error">

            <div class="alert-icon">
                <i class="fas fa-triangle-exclamation"></i>
            </div>

            <div class="alert-content">

                <strong>
                    Data tidak dapat diimport
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
        IMPORT CARD
    ========================================================= --}}

    <div class="employee-import-card">

        <div class="import-card-header">

            <div class="import-card-icon">
                <i class="fas fa-file-excel"></i>
            </div>

            <div>

                <h2>
                    Import Data Employee
                </h2>

                <p>
                    Upload file Excel atau CSV sesuai template
                    yang telah disediakan.
                </p>

            </div>

        </div>


        {{-- =====================================================
            TEMPLATE
        ===================================================== --}}

        <div class="import-template-box">

            <div class="import-template-icon">

                <i class="fas fa-download"></i>

            </div>

            <div class="import-template-content">

                <strong>
                    Belum memiliki template?
                </strong>

                <span>
                    Download template Excel untuk memastikan
                    format data sesuai dengan sistem.
                </span>

            </div>

            <a
                href="{{ route('hrd.employees.import.template') }}"
                class="btn-template"
            >
                <i class="fas fa-file-download"></i>
                Download Template
            </a>

        </div>


        {{-- =====================================================
            FORM
        ===================================================== --}}

        <form
            action="{{ route('hrd.employees.import') }}"
            method="POST"
            enctype="multipart/form-data"
            class="employee-import-form"
        >

            @csrf


            <div class="import-upload-area">

                <div class="upload-icon">

                    <i class="fas fa-cloud-arrow-up"></i>

                </div>

                <h3>
                    Upload File Employee
                </h3>

                <p>
                    Pilih file Excel atau CSV dari komputer Anda.
                </p>


                <label
                    for="employee_file"
                    class="upload-button"
                >
                    <i class="fas fa-folder-open"></i>
                    Pilih File
                </label>

                <input
                    type="file"
                    id="employee_file"
                    name="file"
                    accept=".xlsx,.xls,.csv"
                    hidden
                    required
                >

                <div
                    id="selected-file"
                    class="selected-file"
                >
                </div>

                <small>
                    Format yang didukung:
                    <strong>.xlsx, .xls, .csv</strong>
                </small>

            </div>


            {{-- =================================================
                WARNING
            ================================================= --}}

            <div class="import-warning">

                <i class="fas fa-circle-info"></i>

                <div>

                    <strong>
                        Perhatikan sebelum import
                    </strong>

                    <ul>

                        <li>
                            NIK tidak boleh sama dengan data employee
                            yang sudah ada.
                        </li>

                        <li>
                            Email tidak boleh sama dengan email user
                            yang sudah ada.
                        </li>

                        <li>
                            Password harus diisi di file Excel/CSV.
                        </li>

                        <li>
                            Password akan otomatis di-hash sebelum
                            disimpan ke database.
                        </li>

                        <li>
                            Department dan Position menggunakan
                            <strong>code</strong>.
                        </li>

                        <li>
                            Supervisor, Manager, dan Director harus
                            memiliki data yang sesuai.
                        </li>

                    </ul>

                </div>

            </div>


            {{-- =================================================
                SUBMIT
            ================================================= --}}

            <div class="import-form-footer">

                <a
                    href="{{ route('hrd.employees.index') }}"
                    class="btn-reset"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    <i class="fas fa-file-import"></i>
                    Import Employees
                </button>

            </div>

        </form>

    </div>

</div>


<script>

document
    .getElementById('employee_file')
    .addEventListener('change', function () {

        const fileBox =
            document.getElementById('selected-file');

        if (!this.files.length) {

            fileBox.innerHTML = '';

            return;
        }

        const file = this.files[0];

        fileBox.innerHTML = `
            <div class="selected-file-content">

                <i class="fas fa-file-excel"></i>

                <div>
                    <strong>
                        ${file.name}
                    </strong>

                    <span>
                        ${(file.size / 1024).toFixed(2)} KB
                    </span>
                </div>

            </div>
        `;
    });

</script>

@endsection