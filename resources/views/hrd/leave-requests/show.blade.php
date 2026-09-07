@extends('hrd.layouts.app')

@section('title', 'Detail Pengajuan Cuti')

@section('content')

<div class="hrd-page leave-detail-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon leave-request-header-icon">
                <i class="fas fa-file-signature"></i>
            </div>

            <div>

                <span class="page-header-label">
                    MONITORING PENGAJUAN CUTI
                </span>

                <h1>
                    Detail Pengajuan Cuti
                </h1>

                <p>
                    Informasi lengkap dan riwayat proses pengajuan cuti
                    karyawan.
                </p>

            </div>

        </div>


        <div class="page-header-actions">

            <a
                href="{{ route('hrd.leave-requests.index') }}"
                class="btn-secondary"
            >
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

        </div>

    </div>


    {{-- =========================================================
        MAIN DETAIL GRID
    ========================================================== --}}

    <div class="leave-detail-grid">


        {{-- =====================================================
            LEFT COLUMN
        ====================================================== --}}

        <div class="leave-detail-main">


            {{-- =================================================
                REQUEST SUMMARY
            ================================================== --}}

            <div class="leave-detail-card">

                <div class="leave-detail-card-header">

                    <div>

                        <span class="detail-card-label">
                            INFORMASI PENGAJUAN
                        </span>

                        <h2>
                            {{ $leave->request_number }}
                        </h2>

                    </div>


                    <div>

                        @if($leave->status === \App\Models\LeaveRequest::STATUS_APPROVED)

                            <span class="leave-status-badge approved">
                                <span class="leave-status-dot"></span>
                                Approved
                            </span>

                        @elseif($leave->status === \App\Models\LeaveRequest::STATUS_REJECTED)

                            <span class="leave-status-badge rejected">
                                <span class="leave-status-dot"></span>
                                Rejected
                            </span>

                        @else

                            <span class="leave-status-badge pending">
                                <span class="leave-status-dot"></span>
                                Pending
                            </span>

                        @endif

                    </div>

                </div>


                <div class="leave-detail-body">


                    {{-- EMPLOYEE --}}

                    <div class="detail-section">

                        <div class="detail-section-title">

                            <i class="fas fa-user"></i>

                            <span>
                                Data Karyawan
                            </span>

                        </div>


                        <div class="detail-employee-card">

                            <div class="detail-employee-avatar">

                                <i class="fas fa-user"></i>

                            </div>


                            <div class="detail-employee-info">

                                <strong>
                                    {{ $leave->employee?->user?->name ?? 'Tidak diketahui' }}
                                </strong>

                                <span>
                                    NIK:
                                    {{ $leave->employee?->nik ?? '-' }}
                                </span>

                                <small>
                                    {{ $leave->employee?->position?->name ?? 'Position tidak tersedia' }}
                                    ·
                                    {{ $leave->employee?->department?->name ?? 'Department tidak tersedia' }}
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- LEAVE INFORMATION --}}

                    <div class="detail-section">

                        <div class="detail-section-title">

                            <i class="fas fa-calendar-days"></i>

                            <span>
                                Informasi Cuti
                            </span>

                        </div>


                        <div class="detail-information-grid">


                            <div class="detail-information-item">

                                <span>
                                    Jenis Cuti
                                </span>

                                <strong>
                                    {{ $leave->leaveType?->name ?? '-' }}
                                </strong>

                            </div>


                            <div class="detail-information-item">

                                <span>
                                    Durasi
                                </span>

                                <strong>
                                    {{ $leave->total_days }} Hari
                                </strong>

                            </div>


                            <div class="detail-information-item">

                                <span>
                                    Tanggal Mulai
                                </span>

                                <strong>
                                    {{ $leave->start_date?->format('d F Y') ?? '-' }}
                                </strong>

                            </div>


                            <div class="detail-information-item">

                                <span>
                                    Tanggal Selesai
                                </span>

                                <strong>
                                    {{ $leave->end_date?->format('d F Y') ?? '-' }}
                                </strong>

                            </div>


                            <div class="detail-information-item full">

                                <span>
                                    Tanggal Pengajuan
                                </span>

                                <strong>

                                    @if($leave->submitted_at)

                                        {{ $leave->submitted_at->format('d F Y, H:i') }}

                                    @else

                                        Belum disubmit

                                    @endif

                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- REASON --}}

                    <div class="detail-section">

                        <div class="detail-section-title">

                            <i class="fas fa-comment-alt"></i>

                            <span>
                                Alasan Pengajuan
                            </span>

                        </div>


                        <div class="leave-reason-box">

                            @if($leave->reason)

                                {{ $leave->reason }}

                            @else

                                <span class="empty-value">
                                    Tidak ada alasan yang diberikan.
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- ATTACHMENT --}}

                    @if($leave->attachment)

                        <div class="detail-section">

                            <div class="detail-section-title">

                                <i class="fas fa-paperclip"></i>

                                <span>
                                    Lampiran
                                </span>

                            </div>


                            <a
                                href="{{ asset('storage/' . $leave->attachment) }}"
                                target="_blank"
                                class="leave-attachment-card"
                            >

                                <div class="attachment-icon">

                                    <i class="fas fa-file"></i>

                                </div>


                                <div>

                                    <strong>
                                        Lampiran Pengajuan
                                    </strong>

                                    <span>
                                        Klik untuk melihat lampiran
                                    </span>

                                </div>


                                <i class="fas fa-arrow-up-right-from-square"></i>

                            </a>

                        </div>

                    @endif


                    {{-- EMPLOYEE SIGNATURE --}}

                    @if($leave->employee_signature_path)

                        <div class="detail-section">

                            <div class="detail-section-title">

                                <i class="fas fa-signature"></i>

                                <span>
                                    Tanda Tangan Karyawan
                                </span>

                            </div>


                            <div class="signature-box">

                                <img
                                    src="{{ asset('storage/' . $leave->employee_signature_path) }}"
                                    alt="Tanda tangan karyawan"
                                >

                            </div>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                APPROVAL TIMELINE
            ================================================== --}}

            <div class="leave-detail-card">

                <div class="leave-detail-card-header">

                    <div>

                        <span class="detail-card-label">
                            WORKFLOW
                        </span>

                        <h2>
                            Riwayat Approval
                        </h2>

                    </div>

                </div>


                <div class="approval-timeline">

                    @forelse($leave->approvals as $approval)

                        @php

                            $levelClass = match($approval->approval_level) {

                                \App\Models\LeaveApproval::LEVEL_EMPLOYEE =>
                                    'employee',

                                \App\Models\LeaveApproval::LEVEL_SUPERVISOR =>
                                    'supervisor',

                                \App\Models\LeaveApproval::LEVEL_MANAGER =>
                                    'manager',

                                \App\Models\LeaveApproval::LEVEL_DIRECTOR =>
                                    'director',

                                default =>
                                    'default',

                            };

                        @endphp


                        <div class="approval-item">


                            <div class="approval-line">

                                <div class="approval-marker {{ $approval->status === \App\Models\LeaveApproval::STATUS_APPROVED ? 'approved' : ($approval->status === \App\Models\LeaveApproval::STATUS_REJECTED ? 'rejected' : 'pending') }}">

                                    @if($approval->status === \App\Models\LeaveApproval::STATUS_APPROVED)

                                        <i class="fas fa-check"></i>

                                    @elseif($approval->status === \App\Models\LeaveApproval::STATUS_REJECTED)

                                        <i class="fas fa-xmark"></i>

                                    @else

                                        <i class="fas fa-clock"></i>

                                    @endif

                                </div>

                            </div>


                            <div class="approval-content">

                                <div class="approval-header">

                                    <div>

                                        <span class="approval-level {{ $levelClass }}">
                                            {{ $approval->approval_level }}
                                        </span>

                                        <h3>

                                            {{ $approval->approver?->name ?? 'Menunggu approver' }}

                                        </h3>

                                    </div>


                                    @if($approval->status === \App\Models\LeaveApproval::STATUS_APPROVED)

                                        <span class="approval-status approved">
                                            Approved
                                        </span>

                                    @elseif($approval->status === \App\Models\LeaveApproval::STATUS_REJECTED)

                                        <span class="approval-status rejected">
                                            Rejected
                                        </span>

                                    @elseif($approval->status === \App\Models\LeaveApproval::STATUS_PENDING)

                                        <span class="approval-status pending">
                                            Pending
                                        </span>

                                    @else

                                        <span class="approval-status waiting">
                                            Waiting
                                        </span>

                                    @endif

                                </div>


                                <div class="approval-meta">

                                    @if($approval->approved_at)

                                        <span>

                                            <i class="far fa-calendar-check"></i>

                                            {{ $approval->approved_at->format('d M Y, H:i') }}

                                        </span>

                                    @else

                                        <span>

                                            <i class="far fa-clock"></i>

                                            Belum diproses

                                        </span>

                                    @endif

                                </div>


                                @if($approval->notes)

                                    <div class="approval-notes">

                                        <span>
                                            Catatan
                                        </span>

                                        <p>
                                            {{ $approval->notes }}
                                        </p>

                                    </div>

                                @endif


                                @if($approval->signature_path)

                                    <div class="approval-signature">

                                        <span>
                                            Tanda Tangan
                                        </span>

                                        <img
                                            src="{{ asset('storage/' . $approval->signature_path) }}"
                                            alt="Tanda tangan {{ $approval->approver?->name ?? 'approver' }}"
                                        >

                                    </div>

                                @endif

                            </div>

                        </div>

                    @empty

                        <div class="approval-empty">

                            <i class="fas fa-clock-rotate-left"></i>

                            <strong>
                                Belum ada riwayat approval
                            </strong>

                            <span>
                                Data proses approval belum tersedia.
                            </span>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIGHT SIDEBAR
        ====================================================== --}}

        <aside class="leave-detail-sidebar">


            {{-- STATUS CARD --}}

            <div class="leave-sidebar-card">

                <div class="sidebar-card-header">

                    <span>
                        STATUS PENGAJUAN
                    </span>

                    <i class="fas fa-circle-info"></i>

                </div>


                <div class="sidebar-status">

                    @if($leave->status === \App\Models\LeaveRequest::STATUS_APPROVED)

                        <div class="sidebar-status-icon approved">

                            <i class="fas fa-check"></i>

                        </div>

                        <strong>
                            Pengajuan Disetujui
                        </strong>

                        <span>
                            Seluruh proses approval telah selesai.
                        </span>

                    @elseif($leave->status === \App\Models\LeaveRequest::STATUS_REJECTED)

                        <div class="sidebar-status-icon rejected">

                            <i class="fas fa-xmark"></i>

                        </div>

                        <strong>
                            Pengajuan Ditolak
                        </strong>

                        <span>
                            Pengajuan tidak dapat dilanjutkan.
                        </span>

                    @else

                        <div class="sidebar-status-icon pending">

                            <i class="fas fa-clock"></i>

                        </div>

                        <strong>
                            Menunggu Approval
                        </strong>

                        <span>
                            Proses pengajuan masih berjalan.
                        </span>

                    @endif

                </div>

            </div>


            {{-- CURRENT APPROVER --}}

            @if($leave->currentApprover)

                <div class="leave-sidebar-card">

                    <div class="sidebar-card-header">

                        <span>
                            CURRENT APPROVER
                        </span>

                        <i class="fas fa-user-check"></i>

                    </div>


                    <div class="current-approver">

                        <div class="current-approver-avatar">

                            <i class="fas fa-user"></i>

                        </div>


                        <div>

                            <strong>
                                {{ $leave->currentApprover->name }}
                            </strong>

                            <span>
                                Sedang memproses pengajuan
                            </span>

                        </div>

                    </div>

                </div>

            @endif


            {{-- PDF --}}

            @if($leave->status === \App\Models\LeaveRequest::STATUS_APPROVED)

                <div class="leave-sidebar-card pdf-card">

                    <div class="pdf-card-icon">

                        <i class="fas fa-file-pdf"></i>

                    </div>


                    <div class="pdf-card-content">

                        <strong>
                            Surat Cuti
                        </strong>

                        <span>
                            Pengajuan telah disetujui dan surat PDF tersedia.
                        </span>

                    </div>


                    <a
                        href="{{ route(
                            'hrd.leave-requests.download',
                            $leave
                        ) }}"
                        target="_blank"
                        class="btn-download-pdf"
                    >

                        <i class="fas fa-download"></i>

                        Download PDF

                    </a>

                </div>

            @endif


            {{-- MONITORING INFO --}}

            <div class="leave-sidebar-card monitoring-info-card">

                <div class="monitoring-info-icon">

                    <i class="fas fa-eye"></i>

                </div>


                <strong>
                    Mode Monitoring
                </strong>


                <p>
                    HRD memiliki akses untuk memantau detail pengajuan,
                    riwayat approval, dan surat cuti yang telah disetujui.
                </p>


                <span>
                    <i class="fas fa-lock"></i>
                    Tidak memiliki akses approval
                </span>

            </div>


        </aside>

    </div>

</div>

@endsection