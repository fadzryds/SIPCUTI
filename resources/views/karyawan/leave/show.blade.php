@extends('layouts.app')

@section('title', 'Detail Approval')

@vite([
    'resources/css/karyawan/show-leave.css',
])

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | APPROVAL DATA
    |--------------------------------------------------------------------------
    | Alur:
    | Supervisor -> Manager -> Director
    |--------------------------------------------------------------------------
    */

    $supervisorApproval = $leave->approvals
        ->where(
            'approval_level',
            \App\Models\LeaveApproval::LEVEL_SUPERVISOR
        )
        ->first();

    $managerApproval = $leave->approvals
        ->where(
            'approval_level',
            \App\Models\LeaveApproval::LEVEL_MANAGER
        )
        ->first();

    $directorApproval = $leave->approvals
        ->where(
            'approval_level',
            \App\Models\LeaveApproval::LEVEL_DIRECTOR
        )
        ->first();


    /*
    |--------------------------------------------------------------------------
    | STATUS DIRECTOR
    |--------------------------------------------------------------------------
    */

    $directorStatus = $directorApproval?->status ?? 'Waiting';


    /*
    |--------------------------------------------------------------------------
    | TIMELINE DATA
    |--------------------------------------------------------------------------
    | Dibuat sebagai array tetap agar ketiga level SELALU tampil.
    */

    $approvalSteps = [
        [
            'level' => \App\Models\LeaveApproval::LEVEL_SUPERVISOR,
            'label' => 'Supervisor',
            'approval' => $supervisorApproval,
        ],
        [
            'level' => \App\Models\LeaveApproval::LEVEL_MANAGER,
            'label' => 'Manager',
            'approval' => $managerApproval,
        ],
        [
            'level' => \App\Models\LeaveApproval::LEVEL_DIRECTOR,
            'label' => 'Director',
            'approval' => $directorApproval,
        ],
    ];

@endphp


<div class="hrd-approval-detail">


    {{-- ========================================================= --}}
    {{-- HERO --}}
    {{-- ========================================================= --}}

    <section class="approval-hero">

        <div class="approval-hero-left">

            <div class="hero-actions">

                <a
                    href="{{ route('employee.leave.index') }}"
                    class="approval-back"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>

                <span class="hero-badge">
                    <i class="fa-solid fa-user"></i>
                    Karyawan
                </span>

            </div>

            <h1>
                Detail Cuti
            </h1>

            <p>
                Lihat detail pengajuan cuti, status persetujuan,
                timeline approval, dan tanda tangan digital.
            </p>

        </div>


        <div class="approval-hero-right">

            <div class="hero-avatar">
                {{ strtoupper(substr($leave->employee->user->name, 0, 1)) }}
            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <section class="approval-summary">


        {{-- REQUEST NUMBER --}}

        <div class="summary-card info">

            <div class="summary-icon">
                <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/hashtag-large.png" alt="hashtag-large"/>
            </div>

            <div class="summary-content">

                <small>
                    Nomor Request
                </small>

                <h2>
                    {{ $leave->request_number }}
                </h2>

                <span>
                    Leave Request
                </span>

            </div>

        </div>


        {{-- TOTAL DAYS --}}

        <div class="summary-card success">

            <div class="summary-icon">
                <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/calendar.png" alt="calendar"/>
            </div>

            <div class="summary-content">

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


        {{-- LEAVE TYPE --}}

        <div class="summary-card warning">

            <div class="summary-icon">
                <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/file.png" alt="file"/>
            </div>

            <div class="summary-content">

                <small>
                    Jenis Cuti
                </small>

                <h2>
                    {{ $leave->leaveType->name }}
                </h2>

                <span>
                    Leave Type
                </span>

            </div>

        </div>


        {{-- DIRECTOR STATUS --}}

        <div class="summary-card
            @if($directorStatus === 'Approved')
                success
            @elseif($directorStatus === 'Rejected')
                danger
            @else
                warning
            @endif
        ">

            <div class="summary-icon">

                @if($directorStatus === 'Approved')

                    <img width="35" height="35" src="https://img.icons8.com/ios/50/FFFFFF/visa-stamp.png" alt="visa-stamp"/>

                @elseif($directorStatus === 'Rejected')

                    <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/circle-xmark.png" alt="circle-xmark"/>

                @else

                    <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/clock.png" alt="clock"/>

                @endif

            </div>


            <div class="summary-content">

                <small>
                    Status Director
                </small>

                <h2>
                    {{ $directorStatus }}
                </h2>

                <span>
                    Approval Status
                </span>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- INFORMASI KARYAWAN --}}
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div class="section-title-icon">
                <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/user.png" alt="user"/>
            </div>

            <div>
                <h2>
                    Informasi Karyawan
                </h2>

                <p>
                    Informasi identitas karyawan yang mengajukan cuti.
                </p>
            </div>

        </div>


        <div class="approval-grid">


            <div class="approval-item">

                <label>
                    Nama
                </label>

                <strong>
                    {{ $leave->employee->user->name }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    NIK
                </label>

                <strong>
                    {{ $leave->employee->nik }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Department
                </label>

                <strong>
                    {{ $leave->employee->department->name }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Jabatan
                </label>

                <strong>
                    {{ $leave->employee->position->name }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Email
                </label>

                <strong>
                    {{ $leave->employee->user->email }}
                </strong>

            </div>


            <div class="approval-item">

                <label>
                    Status Karyawan
                </label>

                <strong>
                    {{ $leave->employee->status }}
                </strong>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- DETAIL PENGAJUAN --}}
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div class="section-title-icon">
                <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/today.png" alt="today"/>
            </div>

            <div>

                <h2>
                    Detail Pengajuan
                </h2>

                <p>
                    Informasi periode dan alasan pengajuan cuti.
                </p>

            </div>

        </div>


        <div class="approval-grid leave-detail-grid">


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
                    {{ $leave->leaveType->name }}
                </strong>

            </div>


            <div class="approval-item approval-full">

                <label>
                    Alasan Pengajuan
                </label>

                <div class="approval-reason">
                    {{ $leave->reason }}
                </div>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- LAMPIRAN --}}
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div class="section-title-icon">
                <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/clipboard.png" alt="clipboard"/>
            </div>

            <div>

                <h2>
                    Lampiran Pendukung
                </h2>

                <p>
                    Dokumen pendukung yang diberikan oleh karyawan.
                </p>

            </div>

        </div>


        @if($leave->attachment)

            @php

                $extension = strtolower(
                    pathinfo(
                        $leave->attachment,
                        PATHINFO_EXTENSION
                    )
                );

            @endphp


            <div class="approval-attachment">


                @if(in_array(
                    $extension,
                    ['jpg', 'jpeg', 'png', 'webp']
                ))

                    <img
                        src="{{ asset('storage/'.$leave->attachment) }}"
                        class="attachment-image"
                        alt="Lampiran"
                    >


                @elseif($extension === 'pdf')

                    <div class="attachment-preview">

                        <iframe
                            src="{{ asset('storage/'.$leave->attachment) }}"
                            class="attachment-pdf"
                            title="Preview PDF"
                        ></iframe>

                    </div>


                @else

                    <div class="attachment-file">

                        <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/file.png" alt="file"/>

                        <span>
                            Lampiran
                        </span>

                    </div>

                @endif

            </div>


            <div class="attachment-action">

                <a
                    href="{{ asset('storage/'.$leave->attachment) }}"
                    target="_blank"
                    class="btn-primary"
                >

                    <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/download.png" alt="download"/>

                    Download Lampiran

                </a>

            </div>


        @else

            <div class="approval-empty">

                <div class="empty-icon">
                    <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/folder-open.png" alt="folder-open"/>
                </div>

                <h3>
                    Tidak Ada Lampiran
                </h3>

                <p>
                    Karyawan tidak mengunggah dokumen pendukung.
                </p>

            </div>

        @endif

    </section>



    {{-- ========================================================= --}}
    {{-- TIMELINE APPROVAL --}}
    {{-- SUPERVISOR -> MANAGER -> DIRECTOR --}}
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div class="section-title-icon">
                <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/route.png" alt="route"/>
            </div>

            <div>

                <h2>
                    Timeline Approval
                </h2>

                <p>
                    Alur persetujuan pengajuan cuti.
                </p>

            </div>

        </div>


        <div class="approval-timeline">

            @foreach($approvalSteps as $index => $step)

                @php

                    $approval = $step['approval'];

                    $status = $approval?->status ?? 'Waiting';

                    $statusClass = match($status) {

                        'Approved' => 'success',

                        'Rejected' => 'danger',

                        'Pending' => 'warning',

                        default => 'waiting',

                    };

                    $icon = match($status) {

                        'Approved' => 'fa-check',

                        'Rejected' => 'fa-xmark',

                        'Pending' => 'fa-clock',

                        default => 'fa-hourglass-half',

                    };

                @endphp


                <div class="timeline-item {{ $statusClass }}">


                    {{-- TIMELINE NODE --}}

                    <div class="timeline-marker">

                        <div class="timeline-circle {{ $statusClass }}">

                            <i class="fa-solid {{ $icon }}"></i>

                        </div>

                    </div>


                    {{-- TIMELINE CONTENT --}}

                    <div class="timeline-content">

                        <div class="timeline-main">

                            <div>

                                <span class="timeline-step">
                                    Tahap {{ $index + 1 }}
                                </span>

                                <h4>
                                    {{ $step['label'] }}
                                </h4>

                                <p class="timeline-approver">

                                    <i class="fa-solid fa-user"></i>

                                    {{ $approval?->approver?->name ?? 'Menunggu approver' }}

                                </p>

                            </div>


                            <span class="timeline-status {{ $statusClass }}">

                                {{ $status }}

                            </span>

                        </div>


                        @if($approval?->approved_at)

                            <div class="timeline-date">

                                <img width="35" height="35" src="https://img.icons8.com/comic/100/FD7E14/today.png" alt="today"/>

                                {{ $approval->approved_at->format('d F Y H:i') }}

                            </div>

                        @endif


                        @if($approval?->notes)

                            <div class="timeline-note">

                                <img width="35" height="35" src="https://img.icons8.com/comic/100/FD7E14/comments.png" alt="comments"/>

                                <span>
                                    {{ $approval->notes }}
                                </span>

                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- DIGITAL SIGNATURE --}}
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div class="section-title-icon">
                <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/sign-up.png" alt="sign-up"/>
            </div>

            <div>

                <h2>
                    Tanda Tangan Digital
                </h2>

                <p>
                    Status tanda tangan setiap tahap persetujuan.
                </p>

            </div>

        </div>


        <div class="signature-grid">


            {{-- SUPERVISOR --}}

            <div class="signature-card">

                <div class="signature-header">

                    <div class="signature-role-icon supervisor">
                        <img width="35" height="35" src="https://img.icons8.com/comic/100/FD7E14/checked-user-male.png" alt="checked-user-male"/>
                    </div>

                    <div>

                        <h4>
                            Supervisor
                        </h4>

                        <span>
                            Tahap 1
                        </span>

                    </div>

                </div>


                @if($supervisorApproval?->signature_path)

                    <div class="signature-preview">

                        <img
                            src="{{ asset('storage/'.$supervisorApproval->signature_path) }}"
                            class="signature-image"
                            alt="Tanda tangan Supervisor"
                        >

                    </div>

                @else

                    <div class="signature-empty">

                        <img width="35" height="35" src="https://img.icons8.com/comic/100/228BE6/sign-up.png" alt="sign-up"/>

                        <span>
                            Menunggu Approval
                        </span>

                        <small>
                            Supervisor
                        </small>

                    </div>

                @endif

            </div>



            {{-- MANAGER --}}

            <div class="signature-card">

                <div class="signature-header">

                    <div class="signature-role-icon manager">
                        <img width="35" height="35" src="https://img.icons8.com/comic/100/FD7E14/businessman.png" alt="businessman"/>
                    </div>

                    <div>

                        <h4>
                            Manager
                        </h4>

                        <span>
                            Tahap 2
                        </span>

                    </div>

                </div>


                @if($managerApproval?->signature_path)

                    <div class="signature-preview">

                        <img
                            src="{{ asset('storage/'.$managerApproval->signature_path) }}"
                            class="signature-image"
                            alt="Tanda tangan Manager"
                        >

                    </div>

                @else

                    <div class="signature-empty">

                        <img width="35" height="35" src="https://img.icons8.com/comic/100/FFFFFF/signature.png" alt="signature"/>

                        <span>
                            Menunggu Approval
                        </span>

                        <small>
                            Manager
                        </small>

                    </div>

                @endif

            </div>



            {{-- DIRECTOR --}}

            <div class="signature-card">

                <div class="signature-header">

                    <div class="signature-role-icon director">
                        <img width="35" height="35" src="https://img.icons8.com/comic/100/FD7E14/businessman.png" alt="businessman"/>
                    </div>

                    <div>

                        <h4>
                            Director
                        </h4>

                        <span>
                            Tahap 3
                        </span>

                    </div>

                </div>


                @if($directorApproval?->signature_path)

                    <div class="signature-preview">

                        <img
                            src="{{ asset('storage/'.$directorApproval->signature_path) }}"
                            class="signature-image"
                            alt="Tanda tangan Director"
                        >

                    </div>

                @else

                    <div class="signature-empty">

                        <img width="35" height="35" src="https://img.icons8.com/comic/100/228BE6/sign-up.png" alt="sign-up"/>

                        <span>
                            Menunggu Approval
                        </span>

                        <small>
                            Director
                        </small>

                    </div>

                @endif

            </div>


        </div>

    </section>


</div>



@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('signature-pad');

    if (!canvas) {
        return;
    }

    const wrapper =
        document.querySelector('.signature-container');

    if (!wrapper) {
        return;
    }


    function resizeCanvas() {

        const ratio = Math.max(
            window.devicePixelRatio || 1,
            1
        );

        const width =
            wrapper.clientWidth - 36;

        const height = 180;


        canvas.width =
            width * ratio;

        canvas.height =
            height * ratio;


        canvas.style.width =
            width + 'px';

        canvas.style.height =
            height + 'px';


        const ctx =
            canvas.getContext('2d');

        ctx.setTransform(
            ratio,
            0,
            0,
            ratio,
            0,
            0
        );

    }


    resizeCanvas();


    window.addEventListener(
        'resize',
        resizeCanvas
    );


    const signaturePad =
        new SignaturePad(
            canvas,
            {
                backgroundColor:
                    'rgb(255,255,255)',

                penColor:
                    'rgb(25,25,25)'
            }
        );


    const clearSignature =
        document.getElementById(
            'clearSignature'
        );


    if (clearSignature) {

        clearSignature.addEventListener(
            'click',
            function () {

                signaturePad.clear();

            }
        );

    }


    const approvalForm =
        document.getElementById(
            'approvalForm'
        );


    if (approvalForm) {

        approvalForm.addEventListener(
            'submit',
            function (e) {

                if (signaturePad.isEmpty()) {

                    alert(
                        'Silakan tanda tangan terlebih dahulu.'
                    );

                    e.preventDefault();

                    return;

                }


                const signature =
                    document.getElementById(
                        'signature'
                    );


                if (signature) {

                    signature.value =
                        signaturePad.toDataURL();

                }

            }
        );

    }

});

</script>

@endpush

@endsection