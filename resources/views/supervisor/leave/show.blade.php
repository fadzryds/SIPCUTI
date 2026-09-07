@extends('supervisor.layouts.app')

@section('title', 'Detail Pengajuan Cuti')

@push('styles')
    @vite('resources/css/supervisor/leave.css'),
    @vite('resources/js/supervisor/leave.js')
@endpush

@section('content')

<div class="supervisor-leave-detail">

    {{-- =====================================================
        BREADCRUMB
    ====================================================== --}}

    <nav class="detail-breadcrumb">

        <a href="{{ route('supervisor.dashboard') }}">
            Dashboard
        </a>

        <span>/</span>

        <a href="{{ route('supervisor.leave.index') }}">
            Persetujuan Cuti
        </a>

        <span>/</span>

        <strong>
            Detail Pengajuan
        </strong>

    </nav>


    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}

    <header class="detail-header">

        <div class="detail-header-content">

            <span class="detail-eyebrow">
                LEAVE APPROVAL
            </span>

            <h1>
                Detail Pengajuan Cuti
            </h1>

            <p>
                Periksa seluruh informasi pengajuan sebelum memberikan
                keputusan persetujuan.
            </p>

        </div>


        <div class="detail-request-number">

            <span>
                NOMOR PENGAJUAN
            </span>

            <strong>
                {{ $leave->request_number }}
            </strong>

        </div>

    </header>


    {{-- =====================================================
        FLASH MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="supervisor-alert success">
            <span class="alert-icon">✓</span>

            <div>
                <strong>Berhasil</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>

    @endif


    @if(session('error'))

        <div class="supervisor-alert error">
            <span class="alert-icon">!</span>

            <div>
                <strong>Terjadi Kesalahan</strong>
                <p>{{ session('error') }}</p>
            </div>
        </div>

    @endif


    {{-- =====================================================
        MAIN CONTENT
    ====================================================== --}}

    <div class="detail-layout">

        {{-- =================================================
            LEFT COLUMN
        ================================================== --}}

        <div class="detail-main">


            {{-- =================================================
                EMPLOYEE CARD
            ================================================== --}}

            <section class="detail-card employee-card">

                <div class="detail-card-header">

                    <div>

                        <span class="section-eyebrow">
                            PEMOHON
                        </span>

                        <h2>
                            Informasi Karyawan
                        </h2>

                    </div>

                </div>


                <div class="employee-profile">

                    <div class="employee-avatar-large">

                        {{ strtoupper(
                            substr(
                                $leave->employee->user->name ?? 'U',
                                0,
                                1
                            )
                        ) }}

                    </div>


                    <div class="employee-profile-info">

                        <h3>
                            {{ $leave->employee->user->name ?? '-' }}
                        </h3>

                        <span>
                            {{ $leave->employee->position->name ?? '-' }}
                        </span>

                        <small>
                            {{ $leave->employee->department->name ?? '-' }}
                        </small>

                    </div>

                </div>


                <div class="employee-meta-grid">

                    <div class="meta-item">

                        <span>
                            NIK
                        </span>

                        <strong>
                            {{ $leave->employee->nik ?? '-' }}
                        </strong>

                    </div>


                    <div class="meta-item">

                        <span>
                            Department
                        </span>

                        <strong>
                            {{ $leave->employee->department->name ?? '-' }}
                        </strong>

                    </div>


                    <div class="meta-item">

                        <span>
                            Jabatan
                        </span>

                        <strong>
                            {{ $leave->employee->position->name ?? '-' }}
                        </strong>

                    </div>

                </div>

            </section>


            {{-- =================================================
                LEAVE INFORMATION
            ================================================== --}}

            <section class="detail-card">

                <div class="detail-card-header">

                    <div>

                        <span class="section-eyebrow">
                            PENGAJUAN
                        </span>

                        <h2>
                            Informasi Cuti
                        </h2>

                    </div>


                    @if($leave->status === 'Pending')

                        <span class="status-badge pending">
                            Menunggu

                        </span>

                    @elseif($leave->status === 'Approved')

                        <span class="status-badge approved">
                            Disetujui
                        </span>

                    @elseif($leave->status === 'Rejected')

                        <span class="status-badge rejected">
                            Ditolak
                        </span>

                    @else

                        <span class="status-badge">
                            {{ $leave->status }}
                        </span>

                    @endif

                </div>


                <div class="leave-information-grid">

                    <div class="information-item">

                        <span>
                            Jenis Cuti
                        </span>

                        <strong>
                            {{ $leave->leaveType->name ?? '-' }}
                        </strong>

                    </div>


                    <div class="information-item">

                        <span>
                            Lama Cuti
                        </span>

                        <strong>
                            {{ $leave->total_days }}
                            Hari
                        </strong>

                    </div>


                    <div class="information-item">

                        <span>
                            Tanggal Mulai
                        </span>

                        <strong>
                            {{ \Carbon\Carbon::parse($leave->start_date)->translatedFormat('d F Y') }}
                        </strong>

                    </div>


                    <div class="information-item">

                        <span>
                            Tanggal Selesai
                        </span>

                        <strong>
                            {{ \Carbon\Carbon::parse($leave->end_date)->translatedFormat('d F Y') }}
                        </strong>

                    </div>


                    <div class="information-item">

                        <span>
                            Diajukan Pada
                        </span>

                        <strong>
                            {{ optional($leave->submitted_at)->translatedFormat('d F Y, H:i') }}
                        </strong>

                    </div>


                    <div class="information-item">

                        <span>
                            Nomor Pengajuan
                        </span>

                        <strong>
                            {{ $leave->request_number }}
                        </strong>

                    </div>

                </div>

            </section>


            {{-- =================================================
                REASON
            ================================================== --}}

            <section class="detail-card">

                <div class="detail-card-header">

                    <div>

                        <span class="section-eyebrow">
                            KETERANGAN
                        </span>

                        <h2>
                            Alasan Cuti
                        </h2>

                    </div>

                </div>


                <div class="reason-content">

                    <p>
                        {{ $leave->reason ?: 'Tidak ada alasan yang diberikan.' }}
                    </p>

                </div>

            </section>


            {{-- =================================================
                ATTACHMENT
            ================================================== --}}

            @if($leave->attachment)

                <section class="detail-card">

                    <div class="detail-card-header">

                        <div>

                            <span class="section-eyebrow">
                                DOKUMEN
                            </span>

                            <h2>
                                Lampiran
                            </h2>

                        </div>

                    </div>


                    <div class="attachment-box">

                        <div class="attachment-icon">
                            📎
                        </div>


                        <div class="attachment-info">

                            <strong>
                                Dokumen Pendukung
                            </strong>

                            <span>
                                Lampiran pengajuan cuti
                            </span>

                        </div>


                        <a
                            href="{{ asset('storage/' . $leave->attachment) }}"
                            target="_blank"
                            class="attachment-button">

                            Lihat Dokumen
                            <span>↗</span>

                        </a>

                    </div>

                </section>

            @endif


            {{-- =================================================
                APPROVAL WORKFLOW
            ================================================== --}}

            <section class="detail-card">

                <div class="detail-card-header">

                    <div>

                        <span class="section-eyebrow">
                            APPROVAL FLOW
                        </span>

                        <h2>
                            Workflow Persetujuan
                        </h2>

                    </div>

                </div>


                <div class="approval-timeline">

                    @foreach($leave->approvals as $item)

                        <div class="approval-timeline-item">

                            <div class="timeline-marker
                                {{ strtolower($item->status) }}">

                                @if($item->status === 'Approved')
                                    ✓
                                @elseif($item->status === 'Rejected')
                                    ×
                                @elseif($item->status === 'Pending')
                                    •
                                @else
                                    —
                                @endif

                            </div>


                            <div class="timeline-content">

                                <div class="timeline-top">

                                    <div>

                                        <span class="timeline-level">
                                            {{ $item->approval_level }}
                                        </span>

                                        <strong>
                                            {{ $item->approver->name ?? '-' }}
                                        </strong>

                                    </div>


                                    @if($item->status === 'Approved')

                                        <span class="status-badge approved">
                                            Disetujui
                                        </span>

                                    @elseif($item->status === 'Rejected')

                                        <span class="status-badge rejected">
                                            Ditolak
                                        </span>

                                    @elseif($item->status === 'Pending')

                                        <span class="status-badge pending">
                                            Menunggu
                                        </span>

                                    @else

                                        <span class="status-badge">
                                            {{ $item->status }}
                                        </span>

                                    @endif

                                </div>


                                <p class="timeline-description">

                                    @if($item->status === 'Approved')

                                        Disetujui pada
                                        {{ optional($item->approved_at)->translatedFormat('d F Y, H:i') }}

                                    @elseif($item->status === 'Rejected')

                                        Pengajuan ditolak oleh
                                        {{ $item->approver->name ?? '-' }}

                                    @elseif($item->status === 'Pending')

                                        Menunggu keputusan
                                        {{ $item->approval_level }}

                                    @else

                                        Belum diproses

                                    @endif

                                </p>


                                @if(
                                    $item->status === 'Rejected'
                                    && $item->rejection_reason
                                )

                                    <div class="rejection-reason">

                                        <strong>
                                            Alasan Penolakan
                                        </strong>

                                        <p>
                                            {{ $item->rejection_reason }}
                                        </p>

                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        </div>


        {{-- =================================================
            RIGHT COLUMN
        ================================================== --}}

        <aside class="detail-sidebar">


            {{-- =================================================
    APPROVAL ACTION
================================================== --}}

@if($approval->status === 'Pending')

    <section class="decision-card">

        <div class="decision-header">

            <span class="decision-eyebrow">
                ACTION REQUIRED
            </span>

            <h2>
                Keputusan Supervisor
            </h2>

            <p>
                Pengajuan ini membutuhkan keputusan Anda.
            </p>

        </div>


        {{-- =================================================
            APPROVE FORM
        ================================================== --}}

        <form
            id="approve-form"
            method="POST"
            action="{{ route('supervisor.leave.approve', $leave) }}">

            @csrf


            {{-- =================================================
                SIGNATURE
            ================================================== --}}

            <div class="signature-section">

                <label for="signature-pad">
                    Tanda Tangan Supervisor
                </label>


                <div class="signature-box">

                    <canvas
                        id="signature-pad"
                        aria-label="Area tanda tangan supervisor">
                    </canvas>

                </div>


                {{-- SATU-SATUNYA INPUT SIGNATURE --}}

                <input
                    type="hidden"
                    name="signature"
                    id="approve-signature"
                    value="">


                <div class="signature-actions">

                    <button
                        type="button"
                        id="clear-signature"
                        class="btn-secondary">

                        Hapus Tanda Tangan

                    </button>

                </div>

            </div>


            {{-- =================================================
                APPROVE BUTTON
            ================================================== --}}

            <button
                type="submit"
                class="decision-button approve">

                <span class="button-icon">
                    ✓
                </span>

                <span>

                    <strong>
                        Setujui Pengajuan
                    </strong>

                    <small>
                        Teruskan ke Manager
                    </small>

                </span>

            </button>

        </form>


        {{-- =================================================
            REJECT BUTTON
        ================================================== --}}

        <button
            type="button"
            class="decision-button reject"
            id="open-reject">

            <span class="button-icon">
                ×
            </span>

            <span>

                <strong>
                    Tolak Pengajuan
                </strong>

                <small>
                    Pengajuan tidak disetujui
                </small>

            </span>

        </button>


        {{-- =================================================
            NOTE
        ================================================== --}}

        <div class="decision-note">

            <span>ⓘ</span>

            <p>
                Pastikan informasi pengajuan sudah diperiksa
                sebelum memberikan keputusan.
            </p>

        </div>

    </section>


    {{-- =================================================
        REJECTION FORM
    ================================================== --}}

    <section
        class="detail-card rejection-panel"
        id="reject-panel"
        style="display:none;">

        <div class="detail-card-header">

            <div>

                <span class="section-eyebrow">
                    REJECTION
                </span>

                <h2>
                    Alasan Penolakan
                </h2>

            </div>

        </div>


        <form
            method="POST"
            action="{{ route('supervisor.leave.reject', $leave) }}">

            @csrf


            <div class="form-group">

                <label for="rejection_reason">
                    Alasan Penolakan
                </label>

                <textarea
                    id="rejection_reason"
                    name="rejection_reason"
                    rows="6"
                    maxlength="1000"
                    required
                    placeholder="Jelaskan alasan pengajuan ini ditolak..."></textarea>

                <small>
                    Maksimal 1000 karakter.
                </small>

            </div>


            <div class="rejection-actions">

                <button
                    type="submit"
                    class="btn-danger">

                    Konfirmasi Penolakan

                </button>


                <button
                    type="button"
                    id="cancel-reject"
                    class="btn-light">

                    Batal

                </button>

            </div>

        </form>

    </section>

@else

    {{-- =================================================
        PROCESSED
    ================================================== --}}

    <section class="processed-card">

        <div class="processed-icon">

            @if($approval->status === 'Approved')
                ✓
            @else
                ×
            @endif

        </div>


        <span>
            APPROVAL SELESAI
        </span>


        <h2>
            Pengajuan Sudah Diproses
        </h2>


        <p>
            Status keputusan Supervisor:
        </p>


        <strong class="
            {{ $approval->status === 'Approved'
                ? 'approved-text'
                : 'rejected-text' }}">

            {{ $approval->status }}

        </strong>

    </section>

@endif


            {{-- =================================================
                BACK
            ================================================== --}}

            <a
                href="{{ route('supervisor.leave.index') }}"
                class="back-button">

                <span>
                    ←
                </span>

                Kembali ke Persetujuan Cuti

            </a>

        </aside>

    </div>

</div>

@endsection