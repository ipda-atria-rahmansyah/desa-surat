{{--
    Partial form tambah pejabat penandatangan.
    Dipakai oleh: pages.pengaturan.pejabat-penandatangan.create; disiapkan agar
    dapat dipakai ulang pada form edit tanpa membuat route edit pada M18.
    Variabel: $statusAktifDefault (bool), old() dan $errors dari Form Request.
--}}
<div class="card-body">
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">Periksa kembali data yang dimasukkan.</div>
    @endif

    <form method="POST" action="{{ route('settings.pejabat-penandatangan.store') }}" novalidate data-pejabat-form>
        @csrf
        <div class="app-pengguna-form-fields">
            <div class="app-pengguna-form-row">
                <label class="form-label" for="nama_pejabat"><i class="bi bi-person-fill me-2" aria-hidden="true"></i>Nama Pejabat <span class="text-danger">*</span></label>
                <div>
                    <div class="input-group app-pengguna-input @error('nama_pejabat') is-invalid @enderror">
                        <input id="nama_pejabat" name="nama_pejabat" type="text" class="form-control @error('nama_pejabat') is-invalid @enderror" value="{{ old('nama_pejabat') }}" placeholder="Masukkan nama pejabat" maxlength="150" required>
                    </div>
                    @error('nama_pejabat')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="app-pengguna-form-row">
                <label class="form-label" for="jabatan"><i class="bi bi-briefcase-fill me-2" aria-hidden="true"></i>Jabatan <span class="text-danger">*</span></label>
                <div>
                    <div class="input-group app-pengguna-input @error('jabatan') is-invalid @enderror">
                        <input id="jabatan" name="jabatan" type="text" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" placeholder="Masukkan jabatan" maxlength="100" required>
                    </div>
                    @error('jabatan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="app-pengguna-form-row">
                <label class="form-label" for="nip"><i class="bi bi-card-text me-2" aria-hidden="true"></i>NIP</label>
                <div>
                    <div class="input-group app-pengguna-input @error('nip') is-invalid @enderror">
                        <input id="nip" name="nip" type="text" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" placeholder="Masukkan NIP" inputmode="numeric" maxlength="30" autocomplete="off">
                    </div>
                    @error('nip')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="app-pengguna-form-row">
                <label class="form-label" for="pangkat"><i class="bi bi-award-fill me-2" aria-hidden="true"></i>Pangkat</label>
                <div>
                    <div class="input-group app-pengguna-input @error('pangkat') is-invalid @enderror">
                        <input id="pangkat" name="pangkat" type="text" class="form-control @error('pangkat') is-invalid @enderror" value="{{ old('pangkat') }}" placeholder="Masukkan pangkat" maxlength="100">
                    </div>
                    @error('pangkat')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="app-pengguna-form-row app-pengguna-status-row">
                <label class="form-label" for="status_aktif">Status Aktif</label>
                <div class="app-pengguna-switch">
                    <input type="hidden" name="status_aktif" value="0">
                    <input id="status_aktif" name="status_aktif" value="1" class="visually-hidden" type="checkbox" @checked(old('status_aktif', $statusAktifDefault))>
                    <label class="app-pengguna-switch-control" for="status_aktif"><span class="app-pengguna-switch-track" aria-hidden="true"><span class="app-pengguna-switch-knob"></span></span><span class="app-pengguna-switch-label">Aktif</span></label>
                </div>
                @error('status_aktif')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="app-pengguna-form-actions d-flex flex-column flex-sm-row justify-content-between gap-3">
            <a class="btn app-pengguna-back-button d-flex align-items-center justify-content-center" href="{{ route('settings.pejabat-penandatangan') }}"><i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Batal</a>
            <button class="btn app-pengguna-submit-button" type="submit" data-submit-pejabat><i class="bi bi-floppy-fill me-2" aria-hidden="true"></i><span data-submit-label>Simpan Data</span></button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('[data-pejabat-form]');
        const nip = form?.querySelector('[name="nip"]');
        const submit = form?.querySelector('[data-submit-pejabat]');

        if (!form) return;

        nip?.addEventListener('input', () => {
            nip.value = nip.value.replace(/\D/g, '').slice(0, 30);
        });

        form.addEventListener('submit', () => {
            if (!submit) return;
            submit.disabled = true;
            submit.querySelector('[data-submit-label]').textContent = 'Menyimpan...';
        }, { once: true });
    });
</script>
@endpush