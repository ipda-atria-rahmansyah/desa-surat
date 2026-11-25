{{--
    Form tambah pengguna untuk Admin.
    Dipakai oleh route settings.pengguna.create dan mengirim data form lama
    melalui old() serta pesan validasi dari StorePenggunaRequest.
--}}
@extends('layouts.app')

@section('content')
<div class="app-pengaturan-page app-pengguna-form-page">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h1 class="app-page-title fw-bold mb-1">Tambah Pengguna</h1>
            <p class="app-description mb-0">Silakan isi form berikut untuk menambahkan pengguna baru.</p>
        </div>
    </div>

    <div class="card app-card app-pengguna-form-card">
        <div class="app-pengguna-form-heading d-flex align-items-center gap-3">
            <i class="bi bi-person-fill-add" aria-hidden="true"></i>
            <h2 class="app-card-title fw-semibold mb-0">Informasi Pengguna</h2>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">Periksa kembali data yang dimasukkan.</div>
            @endif

            <form method="post" action="{{ route('settings.pengguna.store') }}" novalidate>
                @csrf
                <div class="app-pengguna-form-fields">
                    <div class="app-pengguna-form-row">
                        <label class="form-label" for="name"><i class="bi bi-person-fill me-2" aria-hidden="true"></i>Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group app-pengguna-input @error('name') is-invalid @enderror">
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap pengguna" maxlength="100" required>
                        </div>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="app-pengguna-form-row">
                        <label class="form-label" for="username"><i class="bi bi-at me-2" aria-hidden="true"></i>Username <span class="text-danger">*</span></label>
                        <div class="input-group app-pengguna-input @error('username') is-invalid @enderror">
                            <input id="username" name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username" maxlength="50" autocomplete="username" required>
                        </div>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="app-pengguna-form-row">
                        <label class="form-label" for="role"><i class="bi bi-shield-fill-check me-2" aria-hidden="true"></i>Role <span class="text-danger">*</span></label>
                        <div class="input-group app-pengguna-input @error('role') is-invalid @enderror">
                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Pilih role pengguna</option>
                                <option value="admin" @selected(old('role') === 'admin')>Administrator</option>
                                <option value="operator" @selected(old('role') === 'operator')>Operator</option>
                            </select>
                        </div>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="app-pengguna-form-row app-pengguna-status-row">
                        <label class="form-label" for="is_active">Status <span class="text-danger">*</span></label>
                        <div class="app-pengguna-switch">
                            <input id="is_active" name="is_active" value="1" class="visually-hidden" type="checkbox" @checked(old('is_active', true))>
                            <label class="app-pengguna-switch-control" for="is_active"><span class="app-pengguna-switch-track" aria-hidden="true"><span class="app-pengguna-switch-knob"></span></span><span class="app-pengguna-switch-label">Aktif</span></label>
                        </div>
                    </div>
                    <div class="app-pengguna-form-row">
                        <label class="form-label" for="password"><i class="bi bi-lock-fill me-2" aria-hidden="true"></i>Password <span class="text-danger">*</span></label>
                        <div class="input-group app-pengguna-input @error('password') is-invalid @enderror">
                            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" minlength="8" autocomplete="new-password" required>
                            <button class="btn app-pengguna-password-toggle" type="button" data-password-toggle="password" aria-label="Tampilkan password"><i class="bi bi-eye-slash-fill"></i></button>
                        </div>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="app-pengguna-form-row">
                        <label class="form-label" for="password_confirmation"><i class="bi bi-lock-fill me-2" aria-hidden="true"></i>Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-group app-pengguna-input">
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Konfirmasi password" minlength="8" autocomplete="new-password" required>
                            <button class="btn app-pengguna-password-toggle" type="button" data-password-toggle="password_confirmation" aria-label="Tampilkan password"><i class="bi bi-eye-slash-fill"></i></button>
                        </div>
                    </div>
                </div>
                <div class="alert app-pengguna-password-note d-flex align-items-center gap-3 mb-0" role="note">
                    <i class="bi bi-info-circle" aria-hidden="true"></i>
                    <span>Password minimal 8 karakter dan harus mengandung huruf dan angka.</span>
                </div>
                <div class="app-pengguna-form-actions d-flex flex-column flex-sm-row justify-content-between gap-3">
                    <a class="btn app-pengguna-back-button d-flex align-items-center" href="{{ route('settings.pengguna') }}"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                    <button class="btn app-pengguna-submit-button" type="submit"><i class="bi bi-person-plus-fill me-2"></i>Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
