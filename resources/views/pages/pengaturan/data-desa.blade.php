{{--
    Halaman informasi dan modal edit Data Desa untuk Admin.
    Dipakai oleh route settings.data-desa; form modal mengirim ke
    route settings.data-desa.update.
    Variabel: $desa (?App\Models\Desa) record pertama berdasarkan id_desa atau null.
    Error form memakai error bag updateDesa.
--}}
@extends('layouts.app')

@section('content')
<div id="data-desa">
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="app-data-desa-page-heading d-flex align-items-center justify-content-between gap-3 mb-4">
       
        <div>
            <h1 class="app-page-title fw-bold mb-1">Data Desa</h1>
            <p class="app-description mb-0">Informasi detail desa yang terdaftar dalam sistem.</p>
        </div>
        <span class="app-data-desa-status d-inline-flex align-items-center gap-2 ms-auto"><i class="bi bi-shield-check"></i>Data resmi desa</span>
    </div>

    @if ($desa)
        <div class="card app-card app-data-desa-card overflow-hidden mb-3">
            <div class="card-header app-data-desa-card-header d-flex align-items-center gap-2 border-0">
                <i class="bi bi-house-door-fill"></i>
                <h2 class="app-card-title fs-5 fw-bold mb-0">Informasi Desa</h2>
            </div>
            <div class="card-body p-3 p-lg-4">
                <div class="row g-4 align-items-stretch">
                    <div class="col-12 col-lg-5 d-flex border rounded">
                        <div class="app-data-desa-logo rounded-3 text-success-emphasis d-flex flex-column align-items-center justify-content-center text-center p-4 w-100">
                            @if ($desa->logo)
                                <img src="{{ asset('storage/'.$desa->logo) }}" alt="Logo {{ $desa->nama_desa }}" class="app-data-desa-logo-image rounded-3">
                            @else
                                <span class="app-data-desa-logo-mark d-inline-flex align-items-center justify-content-center rounded-circle mb-3"><i class="bi bi-building"></i></span>
                                <strong class="app-data-desa-logo-title">Logo desa belum tersedia</strong>
                                <small class="app-data-desa-logo-caption">Tambahkan logo melalui pengaturan data desa</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-lg-7 d-flex">
                        <div class="app-data-desa-details-wrap w-100">
                            <h3 class="app-data-desa-details-title fw-bold mb-2">Detail Desa</h3>
                            <dl class="app-data-desa-details mb-0">
                            <div class="row g-2 py-2 border-bottom">
                                <dt class="col-12 col-sm-5 mb-0 d-flex align-items-center gap-2"><span class="app-data-desa-detail-icon"><i class="bi bi-bank"></i></span><span>Nama Desa</span></dt>
                                <dd class="col-12 col-sm-7 mb-0 fw-semibold">{{ $desa->nama_desa }}</dd>
                            </div>
                            <div class="row g-2 py-2 border-bottom">
                                <dt class="col-12 col-sm-5 mb-0 d-flex align-items-center gap-2"><span class="app-data-desa-detail-icon"><i class="bi bi-geo-alt-fill"></i></span><span>Alamat Desa</span></dt>
                                <dd class="col-12 col-sm-7 mb-0">{{ $desa->alamat_kantor ?: 'Belum tersedia' }}</dd>
                            </div>
                            <div class="row g-2 py-2 border-bottom">
                                <dt class="col-12 col-sm-5 mb-0 d-flex align-items-center gap-2"><span class="app-data-desa-detail-icon"><i class="bi bi-map"></i></span><span>Kecamatan</span></dt>
                                <dd class="col-12 col-sm-7 mb-0">{{ $desa->kecamatan ?: 'Belum tersedia' }}</dd>
                            </div>
                            <div class="row g-2 py-2 border-bottom">
                                <dt class="col-12 col-sm-5 mb-0 d-flex align-items-center gap-2"><span class="app-data-desa-detail-icon"><i class="bi bi-map-fill"></i></span><span>Kabupaten</span></dt>
                                <dd class="col-12 col-sm-7 mb-0">{{ $desa->kabupaten ?: 'Belum tersedia' }}</dd>
                            </div>
                            <div class="row g-2 py-2 border-bottom">
                                <dt class="col-12 col-sm-5 mb-0 d-flex align-items-center gap-2"><span class="app-data-desa-detail-icon"><i class="bi bi-mailbox"></i></span><span>Kode Pos</span></dt>
                                <dd class="col-12 col-sm-7 mb-0">{{ $desa->kode_pos ?: 'Belum tersedia' }}</dd>
                            </div>
                            <div class="row g-2 py-2">
                                <dt class="col-12 col-sm-5 mb-0 d-flex align-items-center gap-2"><span class="app-data-desa-detail-icon"><i class="bi bi-calendar2-check"></i></span><span>Terakhir Diperbarui</span></dt>
                                <dd class="col-12 col-sm-7 mb-0">{{ $desa->updated_at?->locale('id')->translatedFormat('d M Y H:i') ?: 'Belum tersedia' }}</dd>
                            </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary app-touch-target app-data-desa-edit-button" data-bs-toggle="modal" data-bs-target="#editDataDesaModal">
                <i class="bi bi-pencil-square me-2"></i>Edit Data Desa
            </button>
        </div>

        <div class="modal fade" id="editDataDesaModal" tabindex="-1" aria-labelledby="editDataDesaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5 fw-bold" id="editDataDesaModalLabel">Edit Data Desa</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <form method="POST" action="{{ route('settings.data-desa.update') }}" enctype="multipart/form-data" novalidate data-update-desa-form>
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="nama_desa" class="form-label fw-semibold">Nama Desa <span class="text-danger">*</span></label>
                                    <input id="nama_desa" name="nama_desa" type="text" class="form-control @error('nama_desa', 'updateDesa') is-invalid @enderror" value="{{ old('nama_desa', $desa->nama_desa) }}" maxlength="150" required>
                                    @error('nama_desa', 'updateDesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="kecamatan" class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                                    <input id="kecamatan" name="kecamatan" type="text" class="form-control @error('kecamatan', 'updateDesa') is-invalid @enderror" value="{{ old('kecamatan', $desa->kecamatan) }}" maxlength="100" required>
                                    @error('kecamatan', 'updateDesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="kabupaten" class="form-label fw-semibold">Kabupaten <span class="text-danger">*</span></label>
                                    <input id="kabupaten" name="kabupaten" type="text" class="form-control @error('kabupaten', 'updateDesa') is-invalid @enderror" value="{{ old('kabupaten', $desa->kabupaten) }}" maxlength="100" required>
                                    @error('kabupaten', 'updateDesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="provinsi" class="form-label fw-semibold">Provinsi <span class="text-danger">*</span></label>
                                    <input id="provinsi" name="provinsi" type="text" class="form-control @error('provinsi', 'updateDesa') is-invalid @enderror" value="{{ old('provinsi', $desa->provinsi) }}" maxlength="100" required>
                                    @error('provinsi', 'updateDesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="alamat_kantor" class="form-label fw-semibold">Alamat Kantor</label>
                                    <textarea id="alamat_kantor" name="alamat_kantor" class="form-control @error('alamat_kantor', 'updateDesa') is-invalid @enderror" rows="3">{{ old('alamat_kantor', $desa->alamat_kantor) }}</textarea>
                                    @error('alamat_kantor', 'updateDesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="kode_pos" class="form-label fw-semibold">Kode Pos</label>
                                    <input id="kode_pos" name="kode_pos" type="text" class="form-control @error('kode_pos', 'updateDesa') is-invalid @enderror" value="{{ old('kode_pos', $desa->kode_pos) }}" inputmode="numeric" maxlength="10" pattern="\d{1,10}">
                                    @error('kode_pos', 'updateDesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="logo" class="form-label fw-semibold">Logo</label>
                                    <input id="logo" name="logo" type="file" class="form-control @error('logo', 'updateDesa') is-invalid @enderror" accept="image/png,image/jpeg">
                                    @error('logo', 'updateDesa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" data-submit-update-desa>
                                <i class="bi bi-check-lg me-2"></i><span data-submit-label>Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="alert app-alert-info app-data-desa-alert d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle flex-shrink-0"></i>
            <span>Data desa belum tersedia. Silakan tambahkan data desa terlebih dahulu.</span>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    /**
     * Membuka kembali modal Data Desa setelah validasi gagal dan mencegah
     * klik ganda yang dapat mengirim form perubahan lebih dari satu kali.
     */
    document.addEventListener('DOMContentLoaded', () => {
        const modalElement = document.getElementById('editDataDesaModal');
        const form = document.querySelector('[data-update-desa-form]');
        const submit = form?.querySelector('[data-submit-update-desa]');

        if (!modalElement || !form || !submit) return;

        if ({{ $errors->updateDesa->isNotEmpty() ? 'true' : 'false' }}) {
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }

        form.addEventListener('submit', () => {
            submit.disabled = true;
            submit.querySelector('[data-submit-label]').textContent = 'Menyimpan...';
        }, { once: true });
    });
</script>
@endpush
