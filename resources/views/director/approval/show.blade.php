@extends('director.layouts.app')

@section('title', 'Detail Approval')

@section('content')

@php

    $directorApproval = $leave->approvals
        ->where(
            'approval_level',
            \App\Models\LeaveApproval::LEVEL_DIRECTOR
        )
        ->where(
            'approver_id',
            auth()->id()
        )
        ->first();

    $managerApproval = $leave->approvals
        ->where(
            'approval_level',
            \App\Models\LeaveApproval::LEVEL_MANAGER
        )
        ->first();

@endphp


<div class="approval-page approval-detail-page">


    {{-- =========================================================
         HERO
    ========================================================== --}}

    <section class="approval-hero">

        <div class="hero-left">

            <a
                href="{{ route('director.approval.index') }}"
                class="approval-back"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Kembali ke Approval

            </a>


            <span class="hero-tag">

                <i class="fa-solid fa-user-tie"></i>

                Approval Director

            </span>


            <h1>
                Detail Pengajuan Cuti
            </h1>


            <p>

                Silakan lakukan pemeriksaan akhir terhadap
                pengajuan cuti sebelum memberikan keputusan.

            </p>

        </div>


        <div class="hero-right">

            <div class="hero-icon">

                {{
                    strtoupper(
                        substr(
                            $leave->employee->user->name ?? 'U',
                            0,
                            1
                        )
                    )
                }}

            </div>

        </div>

    </section>



    {{-- =========================================================
         SUMMARY
    ========================================================== --}}

    <section class="approval-summary">


        <div class="summary-card info">

            <div class="summary-icon">

                <i class="fa-solid fa-hashtag"></i>

            </div>

            <div>

                <small>
                    Nomor Pengajuan
                </small>

                <h2>
                    {{ $leave->request_number }}
                </h2>

                <span>
                    Request Number
                </span>

            </div>

        </div>


        <div class="summary-card success">

            <div class="summary-icon">

                <i class="fa-solid fa-calendar-days"></i>

            </div>

            <div>

                <small>
                    Total Hari
                </small>

                <h2>
                    {{ $leave->total_days }}
                </h2>

                <span>
                    Hari Cuti
                </span>

            </div>

        </div>


        <div class="summary-card warning">

            <div class="summary-icon">

                <i class="fa-solid fa-file-lines"></i>

            </div>

            <div>

                <small>
                    Jenis Cuti
                </small>

                <h2>
                    {{ $leave->leaveType->name ?? '-' }}
                </h2>

                <span>
                    Leave Type
                </span>

            </div>

        </div>


        <div class="summary-card
            @if(optional($directorApproval)->status === 'Approved')
                success
            @elseif(optional($directorApproval)->status === 'Rejected')
                danger
            @else
                warning
            @endif
        ">

            <div class="summary-icon">

                @if(optional($directorApproval)->status === 'Approved')

                    <i class="fa-solid fa-circle-check"></i>

                @elseif(optional($directorApproval)->status === 'Rejected')

                    <i class="fa-solid fa-circle-xmark"></i>

                @else

                    <i class="fa-solid fa-clock"></i>

                @endif

            </div>


            <div>

                <small>
                    Status Director
                </small>

                <h2>
                    {{ optional($directorApproval)->status ?? 'Waiting' }}
                </h2>

                <span>
                    Approval Status
                </span>

            </div>

        </div>

    </section>



    {{-- =========================================================
         INFORMASI KARYAWAN
    ========================================================== --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-user"></i>

                    Informasi Karyawan

                </h2>

                <p>
                    Informasi lengkap mengenai karyawan.
                </p>

            </div>

        </div>


        <div class="approval-grid">

            <div class="approval-item">

                <label>
                    Nama Lengkap
                </label>

                <strong>
                    {{ $leave->employee->user->name ?? '-' }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    NIK
                </label>

                <strong>
                    {{ $leave->employee->nik ?? '-' }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Department
                </label>

                <strong>
                    {{ $leave->employee->department->name ?? '-' }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Jabatan
                </label>

                <strong>
                    {{ $leave->employee->position->name ?? '-' }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Email
                </label>

                <strong>
                    {{ $leave->employee->user->email ?? '-' }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Status
                </label>

                <strong>
                    {{ $leave->employee->status ?? '-' }}
                </strong>

            </div>

        </div>

    </section>



    {{-- =========================================================
         DETAIL CUTI
    ========================================================== --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-calendar-check"></i>

                    Detail Pengajuan

                </h2>

                <p>
                    Informasi mengenai pengajuan cuti.
                </p>

            </div>

        </div>


        <div class="approval-grid">

            <div class="approval-item">

                <label>
                    Tanggal Mulai
                </label>

                <strong>
                    {{ $leave->start_date->format('d F Y') }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Tanggal Selesai
                </label>

                <strong>
                    {{ $leave->end_date->format('d F Y') }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Total Hari
                </label>

                <strong>
                    {{ $leave->total_days }} Hari
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Jenis Cuti
                </label>

                <strong>
                    {{ $leave->leaveType->name ?? '-' }}
                </strong>

            </div>


            <div class="approval-item approval-full">

                <label>
                    Alasan Pengajuan
                </label>

                <div class="approval-reason">
                    {{ $leave->reason ?? '-' }}
                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
         TIMELINE
    ========================================================== --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-list-check"></i>

                    Timeline Approval

                </h2>

                <p>
                    Riwayat lengkap proses persetujuan.
                </p>

            </div>

        </div>


        <div class="approval-timeline">

            @foreach($leave->approvals as $item)

                <div class="timeline-item">

                    <div class="timeline-circle
                        @if($item->status === 'Approved')
                            success
                        @elseif($item->status === 'Rejected')
                            danger
                        @elseif($item->status === 'Pending')
                            warning
                        @else
                            waiting
                        @endif
                    ">

                        @if($item->status === 'Approved')

                            <i class="fa-solid fa-check"></i>

                        @elseif($item->status === 'Rejected')

                            <i class="fa-solid fa-xmark"></i>

                        @elseif($item->status === 'Pending')

                            <i class="fa-solid fa-clock"></i>

                        @else

                            <i class="fa-solid fa-hourglass-half"></i>

                        @endif

                    </div>


                    <div class="timeline-content">

                        <h4>
                            {{ $item->approval_level }}
                        </h4>

                        <span>
                            {{ $item->approver?->name ?? '-' }}
                        </span>

                        <p>

                            Status :

                            <strong>
                                {{ $item->status }}
                            </strong>

                        </p>


                        @if($item->approved_at)

                            <small>
                                {{ $item->approved_at->format('d F Y H:i') }}
                            </small>

                        @endif


                        @if($item->notes)

                            <div class="timeline-note">

                                {{ $item->notes }}

                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    </section>



    {{-- =========================================================
         SIGNATURE
    ========================================================== --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-signature"></i>

                    Tanda Tangan Digital

                </h2>

                <p>
                    Status tanda tangan Manager dan Director.
                </p>

            </div>

        </div>


        <div class="signature-grid">


            {{-- MANAGER --}}

            <div class="signature-card">

                <h4>
                    Manager
                </h4>


                @if($managerApproval?->signature_path)

                    <img
                        src="{{ asset('storage/'.$managerApproval->signature_path) }}"
                        class="signature-image"
                    >

                @else

                    <div class="signature-empty">
                        Belum Ditandatangani
                    </div>

                @endif

            </div>


            {{-- DIRECTOR --}}

            <div class="signature-card">

                <h4>
                    Director
                </h4>


                @if($directorApproval?->signature_path)

                    <img
                        src="{{ asset('storage/'.$directorApproval->signature_path) }}"
                        class="signature-image"
                    >

                @else

                    <div class="signature-empty">
                        Belum Ditandatangani
                    </div>

                @endif

            </div>

        </div>

    </section>



    {{-- =========================================================
         DIRECTOR APPROVAL
    ========================================================== --}}

    @if(
        $directorApproval &&
        $directorApproval->status ===
        \App\Models\LeaveApproval::STATUS_PENDING
    )

        <section class="approval-card">

            <div class="approval-card-header">

                <div>

                    <h2>

                        <i class="fa-solid fa-pen-nib"></i>

                        Approval Director

                    </h2>

                    <p>
                        Berikan tanda tangan digital dan keputusan akhir.
                    </p>

                </div>

            </div>


            <form
                action="{{ route('director.approval.process', $leave->id) }}"
                method="POST"
                id="directorApprovalForm"
            >

                @csrf


                <input
                    type="hidden"
                    name="signature"
                    id="directorSignature"
                >


                <div class="approval-form">


                    {{-- SIGNATURE --}}

                    <div class="signature-pad-wrapper">

                        <label>
                            Tanda Tangan Digital
                        </label>


                        <div class="signature-container">

                            <canvas
                                id="director-signature-pad"
                            ></canvas>

                        </div>


                        <small class="signature-info">

                            Silakan tanda tangan menggunakan
                            mouse atau touchpad.

                        </small>


                        <button
                            type="button"
                            class="btn-clear"
                            id="clearDirectorSignature"
                        >

                            <i class="fa-solid fa-eraser"></i>

                            Hapus Tanda Tangan

                        </button>

                    </div>


                    {{-- NOTES --}}

                    <div class="approval-note">

                        <label>
                            Catatan
                        </label>

                        <textarea
                            name="notes"
                            rows="6"
                            placeholder="Tambahkan catatan apabila diperlukan..."
                        ></textarea>

                    </div>


                    {{-- ACTION --}}

                    <div class="approval-action">

                        <button
                            type="submit"
                            class="btn-reject"
                            name="action"
                            value="Rejected"
                        >

                            <i class="fa-solid fa-circle-xmark"></i>

                            Reject

                        </button>


                        <button
                            type="submit"
                            class="btn-approve"
                            name="action"
                            value="Approved"
                        >

                            <i class="fa-solid fa-circle-check"></i>

                            Approve

                        </button>

                    </div>

                </div>

            </form>

        </section>

    @else

        <section class="approval-card">

            <div class="approval-card-header">

                <h2>

                    <i class="fa-solid fa-circle-check"></i>

                    Approval Director

                </h2>

            </div>


            <div class="approval-finished">

                @if($directorApproval?->status === 'Approved')

                    <i class="fa-solid fa-circle-check"></i>

                    <h3>
                        Approval Sudah Disetujui
                    </h3>

                    <p>
                        Pengajuan cuti telah disetujui dan
                        proses approval telah selesai.
                    </p>

                @elseif($directorApproval?->status === 'Rejected')

                    <i class="fa-solid fa-circle-xmark"></i>

                    <h3>
                        Pengajuan Ditolak
                    </h3>

                    <p>
                        Pengajuan cuti telah ditolak oleh Director.
                    </p>

                @else

                    <i class="fa-solid fa-clock"></i>

                    <h3>
                        Menunggu Approval
                    </h3>

                    <p>
                        Pengajuan belum dapat diproses.
                    </p>

                @endif

            </div>

        </section>

    @endif


</div>


{{-- =========================================================
     SIGNATURE PAD
========================================================== --}}

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
const directorCanvas =
    document.getElementById('director-signature-pad');

if (directorCanvas) {

    function resizeDirectorCanvas() {

        const ratio =
            Math.max(
                window.devicePixelRatio || 1,
                1
            );

        directorCanvas.width =
            directorCanvas.offsetWidth * ratio;

        directorCanvas.height =
            directorCanvas.offsetHeight * ratio;

        directorCanvas
            .getContext('2d')
            .scale(ratio, ratio);
    }

    resizeDirectorCanvas();

    const directorSignaturePad =
        new SignaturePad(
            directorCanvas,
            {
                backgroundColor: '#ffffff',
                penColor: '#111827',
                minWidth: 1.2,
                maxWidth: 2.8,
                velocityFilterWeight: 0.7
            }
        );

    window.addEventListener(
        'resize',
        function () {

            resizeDirectorCanvas();

            directorSignaturePad.clear();

        }
    );

    document
        .getElementById('clearDirectorSignature')
        .addEventListener(
            'click',
            function () {

                directorSignaturePad.clear();

            }
        );

    document
        .getElementById('directorApprovalForm')
        .addEventListener(
            'submit',
            function (e) {

                if (
                    directorSignaturePad.isEmpty()
                ) {

                    alert(
                        'Silakan tanda tangan terlebih dahulu.'
                    );

                    e.preventDefault();

                    return;
                }

                document
                    .getElementById('directorSignature')
                    .value =
                    directorSignaturePad.toDataURL(
                        'image/png'
                    );
            }
        );
}
</script>

@endpush

@endsection