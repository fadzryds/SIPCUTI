@extends('manager.layouts.app')

@section('title','Detail Approval')

@section('content')

@php

$managerApproval = $leave->approvals
    ->where('approval_level', \App\Models\LeaveApproval::LEVEL_MANAGER)
    ->first();

$hrdApproval = $leave->approvals
    ->where('approval_level', \App\Models\LeaveApproval::LEVEL_HRD)
    ->first();

@endphp

<div class="approval-page approval-detail-page">

    {{-- ========================================================= --}}
    {{-- HERO --}}
    {{-- ========================================================= --}}

    <section class="approval-hero">

        <div class="hero-left">

            <a
                href="{{ route('manager.approval.index') }}"
                class="approval-back">

                <i class="fa-solid fa-arrow-left"></i>

                Kembali ke Approval

            </a>

            <span class="hero-tag">

                <i class="fa-solid fa-file-signature"></i>

                Approval Manager

            </span>

            <h1>

                Detail Pengajuan Cuti

            </h1>

            <p>

                Silakan lakukan pemeriksaan terhadap data pengajuan cuti
                sebelum memberikan keputusan approval.

            </p>

        </div>

        <div class="hero-right">

            <div class="hero-icon">

                {{ strtoupper(substr($leave->employee->user->name,0,1)) }}

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <section class="approval-summary">

        <div class="summary-card info">

            <div class="summary-icon">

                <i class="fa-solid fa-hashtag"></i>

            </div>

            <div>

                <small>Nomor Pengajuan</small>

                <h2>

                    {{ $leave->request_number }}

                </h2>

                <span>Request Number</span>

            </div>

        </div>

        <div class="summary-card success">

            <div class="summary-icon">

                <i class="fa-solid fa-calendar-days"></i>

            </div>

            <div>

                <small>Total Hari</small>

                <h2>

                    {{ $leave->total_days }}

                </h2>

                <span>Hari Cuti</span>

            </div>

        </div>

        <div class="summary-card warning">

            <div class="summary-icon">

                <i class="fa-solid fa-file-lines"></i>

            </div>

            <div>

                <small>Jenis Cuti</small>

                <h2>

                    {{ $leave->leaveType->name }}

                </h2>

                <span>Leave Type</span>

            </div>

        </div>

        <div class="summary-card

            @if(optional($managerApproval)->status=='Approved')

                success

            @elseif(optional($managerApproval)->status=='Rejected')

                danger

            @else

                warning

            @endif

        ">

            <div class="summary-icon">

                @if(optional($managerApproval)->status=='Approved')

                    <i class="fa-solid fa-circle-check"></i>

                @elseif(optional($managerApproval)->status=='Rejected')

                    <i class="fa-solid fa-circle-xmark"></i>

                @else

                    <i class="fa-solid fa-clock"></i>

                @endif

            </div>

            <div>

                <small>Status Manager</small>

                <h2>

                    {{ optional($managerApproval)->status ?? 'Waiting' }}

                </h2>

                <span>Approval Status</span>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- INFORMASI KARYAWAN --}}
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-user"></i>

                    Informasi Karyawan

                </h2>

                <p>

                    Informasi lengkap mengenai karyawan yang mengajukan cuti.

                </p>

            </div>

        </div>

        <div class="approval-grid">

            <div class="approval-item">

                <label>Nama Lengkap</label>

                <strong>

                    {{ $leave->employee->user->name }}

                </strong>

            </div>

            <div class="approval-item">

                <label>NIK</label>

                <strong>

                    {{ $leave->employee->nik }}

                </strong>

            </div>

            <div class="approval-item">

                <label>Department</label>

                <strong>

                    {{ $leave->employee->department->name }}

                </strong>

            </div>

            <div class="approval-item">

                <label>Jabatan</label>

                <strong>

                    {{ $leave->employee->position->name }}

                </strong>

            </div>

            <div class="approval-item">

                <label>Email</label>

                <strong>

                    {{ $leave->employee->user->email }}

                </strong>

            </div>

            <div class="approval-item">

                <label>Status</label>

                <strong>

                    {{ $leave->employee->status }}

                </strong>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- DETAIL CUTI --}}
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-calendar-check"></i>

                    Detail Pengajuan

                </h2>

                <p>

                    Informasi mengenai pengajuan cuti yang diajukan.

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

            <div>

                <h2>

                    <i class="fa-solid fa-paperclip"></i>

                    Lampiran Pendukung

                </h2>

                <p>

                    Dokumen pendukung yang diunggah oleh karyawan.

                </p>

            </div>

        </div>

        @if($leave->attachment)

            @php

                $extension = strtolower(pathinfo($leave->attachment, PATHINFO_EXTENSION));

            @endphp

            <div class="approval-attachment">

                @if(in_array($extension,['jpg','jpeg','png','webp']))

                    <img
                        src="{{ asset('storage/'.$leave->attachment) }}"
                        class="attachment-image">

                @elseif($extension=='pdf')

                    <iframe
                        src="{{ asset('storage/'.$leave->attachment) }}"
                        class="attachment-pdf">

                    </iframe>

                @else

                    <div class="attachment-file">

                        <i class="fa-solid fa-file"></i>

                        File Lampiran

                    </div>

                @endif

            </div>

            <div class="attachment-action">

                <a
                    href="{{ asset('storage/'.$leave->attachment) }}"
                    target="_blank"
                    class="btn-primary">

                    <i class="fa-solid fa-download"></i>

                    Download Lampiran

                </a>

            </div>

        @else

            <div class="approval-empty">

                <i class="fa-solid fa-folder-open"></i>

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
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-list-check"></i>

                    Timeline Approval

                </h2>

                <p>

                    Riwayat proses persetujuan cuti.

                </p>

            </div>

        </div>

        <div class="approval-timeline">

            @foreach($leave->approvals as $approval)

                <div class="timeline-item">

                    <div class="timeline-circle

                        @if($approval->status=='Approved')

                            success

                        @elseif($approval->status=='Rejected')

                            danger

                        @elseif($approval->status=='Pending')

                            warning

                        @else

                            waiting

                        @endif

                    ">

                        @if($approval->status=='Approved')

                            <i class="fa-solid fa-check"></i>

                        @elseif($approval->status=='Rejected')

                            <i class="fa-solid fa-xmark"></i>

                        @elseif($approval->status=='Pending')

                            <i class="fa-solid fa-clock"></i>

                        @else

                            <i class="fa-solid fa-hourglass-half"></i>

                        @endif

                    </div>

                    <div class="timeline-content">

                        <h4>

                            {{ $approval->approval_level }}

                        </h4>

                        <span>

                            {{ $approval->approver?->name }}

                        </span>

                        <p>

                            Status :

                            <strong>

                                {{ $approval->status }}

                            </strong>

                        </p>

                        @if($approval->approved_at)

                            <small>

                                {{ $approval->approved_at->format('d F Y H:i') }}

                            </small>

                        @endif

                        @if($approval->notes)

                            <div class="timeline-note">

                                {{ $approval->notes }}

                            </div>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- PREVIEW TANDA TANGAN --}}
    {{-- ========================================================= --}}

    <section class="approval-card">

        <div class="approval-card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-signature"></i>

                    Tanda Tangan Digital

                </h2>

                <p>

                    Status tanda tangan Manager dan HRD.

                </p>

            </div>

        </div>

        <div class="signature-grid">

            <div class="signature-card">

                <h4>

                    Manager

                </h4>

                @if($managerApproval?->signature_path)

                    <img
                        src="{{ asset('storage/'.$managerApproval->signature_path) }}"
                        class="signature-image">

                @else

                    <div class="signature-empty">

                        Belum Ditandatangani

                    </div>

                @endif

            </div>

            <div class="signature-card">

                <h4>

                    HRD

                </h4>

                @if($hrdApproval?->signature_path)

                    <img
                        src="{{ asset('storage/'.$hrdApproval->signature_path) }}"
                        class="signature-image">

                @else

                    <div class="signature-empty">

                        Menunggu Approval Manager

                    </div>

                @endif

            </div>

        
        </div>

    </section>

    {{-- ========================================================= --}}
{{-- MANAGER APPROVAL --}}
{{-- ========================================================= --}}

@if(
    $managerApproval &&
    $managerApproval->status == \App\Models\LeaveApproval::STATUS_PENDING
)

<section class="approval-card">

    <div class="approval-card-header">

        <div>

            <h2>

                <i class="fa-solid fa-pen-nib"></i>

                Approval Manager

            </h2>

            <p>

                Berikan tanda tangan digital dan keputusan approval.

            </p>

        </div>

    </div>

    <form
        action="{{ route('manager.approval.process',$leave->id) }}"
        method="POST"
        id="approvalForm">

        @csrf

        <input
            type="hidden"
            name="signature"
            id="signature">

        <div class="approval-form">

            {{-- Signature --}}

            <div class="signature-pad-wrapper">

                <label>
                    Tanda Tangan Digital
                </label>
            
                <div class="signature-container">
            
                    <canvas id="signature-pad"></canvas>
            
                </div>
            
                <small class="signature-info">
            
                    Silakan tanda tangan menggunakan mouse atau touchpad.
            
                </small>
            
                <button
                    type="button"
                    class="btn-clear"
                    id="clearSignature">
            
                    <i class="fa-solid fa-eraser"></i>
            
                    Hapus Tanda Tangan
            
                </button>
            
            </div>

            {{-- Notes --}}

            <div class="approval-note">

                <label>

                    Catatan

                </label>

                <textarea

                    name="notes"

                    rows="6"

                    placeholder="Tambahkan catatan apabila diperlukan..."></textarea>

            </div>

            {{-- Action --}}

            <div class="approval-action">

                <button

                    type="submit"

                    class="btn-reject"

                    name="action"

                    value="Rejected">

                    <i class="fa-solid fa-circle-xmark"></i>

                    Reject

                </button>

                <button

                    type="submit"

                    class="btn-approve"

                    name="action"

                    value="Approved">

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

            Approval Manager

        </h2>

    </div>

    <div class="approval-finished">

        <i class="fa-solid fa-circle-check"></i>

        <h3>

            Approval Sudah Diproses

        </h3>

        <p>

            Pengajuan ini telah diproses oleh Manager.

        </p>

    </div>

</section>

@endif

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>

const canvas = document.getElementById('signature-pad');

if(canvas){

    function resizeCanvas(){

        const ratio = Math.max(window.devicePixelRatio || 1,1);

        canvas.width = canvas.offsetWidth * ratio;

        canvas.height = canvas.offsetHeight * ratio;

        canvas.getContext("2d").scale(ratio,ratio);

    }

    resizeCanvas();

    const signaturePad = new SignaturePad(canvas,{

        backgroundColor:"#ffffff",

        penColor:"#111827",

        minWidth:1.2,

        maxWidth:2.8,

        velocityFilterWeight:0.7

    });

    window.addEventListener('resize',function(){

        resizeCanvas();

        signaturePad.clear();

    });

    document
        .getElementById('clearSignature')
        .addEventListener('click',function(){

            signaturePad.clear();

        });

    document
        .getElementById('approvalForm')
        .addEventListener('submit',function(e){

            if(signaturePad.isEmpty()){

                alert("Silakan tanda tangan terlebih dahulu.");

                e.preventDefault();

                return;

            }

            document.getElementById('signature').value =
                signaturePad.toDataURL("image/png");

        });

}

</script>

@endpush
@endsection