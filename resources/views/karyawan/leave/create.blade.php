@extends('layouts.app')

@section('title', 'Pengajuan Cuti')

@vite([
    'resources/css/karyawan/leave.css',
    'resources/js/leave.js'
])

@section('content')

<div class="page-wrapper">

    {{-- ========================================================= --}}
    {{-- BREADCRUMB --}}
    {{-- ========================================================= --}}

    <div class="breadcrumb">

        <a href="{{ route('employee.dashboard') }}">
            Dashboard
        </a>

        <span>/</span>

        <a href="{{ route('employee.leave.index') }}">
            Pengajuan Cuti
        </a>

        <span>/</span>

        <strong>Ajukan Cuti</strong>

    </div>


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="page-header">

        <div>

            <h1>Pengajuan Cuti</h1>

            <p>
                Ajukan cuti Anda dengan mengisi formulir berikut.
            </p>

        </div>

    </div>

    {{-- =========================================================
     SALDO CUTI
========================================================= --}}

@if ($remainingLeave <= 0)

    <div class="leave-alert leave-alert-danger">

        <div class="leave-alert-icon">
            <img
                width="32"
                height="32"
                src="https://img.icons8.com/ios-filled/50/FFFFFF/error--v1.png"
                alt="Saldo cuti habis">
        </div>

        <div class="leave-alert-content">

            <strong>Saldo Cuti Sudah Habis</strong>

            <p>
                Saldo cuti Anda sudah habis.
                Anda tidak dapat mengajukan cuti sampai saldo cuti tersedia kembali.
            </p>

        </div>

    </div>

@else

    {{-- =====================================================
         SALDO CUTI TERSEDIA
    ====================================================== --}}

    <div class="leave-alert leave-alert-success">

        <div class="leave-alert-icon">
            <img
                width="32"
                height="32"
                src="https://img.icons8.com/ios-filled/50/FFFFFF/calendar--v1.png"
                alt="Saldo cuti">
        </div>

        <div class="leave-alert-content">

            <strong>Saldo Cuti Tersedia</strong>

            <p>
                Anda masih memiliki
                <strong>{{ $remainingLeave }} hari</strong>
                cuti yang tersedia untuk diajukan.
            </p>

        </div>

    </div>

@endif

    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('employee.leave.store') }}"
        method="POST"
        enctype="multipart/form-data"
        id="leave-form">

        @csrf


        {{-- ===================================================== --}}
        {{-- ERROR --}}
        {{-- ===================================================== --}}

        @if ($errors->any())

            <div class="card error-card">

                <div class="card-title">
                    Terjadi Kesalahan
                </div>

                <ul class="error-list">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        @if (session('error'))

            <div class="card error-card">

                <div class="card-title">
                    Pengajuan Gagal
                </div>

                <p>
                    {{ session('error') }}
                </p>

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- INFORMASI CUTI --}}
        {{-- ===================================================== --}}

        <div class="card">

            <div class="card-title">
                Informasi Cuti
            </div>


            <div class="form-grid">


                {{-- ===================================================== --}}
                {{-- JENIS CUTI --}}
                {{-- ===================================================== --}}

                <div class="full">

                    <label for="leave_type_id">
                        Jenis Cuti
                    </label>

                    <select
                        id="leave_type_id"
                        name="leave_type_id"
                        required>

                        <option value="">
                            Pilih Jenis Cuti
                        </option>

                        @foreach ($leaveTypes as $type)

                            <option
                                value="{{ $type->id }}"
                                data-available-days="{{ $availableLeaveDays[$type->id] ?? 0 }}"
                                {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>

                                {{ $type->name }}
                                —
                                Tersedia
                                {{ $availableLeaveDays[$type->id] ?? 0 }}
                                hari kerja

                            </option>

                        @endforeach

                    </select>

                    <small
                        id="leave-availability"
                        style="display:block; margin-top:8px; color:#64748b;">

                        Pilih jenis cuti untuk melihat saldo yang tersedia.

                    </small>

                </div>


                {{-- ===================================================== --}}
                {{-- TANGGAL MULAI --}}
                {{-- ===================================================== --}}

                <div>

                    <label for="start_date">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        min="{{ now()->format('Y-m-d') }}"
                        value="{{ old('start_date') }}"
                        required>

                    <small
                        style="display:block; margin-top:6px; color:#64748b;">

                        Sabtu, Minggu, dan hari libur tidak mengurangi saldo.

                    </small>

                </div>


                {{-- ===================================================== --}}
                {{-- TANGGAL SELESAI --}}
                {{-- ===================================================== --}}

                <div>

                    <label for="end_date">
                        Tanggal Selesai
                    </label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date') }}"
                        required>

                    <small
                        id="end-date-info"
                        style="display:block; margin-top:6px; color:#64748b;">

                        Pilih tanggal mulai terlebih dahulu.

                    </small>

                </div>


                {{-- ===================================================== --}}
                {{-- TOTAL HARI --}}
                {{-- ===================================================== --}}

                <div>

                    <label for="total_days">
                        Lama Cuti
                    </label>

                    <input
                        type="text"
                        id="total_days"
                        placeholder="Pilih tanggal terlebih dahulu"
                        readonly>

                </div>

                {{-- ALASAN --}}

                <div class="full">

                    <label for="reason">
                        Alasan
                    </label>

                    <textarea
                        id="reason"
                        name="reason"
                        rows="5"
                        maxlength="1000"
                        placeholder="Masukkan alasan pengajuan cuti..."
                        required>{{ old('reason') }}</textarea>

                </div>

                {{-- LAMPIRAN --}}

                <div class="full">

                    <label for="attachment">
                        Lampiran
                    </label>

                    <input
                        type="file"
                        id="attachment"
                        name="attachment"
                        accept=".pdf,.jpg,.jpeg,.png">

                    <small>
                        PDF / JPG / PNG maksimal 2MB.
                    </small>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INFORMASI APPROVAL --}}
        {{-- ===================================================== --}}

        <div class="card">

            <div class="card-title">
                Informasi Approval
            </div>


            <div class="info-grid">

                {{-- SUPERVISOR --}}

                <div>

                    <label for="supervisor">
                        Supervisor
                    </label>

                    <input
                        type="text"
                        id="supervisor"
                        class="form-control"
                        value="{{ $supervisor?->user?->name ?? '-' }}"
                        readonly>

                </div>


                {{-- MANAGER --}}

                <div>

                    <label for="manager">
                        Manager
                    </label>

                    <input
                        type="text"
                        id="manager"
                        class="form-control"
                        value="{{ $manager?->user?->name ?? '-' }}"
                        readonly>

                </div>


                {{-- DIRECTOR --}}

                <div>

                    <label for="director">
                        Director
                    </label>

                    <input
                        type="text"
                        id="director"
                        class="form-control"
                        value="{{ $director?->user?->name ?? '-' }}"
                        readonly>

                </div>


                {{-- STATUS --}}

                <div>

                    <label for="status">
                        Status
                    </label>

                    <input
                        type="text"
                        id="status"
                        value="Belum Diajukan"
                        readonly>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TANDA TANGAN --}}
        {{-- ===================================================== --}}

        <div class="card">

            <div class="card-title">
                Tanda Tangan Karyawan
            </div>


            <div class="signature-wrapper">

                <p class="signature-description">

                    Silakan bubuhkan tanda tangan Anda pada area
                    di bawah ini sebagai tanda bahwa pengajuan cuti
                    dibuat dan diajukan oleh Anda.

                </p>


                <div class="signature-box">

                    <canvas
                        id="signature-pad"
                        aria-label="Area tanda tangan">
                    </canvas>

                </div>


                <input
                    type="hidden"
                    name="signature"
                    id="signature"
                    value="{{ old('signature') }}">


                <div class="signature-actions">

                    <button
                        type="button"
                        id="clear-signature"
                        class="btn-signature-clear">

                        Hapus Tanda Tangan

                    </button>

                </div>


                <small class="signature-hint">

                    Tanda tangan wajib diisi sebelum pengajuan
                    cuti dikirim.

                </small>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- WORKFLOW --}}
        {{-- ===================================================== --}}

        <div class="card">

            <div class="card-title">
                Workflow Persetujuan
            </div>


            <div class="workflow">

                <div class="step active">
                    Karyawan
                </div>

                <div class="arrow">
                    ↓
                </div>

                <div class="step">
                    Supervisor
                </div>

                <div class="arrow">
                    ↓
                </div>

                <div class="step">
                    Manager
                </div>

                <div class="arrow">
                    ↓
                </div>

                <div class="step">
                    Director
                </div>

                <div class="arrow">
                    ↓
                </div>

                <div class="step">
                    Selesai
                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- CATATAN --}}
        {{-- ===================================================== --}}

        <div class="card">

            <div class="card-title">
                Catatan
            </div>


            <ul class="notes">

                <li>
                    Pengajuan dikirim terlebih dahulu kepada Supervisor.
                </li>

                <li>
                    Setelah Supervisor menyetujui,
                    permintaan diteruskan kepada Manager.
                </li>

                <li>
                    Setelah Manager menyetujui,
                    permintaan diteruskan kepada Director.
                </li>

                <li>
                    Sabtu dan Minggu tidak dihitung sebagai hari cuti.
                </li>

                <li>
                    Hari libur perusahaan/nasional tidak dihitung
                    sebagai hari cuti.
                </li>

                <li>
                    Saldo cuti menggunakan nilai
                    <strong>remaining</strong> pada
                    <strong>leave_balances</strong>.
                </li>

                <li>
                    Setelah seluruh approval selesai,
                    status pengajuan berubah menjadi Disetujui.
                </li>

                <li>
                    Sistem kemudian dapat membuat Surat Cuti PDF.
                </li>

                <li>
                    Karyawan dapat mengunduh surat cuti pada menu
                    Riwayat Cuti.
                </li>

            </ul>

        </div>


        {{-- ===================================================== --}}
        {{-- ACTION --}}
        {{-- ===================================================== --}}

        <div class="form-action">

            <a
                href="{{ route('employee.leave.index') }}"
                class="btn-cancel">

                Batal

            </a>


            <button
                type="submit"
                class="btn-submit">

                Ajukan Cuti

            </button>

        </div>

    </form>

</div>

@endsection

@push('scripts')

    <script>
        window.leaveHolidays = @json($holidays ?? []);
    </script>

@endpush