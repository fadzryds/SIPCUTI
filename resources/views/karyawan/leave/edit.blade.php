@extends('layouts.app')

@section('title','Edit Pengajuan Cuti')

@vite([
'resources/css/karyawan/edit-leave.css',
])

@section('content')

<div class="leave-edit-page">

    {{-- ========================================================= --}}
    {{-- HERO --}}
    {{-- ========================================================= --}}

    <section class="edit-hero">

        <div class="edit-hero-left">

            <a
                href="{{ route('employee.leave.index',$leave) }}"
                class="hero-back">

                <i class="fa-solid fa-arrow-left"></i>

                Kembali

            </a>

            <span class="hero-badge">

                <i class="fa-solid fa-pen-to-square"></i>

                Edit Leave Request

            </span>

            <h1>

                {{ $leave->request_number }}

            </h1>

            <p>

                Perbarui informasi pengajuan cuti sebelum diproses oleh Manager
                dan Human Resource Department.

            </p>

            <div class="hero-meta">

                <div>

                    <span>Status</span>

                    <strong>

                        {{ $leave->status }}

                    </strong>

                </div>

                <div>

                    <span>Dibuat</span>

                    <strong>

                        {{ optional($leave->submitted_at)->format('d F Y') }}

                    </strong>

                </div>

                <div>

                    <span>Terakhir Update</span>

                    <strong>

                        {{ optional($leave->updated_at)->diffForHumans() }}

                    </strong>

                </div>

            </div>

        </div>

        <div class="edit-hero-right">

            <div class="employee-avatar">

                {{ strtoupper(substr($leave->employee->user->name,0,1)) }}

            </div>

            <h3>

                {{ $leave->employee->user->name }}

            </h3>

            <span>

                {{ $leave->employee->position->name }}

            </span>

        </div>

    </section>

    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <section class="summary-grid">

        <div class="summary-card blue">

            <div class="summary-icon">

                <img width="50" height="50" src="https://img.icons8.com/sf-black/64/FFFFFF/user.png" alt="user"/>

            </div>

            <div>

                <small>Karyawan</small>

                <h3>

                    {{ $leave->employee->user->name }}

                </h3>

            </div>

        </div>

        <div class="summary-card green">

            <div class="summary-icon">

                <img width="50" height="50" src="https://img.icons8.com/ios/50/FFFFFF/calendar--v1.png" alt="calendar--v1"/>

            </div>

            <div>

                <small>Total Hari</small>

                <h3>

                    {{ $leave->total_days }}

                </h3>

            </div>

        </div>

        <div class="summary-card orange">

            <div class="summary-icon">

                <img width="50" height="50" src="https://img.icons8.com/ios/50/FFFFFF/file--v1.png" alt="file--v1"/>

            </div>

            <div>

                <small>Jenis Cuti</small>

                <h3>

                    {{ $leave->leaveType->name }}

                </h3>

            </div>

        </div>

        <div class="summary-card purple">

            <div class="summary-icon">

                <img width="50" height="50" src="https://img.icons8.com/ios-glyphs/30/FFFFFF/clock--v3.png" alt="clock--v3"/>

            </div>

            <div>

                <small>Status</small>

                <h3>

                    {{ $leave->status }}

                </h3>

            </div>

        </div>

    </section>

    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form

        action="{{ route('employee.leave.update',$leave) }}"

        method="POST"

        enctype="multipart/form-data"

        class="leave-edit-form">

        @csrf

        @method('PUT')

        <div class="edit-layout">

            {{-- ============================================ --}}
            {{-- LEFT --}}
            {{-- ============================================ --}}

            <div class="edit-main">

                <section class="card">

                    <div class="card-header">

                        <div>

                            <h2>

                                <i class="fa-solid fa-file-pen"></i>

                                Informasi Pengajuan

                            </h2>

                            <p>

                                Silakan ubah data pengajuan cuti.

                            </p>

                        </div>

                    </div>

                    <div class="form-grid">

                        {{-- Jenis Cuti --}}

                        <div class="form-group">

                            <label>

                                Jenis Cuti

                            </label>

                            <select

                                name="leave_type_id"

                                required>

                        @foreach($leaveTypes as $type)

                            <option
                                value="{{ $type->id }}"
                                data-available-days="{{ $availableLeaveDays[$type->id] ?? 0 }}"
                                @selected(
                                    old(
                                        'leave_type_id',
                                        $leave->leave_type_id
                                    ) == $type->id
                                )>

                                {{ $type->name }}

                                — Tersedia
                                {{ $availableLeaveDays[$type->id] ?? 0 }}
                                hari

                            </option>

                        @endforeach

                            </select>

                        </div>

                        {{-- Mulai --}}

                        <div class="form-group">

                            <label>

                                Tanggal Mulai

                            </label>

                            <input

                                type="date"

                                name="start_date"

                                value="{{ old('start_date',$leave->start_date->format('Y-m-d')) }}"
                                
                                min="{{ now()->format('Y-m-d') }}"
                                
                                required>

                        </div>

                        {{-- Selesai --}}

                        <div class="form-group">

                            <label>

                                Tanggal Selesai

                            </label>

                            <input

                                type="date"

                                name="end_date"

                                value="{{ old('end_date',$leave->end_date->format('Y-m-d')) }}"

                                min="{{ old('start_date',$leave->start_date->format('Y-m-d')) }}"

                                required>

                        </div>

                        {{-- Total Hari --}}

                        <div class="form-group">

                            <label>

                                Total Hari

                            </label>

                            <input
                                type="text"
                                id="total_days"
                                value="{{ $leave->total_days }} hari"
                                readonly>

                        </div>

                    </div>

                    <div class="form-group full">

                        <label>

                            Alasan Pengajuan

                        </label>

                        <textarea

                            name="reason"

                            rows="8"

                            required>{{ old('reason',$leave->reason) }}</textarea>

                    </div>

                </section>

                                {{-- ================================================= --}}
                {{-- LAMPIRAN --}}
                {{-- ================================================= --}}

                <section class="card">

                    <div class="card-header">

                        <div>

                            <h2>

                                <i class="fa-solid fa-paperclip"></i>

                                Lampiran Pendukung

                            </h2>

                            <p>

                                Upload ulang apabila ingin mengganti dokumen.

                            </p>

                        </div>

                    </div>

                    @if($leave->attachment)

                        @php

                            $extension = strtolower(pathinfo($leave->attachment, PATHINFO_EXTENSION));

                            $file = asset('storage/'.$leave->attachment);

                        @endphp

                        <div class="attachment-preview">

                            @if(in_array($extension,['jpg','jpeg','png','gif','webp']))

                                <img
                                    src="{{ $file }}"
                                    class="attachment-image"
                                    alt="Attachment">

                            @elseif($extension=='pdf')

                                <iframe
                                    src="{{ $file }}"
                                    class="attachment-pdf">
                                </iframe>

                            @else

                                <div class="attachment-file">

                                    <i class="fa-solid fa-file"></i>

                                    <span>

                                        {{ strtoupper($extension) }}

                                    </span>

                                </div>

                            @endif

                        </div>

                    @endif

                    <div class="form-group full">

                        <label>

                            Upload Lampiran Baru

                        </label>

                        <input
                            type="file"
                            name="attachment"
                            accept=".jpg,.jpeg,.png,.pdf,.webp">

                        <small>

                            Kosongkan apabila tidak ingin mengganti file.

                        </small>

                    </div>

                </section>

            </div>

            {{-- ============================================ --}}
            {{-- RIGHT SIDEBAR --}}
            {{-- ============================================ --}}

            <aside class="edit-sidebar">

                {{-- Employee Card --}}

                <section class="sidebar-card">

                    <div class="sidebar-avatar">

                        {{ strtoupper(substr($leave->employee->user->name,0,1)) }}

                    </div>

                    <h3>

                        {{ $leave->employee->user->name }}

                    </h3>

                    <span>

                        {{ $leave->employee->position->name }}

                    </span>

                    <div class="sidebar-divider"></div>

                    <div class="sidebar-info">

                        <div>

                            <small>NIK</small>

                            <strong>

                                {{ $leave->employee->nik }}

                            </strong>

                        </div>

                        <div>

                            <small>Department</small>

                            <strong>

                                {{ $leave->employee->department->name }}

                            </strong>

                        </div>

                        <div>

                            <small>Email</small>

                            <strong>

                                {{ $leave->employee->user->email }}

                            </strong>

                        </div>

                    </div>

                </section>

                {{-- Information Card --}}

                <section class="sidebar-card">

                    <h3>

                        Informasi

                    </h3>

                    <ul class="info-list">

                        <li>

                            <i class="fa-solid fa-circle-info"></i>

                            Pengajuan hanya dapat diedit sebelum Manager melakukan approval.

                        </li>

                        <li>

                            <i class="fa-solid fa-calendar-check"></i>

                            Pastikan tanggal yang dipilih sesuai dengan kebijakan perusahaan.

                        </li>

                        <li>

                            <i class="fa-solid fa-file-arrow-up"></i>

                            Lampiran lama akan diganti apabila Anda mengunggah file baru.

                        </li>

                    </ul>

                </section>

                {{-- Action Card --}}

                <section class="sidebar-card action-card">

                    <button
                        type="submit"
                        class="btn-save">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Simpan Perubahan

                    </button>

                    <a
                        href="{{ route('employee.leave.show',$leave) }}"
                        class="btn-cancel">

                        <i class="fa-solid fa-arrow-left"></i>

                        Batal

                    </a>

                </section>

            </aside>

        </div>

    </form>

</div>

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const leaveType =
        document.getElementById('leave_type_id');

    const startDate =
        document.querySelector('input[name="start_date"]');

    const endDate =
        document.querySelector('input[name="end_date"]');

    const totalDays =
        document.getElementById('total_days');

    const availability =
        document.getElementById('leave-availability');


    /*
    |--------------------------------------------------------------------------
    | FORMAT DATE
    |--------------------------------------------------------------------------
    */

    function formatDate(date) {

        const year =
            date.getFullYear();

        const month =
            String(
                date.getMonth() + 1
            ).padStart(2, '0');

        const day =
            String(
                date.getDate()
            ).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }


    /*
    |--------------------------------------------------------------------------
    | GET AVAILABLE DAYS
    |--------------------------------------------------------------------------
    */

    function getAvailableDays() {

        const option =
            leaveType.options[
                leaveType.selectedIndex
            ];

        if (
            !option ||
            !option.value
        ) {
            return 0;
        }

        return parseInt(
            option.dataset.availableDays || 0,
            10
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE AVAILABILITY
    |--------------------------------------------------------------------------
    */

    function updateAvailability() {

        const availableDays =
            getAvailableDays();

        availability.textContent =
            `Saldo cuti tersedia: ${availableDays} hari.`;

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE END DATE LIMIT
    |--------------------------------------------------------------------------
    */

    function updateEndDateLimit() {

        if (!startDate.value) {

            endDate.min = '';
            endDate.max = '';

            return;
        }


        const availableDays =
            getAvailableDays();


        endDate.min =
            startDate.value;


        if (availableDays <= 0) {

            endDate.max =
                startDate.value;

            endDate.value = '';

            totalDays.value =
                'Saldo cuti habis';

            return;
        }


        const start =
            new Date(
                startDate.value + 'T00:00:00'
            );


        const maxDate =
            new Date(start);


        maxDate.setDate(
            maxDate.getDate() +
            availableDays -
            1
        );


        endDate.max =
            formatDate(maxDate);


        /*
        |--------------------------------------------------------------------------
        | INVALID END DATE
        |--------------------------------------------------------------------------
        */

        if (
            endDate.value &&
            (
                endDate.value <
                endDate.min ||
                endDate.value >
                endDate.max
            )
        ) {

            endDate.value = '';

            totalDays.value = '';

        }


        calculateTotalDays();

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE TOTAL DAYS
    |--------------------------------------------------------------------------
    */

    function calculateTotalDays() {

        if (
            !startDate.value ||
            !endDate.value
        ) {

            return;
        }


        const start =
            new Date(
                startDate.value + 'T00:00:00'
            );


        const end =
            new Date(
                endDate.value + 'T00:00:00'
            );


        const difference =
            Math.floor(
                (
                    end - start
                ) /
                (
                    1000 *
                    60 *
                    60 *
                    24
                )
            );


        const days =
            difference + 1;


        const availableDays =
            getAvailableDays();


        if (
            days >
            availableDays
        ) {

            totalDays.value =
                `Maksimal ${availableDays} hari`;

            return;
        }


        totalDays.value =
            `${days} hari`;

    }


    /*
    |--------------------------------------------------------------------------
    | LEAVE TYPE CHANGE
    |--------------------------------------------------------------------------
    */

    leaveType.addEventListener(
        'change',
        function () {

            updateAvailability();

            /*
            |----------------------------------------------------------------------
            | Saat jenis cuti diganti, tanggal selesai diperiksa ulang.
            |----------------------------------------------------------------------
            */

            updateEndDateLimit();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | START DATE
    |--------------------------------------------------------------------------
    */

    startDate.addEventListener(
        'change',
        function () {

            updateEndDateLimit();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | END DATE
    |--------------------------------------------------------------------------
    */

    endDate.addEventListener(
        'change',
        function () {

            calculateTotalDays();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    const form =
        document.querySelector('.leave-edit-form');


    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                if (
                    !startDate.value ||
                    !endDate.value
                ) {
                    return;
                }


                const availableDays =
                    getAvailableDays();


                const start =
                    new Date(
                        startDate.value + 'T00:00:00'
                    );


                const end =
                    new Date(
                        endDate.value + 'T00:00:00'
                    );


                const difference =
                    Math.floor(
                        (
                            end - start
                        ) /
                        (
                            1000 *
                            60 *
                            60 *
                            24
                        )
                    );


                const days =
                    difference + 1;


                if (
                    days >
                    availableDays
                ) {

                    event.preventDefault();


                    alert(
                        `Tanggal cuti melebihi saldo yang tersedia. ` +
                        `Saldo tersedia hanya ${availableDays} hari.`
                    );


                    endDate.focus();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    updateAvailability();

    updateEndDateLimit();

});
</script>

@endpush

@endsection