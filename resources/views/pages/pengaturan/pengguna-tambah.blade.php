{{--
    Form tambah pengguna untuk Admin.
    Dipakai oleh route settings.pengguna.create dan mengirim data form lama
    melalui old() serta pesan validasi dari StorePenggunaRequest.
--}}
@extends('layouts.app')

@section('content')
<div class="app-pengaturan-page">
    <div class="mb-4">
        <h1 class="app-page-title fw-bold mb-2">Tambah Pengguna</h1>
        <p class="app-description mb-0">Tambahkan akun baru yang dapat mengakses sistem.</p>
    </div>

    <div class="card app-card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">Periksa kembali data yang dimasukkan.</div>
            @endif

            <form method="post" action="{{ route('settings.pengguna.store') }}" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">Nama <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" maxlength="100" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                        <input id="username" name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" maxlength="50" autocomplete="username" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="role">Role <span class="text-danger">*</span></label>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            <option value="admin" @selected(old('role') === 'admin')>Administrator</option>
                            <option value="operator" @selected(old('role') === 'operator')>Operator</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6"></div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="password">Kata Sandi <span class="text-danger">*</span></label>
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" minlength="8" autocomplete="new-password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" minlength="8" autocomplete="new-password" required>
                    </div>
                </div>
                <div class="d-grid d-sm-flex justify-content-sm-end gap-2 mt-4">
                    <a class="btn btn-outline-secondary" href="{{ route('settings.pengguna') }}">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection