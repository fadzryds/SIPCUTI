@extends('hrd.layouts.app')

@section('title', 'Edit Saldo Cuti')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/hrd/leave-balances.css') }}"
    >
@endpush

@section('content')

<div class="leave-balance-page">

    {{-- HEADER --}}
    <div class="leave-balance-header">

        <div>

            <div class="leave-balance-eyebrow">

                <i class="fas fa-calendar-check"></i>

                HRD MANAGEMENT

            </div>

            <h1>
                Edit Saldo Cuti
            </h1>

            <p>
                Perbarui hak cuti dan carry forward karyawan.
            </p>

        </div>


        <a
            href="{{ route('hrd.leave-balances.index') }}"
            class="btn-back"
        >

            <i class="fas fa-arrow-left"></i>

            Kembali

        </a>

    </div>


    <div class="balance-edit-layout">


        {{-- EMPLOYEE PROFILE --}}
        <div class="employee-profile-card">

            <div class="employee-profile-avatar">

                {{
                    strtoupper(
                        substr(
                            $leaveBalance->employee?->user?->name ?? 'K',
                            0,
                            1
                        )
                    )
                }}

            </div>


            <h2>

                {{
                    $leaveBalance->employee?->user?->name
                    ?? 'Tidak diketahui'
                }}

            </h2>


            <span class="employee-nik">

                NIK:

                {{
                    $leaveBalance->employee?->nik
                    ?? '-'
                }}

            </span>


            <div class="employee-detail-list">

                <div>

                    <span>
                        Department
                    </span>

                    <strong>
                        {{
                            $leaveBalance->employee?->department?->name
                            ?? '-'
                        }}
                    </strong>

                </div>


                <div>

                    <span>
                        Position
                    </span>

                    <strong>
                        {{
                            $leaveBalance->employee?->position?->name
                            ?? '-'
                        }}
                    </strong>

                </div>


                <div>

                    <span>
                        Jenis Cuti
                    </span>

                    <strong>
                        {{
                            $leaveBalance->leaveType?->name
                            ?? '-'
                        }}
                    </strong>

                </div>


                <div>

                    <span>
                        Tahun
                    </span>

                    <strong>
                        {{ $leaveBalance->year }}
                    </strong>

                </div>

            </div>


            {{-- CURRENT BALANCE --}}
            <div class="current-balance-box">

                <span>
                    Saldo Saat Ini
                </span>

                <div>

                    <strong>
                        {{ $leaveBalance->remaining }}
                    </strong>

                    <small>
                        hari tersisa
                    </small>

                </div>

            </div>

        </div>


        {{-- FORM --}}
        <div class="balance-edit-card">

            <div class="edit-card-header">

                <div class="edit-icon">

                    <i class="fas fa-sliders-h"></i>

                </div>

                <div>

                    <h2>
                        Pengaturan Saldo
                    </h2>

                    <p>
                        Atur hak cuti dan carry forward.
                    </p>

                </div>

            </div>


            <form
                method="POST"
                action="{{
                    route(
                        'hrd.leave-balances.update',
                        $leaveBalance
                    )
                }}"
                class="balance-edit-form"
            >

                @csrf

                @method('PUT')


                {{-- QUOTA --}}
                <div class="form-field">

                    <label for="quota">

                        Hak Cuti

                        <span>*</span>

                    </label>


                    <div class="number-input">

                        <input
                            type="number"
                            id="quota"
                            name="quota"
                            value="{{ old('quota', $leaveBalance->quota) }}"
                            min="{{ $leaveBalance->used }}"
                            max="365"
                            required
                        >

                        <span>
                            hari
                        </span>

                    </div>


                    @error('quota')

                        <small class="form-error">

                            <i class="fas fa-exclamation-circle"></i>

                            {{ $message }}

                        </small>

                    @enderror


                    <small class="form-help">

                        Hak cuti tahunan default perusahaan adalah
                        <strong>12 hari</strong>.

                        Nilai dapat disesuaikan HRD sesuai kebijakan.

                    </small>

                </div>


                {{-- USED --}}
                <div class="form-field readonly-field">

                    <label>
                        Cuti Terpakai
                    </label>


                    <div class="readonly-value">

                        <strong>
                            {{ $leaveBalance->used }}
                        </strong>

                        <span>
                            hari
                        </span>

                        <i class="fas fa-lock"></i>

                    </div>


                    <small class="form-help">

                        Nilai ini dihitung otomatis dari pengajuan cuti
                        yang telah disetujui.

                    </small>

                </div>


                {{-- REMAINING --}}
                <div class="form-field readonly-field">

                    <label>
                        Sisa Cuti
                    </label>


                    <div class="readonly-value remaining-preview">

                        <strong id="remainingPreview">

                            {{
                                max(
                                    0,
                                    $leaveBalance->quota
                                    -
                                    $leaveBalance->used
                                )
                            }}

                        </strong>

                        <span>
                            hari
                        </span>

                        <i class="fas fa-calculator"></i>

                    </div>


                    <small class="form-help">

                        Sisa otomatis dihitung:

                        <strong>
                            Hak Cuti − Terpakai
                        </strong>

                    </small>

                </div>


                {{-- CARRY FORWARD --}}
                <div class="form-field">

                    <label for="carry_forward">

                        Carry Forward

                    </label>


                    <div class="number-input">

                        <input
                            type="number"
                            id="carry_forward"
                            name="carry_forward"
                            value="{{
                                old(
                                    'carry_forward',
                                    $leaveBalance->carry_forward
                                )
                            }}"
                            min="0"
                            max="365"
                        >

                        <span>
                            hari
                        </span>

                    </div>


                    @error('carry_forward')

                        <small class="form-error">

                            <i class="fas fa-exclamation-circle"></i>

                            {{ $message }}

                        </small>

                    @enderror


                    <small class="form-help">

                        Jumlah cuti dari tahun sebelumnya
                        yang dibawa ke tahun berjalan.

                    </small>

                </div>


                {{-- PREVIEW --}}
                <div class="balance-calculation">

                    <div class="calculation-title">

                        <i class="fas fa-calculator"></i>

                        Preview Perhitungan

                    </div>


                    <div class="calculation-row">

                        <span>
                            Hak Cuti
                        </span>

                        <strong id="quotaCalculation">
                            {{ $leaveBalance->quota }}
                        </strong>

                        <span>
                            hari
                        </span>

                    </div>


                    <div class="calculation-row minus">

                        <span>
                            Terpakai
                        </span>

                        <strong>
                            {{ $leaveBalance->used }}
                        </strong>

                        <span>
                            hari
                        </span>

                    </div>


                    <div class="calculation-divider"></div>


                    <div class="calculation-row total">

                        <span>
                            Sisa
                        </span>

                        <strong id="remainingCalculation">

                            {{
                                max(
                                    0,
                                    $leaveBalance->quota
                                    -
                                    $leaveBalance->used
                                )
                            }}

                        </strong>

                        <span>
                            hari
                        </span>

                    </div>

                </div>


                {{-- ACTION --}}
                <div class="edit-form-actions">

                    <a
                        href="{{ route('hrd.leave-balances.index') }}"
                        class="btn-cancel"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="btn-save"
                    >

                        <i class="fas fa-save"></i>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const quotaInput =
        document.getElementById('quota');

    const remainingPreview =
        document.getElementById('remainingPreview');

    const remainingCalculation =
        document.getElementById('remainingCalculation');

    const quotaCalculation =
        document.getElementById('quotaCalculation');

    const used =
        {{ (int) $leaveBalance->used }};


    function updateRemaining()
    {
        const quota =
            parseInt(quotaInput.value) || 0;


        const remaining =
            Math.max(
                0,
                quota - used
            );


        remainingPreview.textContent =
            remaining;


        remainingCalculation.textContent =
            remaining;


        quotaCalculation.textContent =
            quota;
    }


    quotaInput.addEventListener(
        'input',
        updateRemaining
    );


    updateRemaining();

});

</script>

@endpush

@endsection