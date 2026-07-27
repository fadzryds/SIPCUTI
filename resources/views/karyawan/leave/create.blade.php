@extends('layouts.app')

@section('title', 'Pengajuan Cuti')

@vite([
'resources/css/leave.css',
'resources/js/leave.js'
])

@section('content')

<div class="page-wrapper">

    {{-- Breadcrumb --}}
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

    {{-- Header --}}
    <div class="page-header">

        <div>

            <h1>Pengajuan Cuti</h1>

            <p>
                Ajukan cuti Anda dengan mengisi formulir berikut.
            </p>

        </div>

    </div>

    <form
        action="{{ route('employee.leave.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        {{-- ================================================= --}}
        {{-- INFORMASI KARYAWAN --}}
        {{-- ================================================= --}}

        <div class="card">

            <div class="card-title">

                Informasi Karyawan

            </div>

            <div class="info-grid">

                <div>

                    <label>NIK</label>

                    <input
                        type="text"
                        value="{{ auth()->user()->employee->nik }}"
                        readonly>

                </div>

                <div>

                    <label>Nama</label>

                    <input
                        type="text"
                        value="{{ auth()->user()->name }}"
                        readonly>

                </div>

                <div>

                    <label>Department</label>

                    <input
                        type="text"
                        value="{{ auth()->user()->employee->department->name }}"
                        readonly>

                </div>

                <div>

                    <label>Jabatan</label>

                    <input
                        type="text"
                        value="{{ auth()->user()->employee->position->name }}"
                        readonly>

                </div>

                <div>

                    <label>Sisa Cuti</label>

                    <input
                        type="text"
                        value="{{ $remainingLeave }} Hari"
                        readonly>

                </div>

            </div>

        </div>

        {{-- ================================================= --}}
        {{-- INFORMASI CUTI --}}
        {{-- ================================================= --}}

        <div class="card">

            <div class="card-title">

                Informasi Cuti

            </div>

            <div class="form-grid">

                <div class="full">

                    <label>Jenis Cuti</label>

                    <select name="leave_type_id" required>

                        <option value="">
                            Pilih Jenis Cuti
                        </option>

                        @foreach($leaveTypes as $type)

                            <option value="{{ $type->id }}">

                                {{ $type->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div>

                    <label>Tanggal Mulai</label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        required>

                </div>

                <div>

                    <label>Tanggal Selesai</label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        required>

                </div>

                <div>

                    <label>Lama Cuti</label>

                    <input
                        type="text"
                        id="total_days"
                        readonly>

                </div>

                <div class="full">

                    <label>Alasan</label>

                    <textarea
                        name="reason"
                        rows="5"
                        required></textarea>

                </div>

                <div class="full">

                    <label>Lampiran</label>

                    <input
                        type="file"
                        name="attachment"
                        accept=".pdf,.jpg,.jpeg,.png">

                    <small>

                        PDF / JPG / PNG maksimal 2MB

                    </small>

                </div>

            </div>

        </div>

        {{-- ================================================= --}}
        {{-- APPROVAL --}}
        {{-- ================================================= --}}

        <div class="card">

            <div class="card-title">

                Informasi Approval

            </div>

            <div class="info-grid">

                <div>

                    <label>Manager</label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $manager?->user?->name ?? '-' }}"
                        readonly>
                </div>

                <div>

                    <label>HRD</label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $hrd?->user?->name ?? '-' }}"
                        readonly>

                </div>

                <div>

                    <label>Status</label>

                    <input
                        type="text"
                        value="Belum Diajukan"
                        readonly>

                </div>

            </div>

        </div>

        {{-- ================================================= --}}
        {{-- WORKFLOW --}}
        {{-- ================================================= --}}

        <div class="card">

            <div class="card-title">

                Workflow Persetujuan

            </div>

            <div class="workflow">

                <div class="step active">

                    Karyawan

                </div>

                <div class="arrow">↓</div>

                <div class="step">

                    Manager

                </div>

                <div class="arrow">↓</div>

                <div class="step">

                    HRD

                </div>

                <div class="arrow">↓</div>

                <div class="step">

                    Generate PDF

                </div>

                <div class="arrow">↓</div>

                <div class="step">

                    Download

                </div>

            </div>

        </div>

        {{-- ================================================= --}}
        {{-- CATATAN --}}
        {{-- ================================================= --}}

        <div class="card">

            <div class="card-title">

                Catatan

            </div>

            <ul class="notes">

                <li>Pengajuan dikirim terlebih dahulu kepada Manager.</li>

                <li>Setelah Manager menyetujui, permintaan diteruskan ke HRD.</li>

                <li>Setelah HRD menyetujui, sistem otomatis membuat Surat Cuti PDF.</li>

                <li>Karyawan dapat mengunduh surat cuti pada menu Riwayat Cuti.</li>

            </ul>

        </div>

        {{-- BUTTON --}}

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