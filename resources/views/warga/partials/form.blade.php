<form method="POST" action="{{ route('warga.store') }}" class="app-warga-form" data-warga-form novalidate>
    @csrf
    <div class="card-body">
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <label for="nik" class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                <div class="input-group app-warga-form-input @error('nik') is-invalid @enderror">
                    <span class="input-group-text"><i class="bi bi-person-vcard-fill"></i></span>
                    <input id="nik" name="nik" type="text" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="Masukkan NIK warga" inputmode="numeric" maxlength="16" pattern="\d{16}" autocomplete="off" required>
                </div>
                @error('nik')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 col-lg-6">
                <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <div class="input-group app-warga-form-input @error('nama_lengkap') is-invalid @enderror">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input id="nama_lengkap" name="nama_lengkap" type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap warga" maxlength="100" required>
                </div>
                @error('nama_lengkap')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="alamat" class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                <div class="input-group app-warga-form-input app-warga-form-textarea @error('alamat') is-invalid @enderror">
                    <span class="input-group-text align-items-start"><i class="bi bi-house-fill"></i></span>
                    <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat lengkap warga" maxlength="255" rows="3" required>{{ old('alamat') }}</textarea>
                </div>
                @error('alamat')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <x-info-alert><span><strong>Informasi</strong><br>Pastikan data yang Anda masukkan sudah benar sebelum menyimpan.</span></x-info-alert>
            </div>
        </div>
    </div>

    <div class="app-warga-form-actions d-flex flex-column flex-sm-row justify-content-end gap-3">
        <a href="{{ route('warga.index') }}" class="btn app-warga-form-cancel d-inline-flex align-items-center justify-content-center gap-2"><i class="bi bi-arrow-left"></i><span>Batal</span></a>
        <button type="submit" class="btn app-warga-form-submit d-inline-flex align-items-center justify-content-center gap-2" data-submit-warga><i class="bi bi-floppy-fill"></i><span data-submit-label>Simpan Data</span></button>
    </div>
</form>