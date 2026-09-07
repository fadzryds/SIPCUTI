@extends('hrd.layouts.app')

@section('title', 'Edit Position')

@section('content')

<div class="hrd-page">

    <div class="page-header">

        <div class="page-header-content">

            <div class="page-header-icon">
                <i class="fas fa-pen"></i>
            </div>

            <div>

                <span class="page-header-label">
                    MASTER DATA
                </span>

                <h1>
                    Edit Position
                </h1>

                <p>
                    Perbarui informasi position yang terdaftar.
                </p>

            </div>

        </div>

        <div class="page-header-actions">

            <a
                href="{{ route('hrd.positions.show', $position) }}"
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

        </div>

    @endif


    <form
        action="{{ route('hrd.positions.update', $position) }}"
        method="POST"
        class="position-form"
    >

        @csrf
        @method('PUT')


        <div class="position-form-card">

            <div class="form-card-header">

                <div class="form-card-icon">
                    <i class="fas fa-pen"></i>
                </div>

                <div>

                    <h2>
                        Edit Informasi Position
                    </h2>

                    <p>
                        Perbarui data position sesuai kebutuhan.
                    </p>

                </div>

            </div>


            <div class="position-form-body">

                <div class="form-grid">


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
                                value="{{ old('code', $position->code) }}"
                                maxlength="50"
                                required
                            >

                        </div>

                        @error('code')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


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
                                value="{{ old('name', $position->name) }}"
                                maxlength="150"
                                required
                            >

                        </div>

                        @error('name')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


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
                            >{{ old('description', $position->description) }}</textarea>

                        </div>

                        @error('description')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


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
                                    {{ old('is_active', $position->is_active) ? 'checked' : '' }}
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
                                    {{ old('is_active', $position->is_active) == 0 ? 'checked' : '' }}
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

                    </div>

                </div>

            </div>


            <div class="form-card-footer">

                <a
                    href="{{ route('hrd.positions.show', $position) }}"
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
                    Update Position
                </button>

            </div>

        </div>

    </form>

</div>

@endsection