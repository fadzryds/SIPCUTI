@extends('hrd.layouts.app')

@section('title', 'Tambah Position')

@section('content')

<div class="hrd-page">

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon">
                <i class="fas fa-briefcase"></i>
            </div>

            <div>

                <span class="page-header-label">
                    MASTER DATA
                </span>

                <h1>
                    Tambah Position
                </h1>

                <p>
                    Tambahkan position baru ke dalam sistem.
                </p>

            </div>

        </div>

        <div class="page-header-actions">

            <a
                href="{{ route('hrd.positions.index') }}"
                class="btn-back"
            >
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

        </div>

    </div>


    @if($errors->any())

        <div class="alert alert-error">

            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <div class="alert-content">

                <strong>
                    Terdapat kesalahan
                </strong>

                <span>
                    Periksa kembali data yang Anda masukkan.
                </span>

            </div>

            <button
                type="button"
                class="alert-close"
                onclick="this.parentElement.remove()"
            >
                &times;
            </button>

        </div>

    @endif


    <form
        action="{{ route('hrd.positions.store') }}"
        method="POST"
        class="position-form"
    >

        @csrf


        <div class="position-form-card">

            <div class="form-card-header">

                <div class="form-card-icon">
                    <i class="fas fa-briefcase"></i>
                </div>

                <div>

                    <h2>
                        Informasi Position
                    </h2>

                    <p>
                        Masukkan informasi position secara lengkap.
                    </p>

                </div>

            </div>


            <div class="position-form-body">


                <div class="form-grid">


                    {{-- CODE --}}

                    <div class="form-group">

                        <label for="code">
                            Kode Position
                            <span>*</span>
                        </label>

                        <div class="input-wrapper">

                            <i class="fas fa-hashtag"></i>

                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
                                placeholder="Contoh: MGR"
                                maxlength="50"
                                required
                            >

                        </div>

                        <small>
                            Gunakan kode unik untuk position.
                        </small>

                        @error('code')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- NAME --}}

                    <div class="form-group">

                        <label for="name">
                            Nama Position
                            <span>*</span>
                        </label>

                        <div class="input-wrapper">

                            <i class="fas fa-briefcase"></i>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Manager"
                                maxlength="150"
                                required
                            >

                        </div>

                        <small>
                            Masukkan nama position.
                        </small>

                        @error('name')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- DESCRIPTION --}}

                    <div class="form-group full">

                        <label for="description">
                            Deskripsi
                        </label>

                        <div class="textarea-wrapper">

                            <i class="fas fa-align-left"></i>

                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                maxlength="1000"
                                placeholder="Masukkan deskripsi position..."
                            >{{ old('description') }}</textarea>

                        </div>

                        <small>
                            Deskripsi bersifat opsional.
                        </small>

                        @error('description')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- STATUS --}}

                    <div class="form-group full">

                        <label>
                            Status Position
                            <span>*</span>
                        </label>

                        <div class="status-options">

                            <label class="status-option">

                                <input
                                    type="radio"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                >

                                <span class="status-option-content active">

                                    <span class="status-option-icon">
                                        <i class="fas fa-circle-check"></i>
                                    </span>

                                    <span>

                                        <strong>
                                            Active
                                        </strong>

                                        <small>
                                            Position dapat digunakan.
                                        </small>

                                    </span>

                                </span>

                            </label>


                            <label class="status-option">

                                <input
                                    type="radio"
                                    name="is_active"
                                    value="0"
                                    {{ old('is_active') === '0' ? 'checked' : '' }}
                                >

                                <span class="status-option-content inactive">

                                    <span class="status-option-icon">
                                        <i class="fas fa-circle-xmark"></i>
                                    </span>

                                    <span>

                                        <strong>
                                            Inactive
                                        </strong>

                                        <small>
                                            Position tidak digunakan.
                                        </small>

                                    </span>

                                </span>

                            </label>

                        </div>

                        @error('is_active')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            <div class="form-card-footer">

                <a
                    href="{{ route('hrd.positions.index') }}"
                    class="btn-form-cancel"
                >
                    <i class="fas fa-times"></i>
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-form-submit"
                >
                    <i class="fas fa-save"></i>
                    Simpan Position
                </button>

            </div>

        </div>

    </form>

</div>

@endsection