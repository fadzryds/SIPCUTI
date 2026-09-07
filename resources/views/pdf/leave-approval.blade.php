<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Formulir Pengajuan Cuti</title>

    <style>

        /* =========================================================
           PAGE
        ========================================================= */

        @page {
            size: A4;
            margin: 4cm 5.5cm 2.8cm 4cm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            background: #fff;
            font-size: 12pt;
            line-height: 2;
        }


        /* =========================================================
           INNER PAGE
        ========================================================= */

        .page {
            width: 70%;
            margin: 0 auto;
            padding-top: 1.8cm;
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .header {
            width: 100%;
            height: 78px;
            position: relative;
            margin-bottom: 22px;
        }


        /* =========================================================
           LOGO
        ========================================================= */

        .logo-area {
            position: absolute;
            left: 0;
            top: 2px;

            width: 60px;
            height: 60px;

            text-align: left;
        }

        .logo {
            display: block;

            width: 69px;
            height: 64px;

            filter: grayscale(100%);
            -webkit-filter: grayscale(100%);
        }


        /* =========================================================
           COMPANY
        ========================================================= */

        .company-area {
            position: absolute;

            left: 70px;
            padding-top: 10px;

            margin: 0;

            line-height: 1.05;

            text-align: left;
        }

        .company-name {
            margin: 0;

            font-size: 10.5pt;
            font-weight: bold;

            text-transform: uppercase;

            text-decoration: underline;

            white-space: nowrap;
        }

        .company-location {
            margin-top: 2px;

            font-size: 10.5pt;
            font-weight: bold;

            white-space: nowrap;
            text-align: center;
        }


        /* =========================================================
           DOCUMENT NUMBER
        ========================================================= */

        .document-number {
            position: absolute;

            top: 4px;
            right: 0;

            font-size: 8.5pt;

            white-space: nowrap;
        }


        /* =========================================================
           TITLE
        ========================================================= */

        .document-title {
            text-align: center;

            line-height: 1.05;
            margin-top: 18px;
            margin-bottom: 23px;
        }

        .document-title .main-title {
            font-size: 14pt;
            font-weight: bold;

            letter-spacing: 1.5px;

            text-decoration: underline;

            margin: 0;
        }

        .document-title .subtitle {
            font-size: 12pt;
            font-weight: bold;

            margin-top: 3px;
        }


        /* =========================================================
           RECIPIENT
        ========================================================= */

        .recipient {
            margin-bottom: 21px;

            line-height: 1.25;
        }

        .recipient p {
            margin: 0;
        }


        /* =========================================================
           OPENING
        ========================================================= */

        .opening {
            margin-bottom: 15px;
        }

        .opening p {
            margin: 0 0 12px 0;
        }


        /* =========================================================
           FORM TABLE
        ========================================================= */

        .form-table {
            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }

        .form-table td {
            vertical-align: top;

            padding: 3px 0;

            font-size: 11.5pt;
        }

        .form-label {
            width: 30%;

            padding-right: 8px !important;

            white-space: nowrap;
        }

        .form-colon {
            width: 4%;

            text-align: center;
        }

        .form-value {
            width: 66%;

            padding-left: 8px !important;
        }

        .dotted-value {
            display: block;

            width: 70%;

            min-height: 18px;

            border-bottom: 1px dotted #000;

            padding-bottom: 1px;
        }

        .normal-value {
            display: block;

            width: 100%;

            min-height: 18px;

            padding-bottom: 1px;
        }

        .leave-request {
            width: 100%;

            margin-top: 5px;
            margin-bottom: 8px;

            font-size: 11.5pt;

            line-height: 1.35;

            white-space: nowrap;
        }

        .leave-request-text {
            display: inline;
        }

        .leave-days {
            display: inline-block;

            width: 105px;

            margin-left: 3px;
            margin-right: 3px;

            border-bottom: 1px dotted #000;

            text-align: center;

            line-height: 18px;
            height: 19px;

            vertical-align: baseline;
        }

        .leave-days-value {
            display: inline-block;

            min-width: 35px;

            text-align: center;
        }


        /* =========================================================
           DETAIL CUTI
        ========================================================= */

        .leave-detail-table {
            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }

        .leave-detail-table td {
            vertical-align: top;

            padding: 4px 0;

            font-size: 11.5pt;
        }

        .leave-detail-label {
            width: 30%;

            white-space: nowrap;

            padding-right: 8px !important;
        }

        .leave-detail-colon {
            width: 4%;

            text-align: center;
        }

        .leave-detail-value {
            width: 66%;

            padding-left: 8px !important;
        }

        .leave-detail-line {
            display: block;

            width: 70%;

            min-height: 18px;

            border-bottom: 1px dotted #000;

            padding-bottom: 1px;
        }


        /* =========================================================
           CLOSING
        ========================================================= */

        .closing {
            margin-top: 12px;
            margin-bottom: 13px;

            line-height: 1.4;
        }


        /* =========================================================
           SIGNATURE
        ========================================================= */

        .signature-section {
            width: 100%;

            margin-top: 30px;
        }

        .signature-date {
            width: 100%;

            margin-bottom: 7px;

            border-collapse: collapse;
        }

        .signature-date td {
            font-size: 11pt;
        }

        .signature-table {
            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }

        .signature-table td {
            width: 25%;

            text-align: center;

            vertical-align: top;

            padding: 0 5px;
        }

        /* =========================================================
           SIGNATURE HEADER
           ========================================================= */

        .signature-approval-heading {
            height: 20px;

            padding: 0 5px !important;

            text-align: center;
            vertical-align: top;

            font-family: "Times New Roman", Times, serif;
            font-size: 10.5pt;
            font-weight: normal;

            line-height: 20px;
        }


        /* =========================================================
           SIGNATURE ROLE
           ========================================================= */

        .signature-role {
            height: 20px;

            padding: 0 5px;

            text-align: center;
            vertical-align: top;

            font-family: "Times New Roman", Times, serif;
            font-size: 10.5pt;
            font-weight: normal;

            line-height: 20px;
        }


        /* =========================================================
           MANAGER / SNR MANAGER
           ========================================================= */

        .signature-name.manager-name {
            white-space: nowrap;
            font-size: 10pt;
        }

        .signature-space {
            height: 72px;

            position: relative;
        }

        .signature-image {
            display: block;

            margin: 8px auto 2px auto;

            width: auto;

            height: 62px;

            max-width: 100px;

            object-fit: contain;
        }

        .signature-placeholder {
            height: 62px;
        }

        .signature-line {
            width: 88%;

            margin: 0 auto;

            border-bottom: 1px dotted #000;

            height: 1px;
        }

        .signature-name {
            margin-top: 5px;

            font-size: 10.5pt;

            min-height: 16px;
        }


        /* =========================================================
           NOTE
        ========================================================= */

        .note {
            margin-top: 18px;

            font-size: 9.5pt;

            font-style: italic;

            font-weight: bold;

            text-align: left;
        }

        .note-title {
            text-decoration: underline;
        }


        /* =========================================================
           HELPERS
        ========================================================= */

        .no-border {
            border: none !important;
        }

        .black {
            color: #000 !important;
        }

    </style>

</head>


<body>

@php

    /*
    |--------------------------------------------------------------------------
    | APPROVAL COLLECTION
    |--------------------------------------------------------------------------
    */

    $approvals = collect($leave->approvals ?? []);


    /*
    |--------------------------------------------------------------------------
    | FIND APPROVAL
    |--------------------------------------------------------------------------
    */

    $findApproval = function ($levels) use ($approvals) {

        return $approvals->first(function ($approval) use ($levels) {

            $level = strtolower(
                trim(
                    (string) ($approval->approval_level ?? '')
                )
            );

            foreach ($levels as $expectedLevel) {

                if (
                    $level ===
                    strtolower(
                        trim($expectedLevel)
                    )
                ) {
                    return true;
                }

            }

            return false;
        });

    };


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE APPROVAL
    |--------------------------------------------------------------------------
    */

    $employeeApproval = $findApproval([
        'Employee',
        'Karyawan',
        'Employee Approval',
    ]);


    /*
    |--------------------------------------------------------------------------
    | SUPERVISOR
    |--------------------------------------------------------------------------
    */

    $supervisorApproval = $findApproval([
        'Supervisor',
        'Supervisor Approval',
    ]);


    /*
    |--------------------------------------------------------------------------
    | MANAGER
    |--------------------------------------------------------------------------
    */

    $managerApproval = $findApproval([
        'Manager',
        'Manager/Snr Manager',
        'Manager / Snr Manager',
        'Senior Manager',
        'Snr Manager',
    ]);


    /*
    |--------------------------------------------------------------------------
    | DIRECTOR
    |--------------------------------------------------------------------------
    */

    $directorApproval = $findApproval([
        'Director',
        'Direktur',
        'Director Approval',
    ]);


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE
    |--------------------------------------------------------------------------
    */

    $employeeName =
        data_get(
            $leave,
            'employee.user.name'
        ) ?? '-';


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE SIGNATURE
    |--------------------------------------------------------------------------
    */

    $employeeSignature =
        data_get(
            $leave,
            'employee_signature_path'
        );


    /*
    |--------------------------------------------------------------------------
    | FALLBACK EMPLOYEE APPROVAL
    |--------------------------------------------------------------------------
    */

    if (
        !$employeeSignature &&
        $employeeApproval
    ) {

        $employeeSignature =
            data_get(
                $employeeApproval,
                'signature_path'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE SIGNATURE FULL PATH
    |--------------------------------------------------------------------------
    */

    $employeeSignatureFullPath = null;

    if ($employeeSignature) {

        $employeeSignatureFullPath =
            public_path(
                'storage/' .
                ltrim(
                    $employeeSignature,
                    '/'
                )
            );

    }


    /*
    |--------------------------------------------------------------------------
    | SUPERVISOR SIGNATURE
    |--------------------------------------------------------------------------
    */

    $supervisorSignature =
        data_get(
            $supervisorApproval,
            'signature_path'
        );


    $supervisorSignatureFullPath = null;

    if ($supervisorSignature) {

        $supervisorSignatureFullPath =
            public_path(
                'storage/' .
                ltrim(
                    $supervisorSignature,
                    '/'
                )
            );

    }


    /*
    |--------------------------------------------------------------------------
    | MANAGER SIGNATURE
    |--------------------------------------------------------------------------
    */

    $managerSignature =
        data_get(
            $managerApproval,
            'signature_path'
        );


    $managerSignatureFullPath = null;

    if ($managerSignature) {

        $managerSignatureFullPath =
            public_path(
                'storage/' .
                ltrim(
                    $managerSignature,
                    '/'
                )
            );

    }


    /*
    |--------------------------------------------------------------------------
    | DIRECTOR SIGNATURE
    |--------------------------------------------------------------------------
    */

    $directorSignature =
        data_get(
            $directorApproval,
            'signature_path'
        );


    $directorSignatureFullPath = null;

    if ($directorSignature) {

        $directorSignatureFullPath =
            public_path(
                'storage/' .
                ltrim(
                    $directorSignature,
                    '/'
                )
            );

    }


    /*
    |--------------------------------------------------------------------------
    | APPROVER NAMES
    |--------------------------------------------------------------------------
    */

    $supervisorName =
        data_get(
            $supervisorApproval,
            'approver.name'
        ) ?? '-';


    $managerName =
        data_get(
            $managerApproval,
            'approver.name'
        ) ?? '-';


    $directorName =
        data_get(
            $directorApproval,
            'approver.name'
        ) ?? '-';

@endphp


<div class="page">


    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="header">


        {{-- LOGO --}}

        <div class="logo-area">

            @php

                $logoPath =
                    public_path(
                        'assets/images/toa.png'
                    );

            @endphp

            @if(file_exists($logoPath))

                <img
                    src="{{ $logoPath }}"
                    class="logo"
                    alt="Logo"
                >

            @endif

        </div>


        {{-- COMPANY NAME --}}

        <div class="company-area">

            <div class="company-name">
                PT. TOA GALVA INDUSTRIES
            </div>

            <div class="company-location">
                TAPOS - DEPOK
            </div>

        </div>


        {{-- DOCUMENT NUMBER --}}

        <div class="document-number">
            No. Dok : FRM-PSN-035.Rev.03
        </div>


    </div>


    {{-- =========================================================
         TITLE
    ========================================================== --}}

    <div class="document-title">

        <div class="main-title">
            FORMULIR ISIAN
        </div>

        <div class="subtitle">
            PENGAJUAN CUTI PRIBADI
        </div>

    </div>


    {{-- =========================================================
         RECIPIENT
    ========================================================== --}}

    <div class="recipient">

        <p>Yang Terhormat,</p>

        <p>Departemen Umum &amp; Personalia</p>

        <p>Di Tempat</p>

    </div>


    {{-- =========================================================
         OPENING
    ========================================================== --}}

    <div class="opening">

        <p>
            Dengan hormat,
        </p>

        <p>
            Yang bertanda tangan dibawah ini,
        </p>

    </div>


    {{-- =========================================================
         EMPLOYEE DATA
    ========================================================== --}}

    <table class="form-table">

        <tr>

            <td class="form-label">
                Nama
            </td>

            <td class="form-colon">
                :
            </td>

            <td class="form-value">

                <span class="dotted-value">
                    {{ $employeeName }}
                </span>

            </td>

        </tr>


        <tr>

            <td class="form-label">
                Jabatan
            </td>

            <td class="form-colon">
                :
            </td>

            <td class="form-value">

                <span class="dotted-value">
                    {{ data_get($leave, 'employee.position.name') ?? '-' }}
                </span>

            </td>

        </tr>


        <tr>

            <td class="form-label">
                Departemen / Bagian
            </td>

            <td class="form-colon">
                :
            </td>

            <td class="form-value">

                <span class="dotted-value">
                    {{ data_get($leave, 'employee.department.name') ?? '-' }}
                </span>

            </td>

        </tr>

    </table>


    {{-- =========================================================
         LEAVE REQUEST
         SESUAI DENGAN FORM ASLI
    ========================================================== --}}

    <div class="leave-request">

        <span class="leave-request-text">
            Mengajukan permohonan cuti pribadi sejumlah
        </span>

        <span class="leave-days">

            <span class="leave-days-value">
                {{ $leave->total_days }}
            </span>

        </span>

        <span class="leave-request-text">
            hari kerja, pada :
        </span>

    </div>


    {{-- =========================================================
         DATE / REASON / REMAINING LEAVE
    ========================================================== --}}

    <table class="leave-detail-table">


        {{-- HARI / TANGGAL --}}

        <tr>

            <td class="leave-detail-label">
                Hari/tanggal
            </td>

            <td class="leave-detail-colon">
                :
            </td>

            <td class="leave-detail-value">

                <span class="leave-detail-line">

                    {{ $leave->start_date->translatedFormat('d F Y') }}

                    @if(
                        $leave->end_date &&
                        $leave->end_date->ne($leave->start_date)
                    )

                        s/d

                        {{ $leave->end_date->translatedFormat('d F Y') }}

                    @endif

                </span>

            </td>

        </tr>


        {{-- ALASAN --}}

        <tr>

            <td class="leave-detail-label">
                Alasan
            </td>

            <td class="leave-detail-colon">
                :
            </td>

            <td class="leave-detail-value">

                <span class="leave-detail-line">

                    {{ $leave->reason ?? '-' }}

                </span>

            </td>

        </tr>


        {{-- SISA CUTI --}}

        <tr>

            <td class="leave-detail-label">
                Sisa cuti
            </td>

            <td class="leave-detail-colon">
                :
            </td>

            <td class="leave-detail-value">

                <span class="leave-detail-line">

                    {{
                        data_get(
                            $leave,
                            'leaveBalance.remaining'
                        )
                        ??
                        data_get(
                            $leave,
                            'remaining_leave'
                        )
                        ??
                        '-'
                    }}

                </span>

            </td>

        </tr>

    </table>


    {{-- =========================================================
         CLOSING
    ========================================================== --}}

    <div class="closing">

        Permohonan cuti ini saya sampaikan, atas perhatian
        Bapak/Ibu saya ucapkan terima kasih.

    </div>


    {{-- =========================================================
         SIGNATURE DATE
    ========================================================== --}}

    <table class="signature-date">

        <tr>

            <td align="left">

                Depok,

                {{
                    optional($leave->submitted_at)
                        ->translatedFormat('d F Y')
                    ??
                    now()->translatedFormat('d F Y')
                }}

            </td>

        </tr>

    </table>

    {{-- =========================================================
         SIGNATURE TABLE
    ========================================================== --}}
    
    <table class="signature-table">
    
        {{-- APPROVAL HEADER --}}

        <tr>

            {{-- KARYAWAN --}}

            <td class="signature-approval-heading">
                Yang mengajukan,
            </td>


            {{-- SUPERVISOR --}}

            <td class="signature-approval-heading">
            </td>


            {{-- MANAGER + DIREKTUR --}}

            <td
                colspan="2"
                class="signature-approval-heading"
            >
                Menyetujui,
            </td>

        </tr>
    
        <tr>
    
    
            {{-- =====================================================
                 EMPLOYEE
            ====================================================== --}}
    
            <td>

                <div class="signature-role"></div>

                <div class="signature-space">
    
                    @if(
                        $employeeSignatureFullPath &&
                        file_exists($employeeSignatureFullPath)
                    )
    
                        <img
                            src="{{ $employeeSignatureFullPath }}"
                            class="signature-image"
                            alt="Tanda tangan karyawan"
                        >
    
                    @else
    
                        <div class="signature-placeholder"></div>
    
                    @endif
    
                </div>
    
    
                <div class="signature-line"></div>
    
    
                <div class="signature-name">
                    {{ $employeeName }}
                </div>
    
            </td>
    
    
            {{-- =====================================================
                 SUPERVISOR
            ====================================================== --}}
    
            <td>
    
                <div class="signature-role"></div>
    
    
                <div class="signature-space">
    
                    @if(
                        $supervisorApproval &&
                        $supervisorApproval->status === 'Approved' &&
                        $supervisorSignatureFullPath &&
                        file_exists($supervisorSignatureFullPath)
                    )
    
                        <img
                            src="{{ $supervisorSignatureFullPath }}"
                            class="signature-image"
                            alt="Tanda tangan Supervisor"
                        >
    
                    @else
    
                        <div class="signature-placeholder"></div>
    
                    @endif
    
                </div>
    
    
                <div class="signature-line"></div>
    
    
                <div class="signature-name">
                    Supervisor
                </div>
    
            </td>
    
    
            {{-- =====================================================
                 MANAGER
            ====================================================== --}}
    
            <td>
    
                <div class="signature-role"></div>
    
    
                <div class="signature-space">
    
                    @if(
                        $managerApproval &&
                        $managerApproval->status === 'Approved' &&
                        $managerSignatureFullPath &&
                        file_exists($managerSignatureFullPath)
                    )
    
                        <img
                            src="{{ $managerSignatureFullPath }}"
                            class="signature-image"
                            alt="Tanda tangan Manager"
                        >
    
                    @else
    
                        <div class="signature-placeholder"></div>
    
                    @endif
    
                </div>
    
    
                <div class="signature-line"></div>
    
    
                <div class="signature-name manager-name">
                    Manager/Snr Manager
                </div>
    
            </td>
    
    
            {{-- =====================================================
                 DIRECTOR
            ====================================================== --}}
    
            <td>
    
                <div class="signature-role"></div>
    
    
                <div class="signature-space">
    
                    @if(
                        $directorApproval &&
                        $directorApproval->status === 'Approved' &&
                        $directorSignatureFullPath &&
                        file_exists($directorSignatureFullPath)
                    )
    
                        <img
                            src="{{ $directorSignatureFullPath }}"
                            class="signature-image"
                            alt="Tanda tangan Direktur"
                        >
    
                    @else
    
                        <div class="signature-placeholder"></div>
    
                    @endif
    
                </div>
    
    
                <div class="signature-line"></div>
    
    
                <div class="signature-name">
                    Direktur
                </div>
    
            </td>
    
        </tr>
    
    </table>

    {{-- =========================================================
         NOTE
    ========================================================== --}}

    <div class="note">

        <span class="note-title">
            Catatan :
        </span>

        Disetujui oleh Direktur untuk jabatan
        Supervisor 3/Manager/Senior Manager

    </div>


</div>

</body>

</html>