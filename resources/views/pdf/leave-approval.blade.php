<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Surat Persetujuan Cuti</title>

<style>

body{

    font-family: DejaVu Sans, sans-serif;

    color:#222;

    font-size:13px;

    line-height:1.6;

}

.header{

    width:100%;

    border-bottom:3px solid #1e40af;

    padding-bottom:18px;

    margin-bottom:25px;

}

.logo{

    width:70px;

    float:left;

}

.company{

    margin-left:90px;

}

.company h2{

    margin:0;

    color:#1e40af;

    font-size:24px;

}

.company h4{

    margin:4px 0;

    font-size:16px;

    font-weight:600;

}

.company p{

    margin:0;

    font-size:12px;

}

.clear{

    clear:both;

}

.title{

    text-align:center;

    margin:30px 0;

}

.title h2{

    margin:0;

    font-size:22px;

}

.title p{

    margin-top:8px;

    color:#666;

}

.section{

    margin-top:25px;

}

.section-title{

    background:#1e40af;

    color:#fff;

    padding:8px 12px;

    font-size:14px;

    font-weight:bold;

}

table{

    width:100%;

    border-collapse:collapse;

}

.info td{

    padding:7px 5px;

    vertical-align:top;

}

.info td:first-child{

    width:180px;

    font-weight:bold;

}

.reason{

    margin-top:15px;

    border:1px solid #dcdcdc;

    padding:15px;

    border-radius:5px;

    background:#fafafa;

}

.approval-table{

    margin-top:15px;

}

.approval-table th{

    background:#f3f4f6;

    border:1px solid #dcdcdc;

    padding:8px;

}

.approval-table td{

    border:1px solid #dcdcdc;

    padding:10px;

}

.status-approved{

    color:#16a34a;

    font-weight:bold;

}

.status-rejected{

    color:#dc2626;

    font-weight:bold;

}

.status-pending{

    color:#f59e0b;

    font-weight:bold;

}

.signature{

    margin-top:12px;

    text-align:center;

}

.signature img{

    height:70px;

}

.footer{

    margin-top:50px;

    text-align:center;

    font-size:11px;

    color:#777;

}

hr{

    border:none;

    border-top:1px solid #ddd;

    margin:25px 0;

}

.section{

    margin-top:20px;

}

.section-title{

    font-size:15px;

    font-weight:bold;

    background:#0f4c81;

    color:#fff;

    padding:8px 12px;

    margin-bottom:12px;

}

.info{

    width:100%;

    border-collapse:collapse;

}

.info td{

    padding:6px 4px;

    vertical-align:top;

}

.reason{

    border:1px solid #dcdcdc;

    padding:15px;

    background:#fafafa;

    line-height:1.8;

}

.approval-table{

    width:100%;

    border-collapse:collapse;

    margin-top:10px;

}

.approval-table th{

    background:#0f4c81;

    color:white;

    border:1px solid #cfcfcf;

    padding:8px;

    font-size:12px;

}

.approval-table td{

    border:1px solid #dcdcdc;

    padding:8px;

    font-size:11px;

}

.status-approved{

    color:#0a8b37;

    font-weight:bold;

}

.status-rejected{

    color:#c62828;

    font-weight:bold;

}

.status-pending{

    color:#f39c12;

    font-weight:bold;

}

.note-box{

    margin-top:30px;

    border:1px solid #ddd;

    background:#fafafa;

    padding:15px;

    font-size:11px;

}

.note-box ul{

    margin-top:10px;

    margin-left:18px;

}

.note-box li{

    margin-bottom:6px;

}

.footer-sign{

    width:100%;

    margin-top:45px;

    border-top:1px solid #ddd;

    padding-top:12px;

}

.footer-sign small{

    color:#666;

    font-size:10px;

}

.watermark{

    position:fixed;

    top:42%;

    left:16%;

    transform:rotate(-35deg);

    font-size:92px;

    font-weight:900;

    opacity:.08;

    z-index:-1;

    letter-spacing:8px;

}

.watermark.approved{

    color:#0a8b37;

}

.watermark.rejected{

    color:#d32f2f;

}

</style>

</head>

<body>

<div class="header">

    {{-- Logo Perusahaan --}}

    {{-- Ganti logo sesuai perusahaan Anda --}}

    {{-- Jika belum ada logo boleh dikosongkan --}}

    {{--

    <img
        src="{{ public_path('images/logo.png') }}"
        class="logo">

    --}}

    <div class="company">

        <h2>PT. NAMA PERUSAHAAN</h2>

        <h4>Sistem Informasi Pengajuan Cuti Karyawan</h4>

        <p>

            Jl. Contoh Alamat Perusahaan

        </p>

        <p>

            Email : hrd@perusahaan.com

        </p>

    </div>

    <div class="clear"></div>

</div>

<div class="title">

    <h2>

        SURAT PERSETUJUAN CUTI

    </h2>

    <p>

        Nomor Pengajuan :
        <strong>

            {{ $leave->request_number }}

        </strong>

    </p>

</div>

{{-- ====================================================== --}}
{{-- DATA KARYAWAN --}}
{{-- ====================================================== --}}

<div class="section">

    <div class="section-title">

        INFORMASI KARYAWAN

    </div>

    <table class="info">

        <tr>

            <td>Nama Lengkap</td>

            <td>: {{ $leave->employee->user->name }}</td>

        </tr>

        <tr>

            <td>NIK</td>

            <td>: {{ $leave->employee->nik }}</td>

        </tr>

        <tr>

            <td>Department</td>

            <td>: {{ $leave->employee->department->name }}</td>

        </tr>

        <tr>

            <td>Jabatan</td>

            <td>: {{ $leave->employee->position->name }}</td>

        </tr>

        <tr>

            <td>Email</td>

            <td>: {{ $leave->employee->user->email }}</td>

        </tr>

        <tr>

            <td>Status Pegawai</td>

            <td>: {{ $leave->employee->status }}</td>

        </tr>

    </table>

</div>

<hr>

{{-- ====================================================== --}}
{{-- INFORMASI CUTI --}}
{{-- ====================================================== --}}

<div class="section">

    <div class="section-title">

        INFORMASI PENGAJUAN CUTI

    </div>

    <table class="info">

        <tr>

            <td>Nomor Pengajuan</td>

            <td>

                : {{ $leave->request_number }}

            </td>

        </tr>

        <tr>

            <td>Jenis Cuti</td>

            <td>

                : {{ $leave->leaveType->name }}

            </td>

        </tr>

        <tr>

            <td>Tanggal Pengajuan</td>

            <td>

                : {{ optional($leave->submitted_at)->format('d F Y') }}

            </td>

        </tr>

        <tr>

            <td>Tanggal Mulai</td>

            <td>

                : {{ $leave->start_date->format('d F Y') }}

            </td>

        </tr>

        <tr>

            <td>Tanggal Selesai</td>

            <td>

                : {{ $leave->end_date->format('d F Y') }}

            </td>

        </tr>

        <tr>

            <td>Total Hari</td>

            <td>

                : {{ $leave->total_days }} Hari

            </td>

        </tr>

        <tr>

            <td>Status Pengajuan</td>

            <td>

                :

                @if($leave->status=='Approved')

                    <span class="status-approved">

                        DISETUJUI

                    </span>

                @elseif($leave->status=='Rejected')

                    <span class="status-rejected">

                        DITOLAK

                    </span>

                @else

                    <span class="status-pending">

                        MENUNGGU PERSETUJUAN

                    </span>

                @endif

            </td>

        </tr>

    </table>

</div>

{{-- ====================================================== --}}
{{-- ALASAN CUTI --}}
{{-- ====================================================== --}}

<div class="section">

    <div class="section-title">

        ALASAN PENGAJUAN CUTI

    </div>

    <div class="reason">

        {{ $leave->reason }}

    </div>

</div>

{{-- ====================================================== --}}
{{-- RIWAYAT APPROVAL --}}
{{-- ====================================================== --}}

@php

    $managerApproval = $leave->approvals
        ->where('approval_level','Manager')
        ->first();

    $hrdApproval = $leave->approvals
        ->where('approval_level','HRD')
        ->first();

@endphp

<div class="section">

    <div class="section-title">

        RIWAYAT PERSETUJUAN

    </div>

    <table class="approval-table">

        <thead>

            <tr>

                <th width="15%">Level</th>

                <th width="15%">Status</th>

                <th width="22%">Approver</th>

                <th width="20%">Tanggal</th>

                <th>Catatan</th>

            </tr>

        </thead>

        <tbody>

            {{-- ========================= --}}
            {{-- MANAGER --}}
            {{-- ========================= --}}

            <tr>

                <td>

                    Manager

                </td>

                <td>

                    @if($managerApproval)

                        @if($managerApproval->status=='Approved')

                            <span class="status-approved">

                                APPROVED

                            </span>

                        @elseif($managerApproval->status=='Rejected')

                            <span class="status-rejected">

                                REJECTED

                            </span>

                        @else

                            <span class="status-pending">

                                PENDING

                            </span>

                        @endif

                    @else

                        -

                    @endif

                </td>

                <td>

                    {{ optional($managerApproval?->approver)->name ?? '-' }}

                </td>

                <td>

                    {{ optional($managerApproval?->approved_at)->format('d F Y H:i') ?? '-' }}

                </td>

                <td>

                    {{ $managerApproval->notes ?? '-' }}

                </td>

            </tr>

            {{-- ========================= --}}
            {{-- HRD --}}
            {{-- ========================= --}}

            <tr>

                <td>

                    HRD

                </td>

                <td>

                    @if($hrdApproval)

                        @if($hrdApproval->status=='Approved')

                            <span class="status-approved">

                                APPROVED

                            </span>

                        @elseif($hrdApproval->status=='Rejected')

                            <span class="status-rejected">

                                REJECTED

                            </span>

                        @else

                            <span class="status-pending">

                                PENDING

                            </span>

                        @endif

                    @else

                        -

                    @endif

                </td>

                <td>

                    {{ optional($hrdApproval?->approver)->name ?? '-' }}

                </td>

                <td>

                    {{ optional($hrdApproval?->approved_at)->format('d F Y H:i') ?? '-' }}

                </td>

                <td>

                    {{ $hrdApproval->notes ?? '-' }}

                </td>

            </tr>

        </tbody>

    </table>

</div>

<hr>

{{-- ====================================================== --}}
{{-- TANDA TANGAN DIGITAL --}}
{{-- ====================================================== --}}

<div class="section">

    <table width="100%">

        <tr>

            {{-- ========================= --}}
            {{-- MANAGER --}}
            {{-- ========================= --}}

            <td align="center" width="50%">

                <strong>

                    Manager

                </strong>

                <br><br>

                @if($managerApproval && $managerApproval->signature_path)

                    <img
                        src="{{ public_path('storage/'.$managerApproval->signature_path) }}"
                        style="height:90px;">

                @else

                    <div style="height:90px;"></div>

                @endif

                <br>

                <strong>

                    {{ optional($managerApproval?->approver)->name }}

                </strong>

            </td>

            {{-- ========================= --}}
            {{-- HRD --}}
            {{-- ========================= --}}

            <td align="center" width="50%">

                <strong>

                    Human Resource Department

                </strong>

                <br><br>

                @if($hrdApproval && $hrdApproval->signature_path)

                    <img
                        src="{{ public_path('storage/'.$hrdApproval->signature_path) }}"
                        style="height:90px;">

                @else

                    <div style="height:90px;"></div>

                @endif

                <br>

                <strong>

                    {{ optional($hrdApproval?->approver)->name }}

                </strong>

            </td>

        </tr>

    </table>

</div>

{{-- ====================================================== --}}
{{-- LAMPIRAN --}}
{{-- ====================================================== --}}

@if($leave->attachment)

<div class="section">

    <div class="section-title">

        DOKUMEN LAMPIRAN

    </div>

    <p>

        Dokumen pendukung telah dilampirkan oleh karyawan pada saat
        pengajuan cuti.

    </p>

    <table class="info">

        <tr>

            <td width="180">

                Nama File

            </td>

            <td>

                :

                {{ basename($leave->attachment) }}

            </td>

        </tr>

    </table>

</div>

@endif


{{-- ====================================================== --}}
{{-- CATATAN --}}
{{-- ====================================================== --}}

<div class="note-box">

    <strong>Catatan :</strong>

    <ul>

        <li>
            Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi Pengajuan Cuti.
        </li>

        <li>
            Dokumen ini sah apabila telah memiliki persetujuan Manager dan HRD.
        </li>

        <li>
            Seluruh tanda tangan yang tercantum merupakan tanda tangan digital yang tersimpan pada sistem.
        </li>

        <li>
            Dilarang melakukan perubahan isi dokumen tanpa persetujuan perusahaan.
        </li>

    </ul>

</div>


{{-- ====================================================== --}}
{{-- FOOTER SIGN --}}
{{-- ====================================================== --}}

<table class="footer-sign">

    <tr>

        <td align="left">

            <small>

                Dicetak :

                {{ now()->format('d F Y H:i:s') }}

            </small>

        </td>

        <td align="right">

            <small>

                Generated by

                Sistem Informasi Pengajuan Cuti

            </small>

        </td>

    </tr>

</table>


{{-- ====================================================== --}}
{{-- WATERMARK --}}
{{-- ====================================================== --}}

@if($leave->status=='Approved')

<div class="watermark approved">

    APPROVED

</div>

@elseif($leave->status=='Rejected')

<div class="watermark rejected">

    REJECTED

</div>

@endif