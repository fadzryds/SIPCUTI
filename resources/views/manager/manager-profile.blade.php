@extends('manager.layouts.app')

@section('title','Profile Manager')

@section('content')

<div class="manager-profile-page">

    {{-- ========================================================= --}}
    {{-- HERO --}}
    {{-- ========================================================= --}}

    <section class="profile-hero">

        <div class="profile-hero-left">

            <span class="profile-tag">

                <i class="fa-solid fa-user-tie"></i>

                Manager Profile

            </span>

            <h1>

                {{ $manager->user->name }}

            </h1>

            <p>

                Kelola informasi akun Manager serta pantau statistik approval
                pengajuan cuti karyawan.

            </p>

            <div class="profile-role">

                <span>

                    <i class="fa-solid fa-briefcase"></i>

                    {{ $manager->position->name }}

                </span>

                <span>

                    <i class="fa-solid fa-building"></i>

                    {{ $manager->department->name }}

                </span>

            </div>

        </div>

        <div class="profile-hero-right">

            <div class="profile-avatar">

                {{ strtoupper(substr($manager->user->name,0,1)) }}

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- STATISTIC --}}
    {{-- ========================================================= --}}

    <section class="profile-statistic">

        <div class="stat-card pending">

            <div class="stat-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div>

                <span>Pending</span>

                <h2>{{ $pending }}</h2>

                <small>Menunggu Approval</small>

            </div>

        </div>

        <div class="stat-card approved">

            <div class="stat-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>

                <span>Approved</span>

                <h2>{{ $approved }}</h2>

                <small>Disetujui</small>

            </div>

        </div>

        <div class="stat-card rejected">

            <div class="stat-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>

            <div>

                <span>Rejected</span>

                <h2>{{ $rejected }}</h2>

                <small>Ditolak</small>

            </div>

        </div>

        <div class="stat-card total">

            <div class="stat-icon">

                <i class="fa-solid fa-chart-column"></i>

            </div>

            <div>

                <span>Total Approval</span>

                <h2>{{ $totalApproval }}</h2>

                <small>Semua Approval</small>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- PROFILE INFORMATION --}}
    {{-- ========================================================= --}}

    <section class="profile-card">

        <div class="card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-id-card"></i>

                    Informasi Manager

                </h2>

                <p>

                    Informasi lengkap akun manager.

                </p>

            </div>

        </div>

        <div class="profile-grid">

            <div class="profile-item">

                <label>NIK</label>

                <strong>{{ $manager->nik }}</strong>

            </div>

            <div class="profile-item">

                <label>Nama Lengkap</label>

                <strong>{{ $manager->user->name }}</strong>

            </div>

            <div class="profile-item">

                <label>Email</label>

                <strong>{{ $manager->user->email }}</strong>

            </div>

            <div class="profile-item">

                <label>Jenis Kelamin</label>

                <strong>{{ $manager->gender }}</strong>

            </div>

            <div class="profile-item">

                <label>Department</label>

                <strong>{{ $manager->department->name }}</strong>

            </div>

            <div class="profile-item">

                <label>Position</label>

                <strong>{{ $manager->position->name }}</strong>

            </div>

            <div class="profile-item">

                <label>Tanggal Bergabung</label>

                <strong>

                    {{ optional($manager->join_date)->format('d F Y') }}

                </strong>

            </div>

            <div class="profile-item">

                <label>Tanggal Lahir</label>

                <strong>

                    {{ optional($manager->birth_date)->format('d F Y') }}

                </strong>

            </div>

            <div class="profile-item">

                <label>Status</label>

                <span class="status-badge">

                    {{ $manager->status }}

                </span>

            </div>

        </div>

    </section>



    {{-- ========================================================= --}}
    {{-- ADDRESS --}}
    {{-- ========================================================= --}}

    <section class="profile-card">

        <div class="card-header">

            <div>

                <h2>

                    <i class="fa-solid fa-location-dot"></i>

                    Alamat

                </h2>

                <p>

                    Anda dapat memperbarui alamat apabila terdapat perubahan.

                </p>

            </div>

        </div>

        <div id="address-view">

            <div class="address-box">

                {{ $manager->address ?: 'Belum ada alamat.' }}

            </div>

            <button

                class="btn-edit"

                id="btnEditAddress"

                type="button">

                <i class="fa-solid fa-pen"></i>

                Edit Alamat

            </button>

        </div>



        <div id="address-form" style="display:none;">

            <form

                action="#"

                method="POST">

                @csrf

                @method('PATCH')

                <textarea

                    name="address"

                    rows="6"

                    class="address-textarea"

                    placeholder="Masukkan alamat lengkap...">{{ old('address',$manager->address) }}</textarea>

                @error('address')

                    <small class="text-danger">

                        {{ $message }}

                    </small>

                @enderror

                <div class="address-action">

                    <button

                        class="btn-save"

                        type="submit">

                        <i class="fa-solid fa-floppy-disk"></i>

                        Simpan

                    </button>

                    <button

                        class="btn-cancel"

                        type="button"

                        id="btnCancelAddress">

                        Batal

                    </button>

                </div>

            </form>

        </div>

    </section>

</div>

@endsection


@push('scripts')

<script>

const editButton = document.getElementById('btnEditAddress');

const cancelButton = document.getElementById('btnCancelAddress');

const addressView = document.getElementById('address-view');

const addressForm = document.getElementById('address-form');

if(editButton){

    editButton.addEventListener('click',function(){

        addressView.style.display='none';

        addressForm.style.display='block';

    });

}

if(cancelButton){

    cancelButton.addEventListener('click',function(){

        addressForm.style.display='none';

        addressView.style.display='block';

    });

}

</script>

@endpush