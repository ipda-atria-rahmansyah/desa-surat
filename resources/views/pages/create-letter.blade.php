@extends('layouts.app')

@section('content')
<div class="app-create-letter" x-data="letterForm()">
    <div class="mb-3">
        <h1 class="app-page-title fw-bold mb-1">Buat Surat</h1>
        <p class="app-description mb-0">Silakan pilih kategori dan jenis surat, kemudian cari data warga berdasarkan NIK.</p>
    </div>

    <x-letter-stepper />

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card app-card h-100">
                <div class="card-body d-flex flex-column gap-3">
                    <h2 class="app-card-title fw-semibold mb-0"><i class="bi bi-check-lg app-step-check me-1"></i>Data Warga</h2>
                    <div class="app-resident-summary d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-3">
                        <div class="app-resident-avatar d-flex align-items-center justify-content-center rounded-circle"><i class="bi bi-person-fill"></i></div>
                        <div class="app-resident-details flex-grow-1 w-100">
                            <div class="app-data-row"><span class="app-field-label">Nama</span><span>Budi Santoso</span></div>
                            <div class="app-data-row"><span class="app-field-label">NIK</span><span>3201010101010001</span></div>
                            <div class="app-data-row"><span class="app-field-label">Tempat/Tgl Lahir</span><span>Bandung, 10 Januari 1990</span></div>
                            <div class="app-data-row"><span class="app-field-label">Jenis Kelamin</span><span>Laki-laki</span></div>
                            <div class="app-data-row"><span class="app-field-label">Alamat</span><span>Jl. Melati No. 12 RT 003/RW 001<br>Desa Sukamaju, Kec. Sukamaju</span></div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary app-touch-target mt-auto" @click="step = 3">Ubah Data Warga</button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card app-card h-100">
                <div class="card-body d-flex flex-column gap-3">
                    <h2 class="app-card-title fw-semibold mb-1">Data Surat</h2>
                    <div>
                        <label for="letter-type" class="form-label app-field-label">Jenis Surat</label>
                        <select id="letter-type" class="form-select app-touch-target"><option>Surat Keterangan Domisili</option></select>
                    </div>
                    <div>
                        <label for="letter-number" class="form-label app-field-label">Nomor Surat</label>
                        <input id="letter-number" class="form-control app-touch-target" value="140/DS/2025">
                    </div>
                    <div>
                        <label for="letter-date" class="form-label app-field-label">Tanggal Surat</label>
                        <div class="input-group"><span class="input-group-text app-input-icon"><i class="bi bi-calendar3"></i></span><input id="letter-date" class="form-control app-touch-target" value="28/10/2025"></div>
                    </div>
                    <div>
                        <label for="letter-purpose" class="form-label app-field-label">Keperluan</label>
                        <textarea id="letter-purpose" class="form-control" rows="3">Untuk keperluan pendaftaran pekerjaan.</textarea>
                    </div>
                    <button type="button" class="btn btn-primary app-touch-target mt-auto" @click="step = 4">Simpan &amp; Lanjutkan <i class="bi bi-arrow-right ms-1"></i></button>
                </div>
            </div>
        </div>

        <div class="col-12"><x-info-alert>Data warga sudah terisi otomatis. Silakan lengkapi keperluan surat dan pastikan semua informasi sudah benar.</x-info-alert></div>
    </div>
</div>
@endsection