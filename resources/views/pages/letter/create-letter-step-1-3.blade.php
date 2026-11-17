@extends('layouts.app')

@section('content')
<div class="app-create-letter" x-data="letterForm()">
    <div class="mb-3">
        <h1 class="app-page-title fw-bold mb-1">Buat Surat</h1>
        <p class="app-description mb-0">Silakan pilih kategori dan jenis surat, kemudian cari data warga berdasarkan NIK.</p>
    </div>

    <x-letter-stepper :current="1" />

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card app-card h-100">
                <div class="card-body d-flex flex-column gap-3">
                    <h2 class="app-card-title fw-semibold mb-0">1. Pilih Kategori Surat</h2>
                    <div class="app-category-list d-flex flex-column gap-2">
                        @foreach ([['Surat Kematian', 'Surat keterangan terkait warga yang meninggal dunia.', 'bi-file-earmark-medical'], ['Surat Kelahiran', 'Surat keterangan terkait kelahiran warga.', 'bi-file-earmark-plus'], ['Surat Keterangan Umum', 'Surat keterangan umum sesuai kebutuhan masyarakat.', 'bi-file-earmark-text'], ['Surat Pindah', 'Surat keterangan untuk keperluan perpindahan warga.', 'bi-arrow-left-right']] as $category)
                            <button type="button" class="app-category-option d-flex align-items-center gap-3 text-start" :class="{ 'selected': category === @js($category[0]) }" @click="category = @js($category[0])">
                                <span class="app-category-icon d-flex align-items-center justify-content-center"><i class="bi {{ $category[2] }}"></i></span>
                                <span><strong class="d-block">{{ $category[0] }}</strong><small>{{ $category[1] }}</small></span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7 d-flex flex-column gap-3">
            <div class="card app-card">
                <div class="card-body d-flex flex-column gap-3">
                    <h2 class="app-card-title fw-semibold mb-1">2. Pilih Jenis Surat</h2>
                    <div>
                        <label for="letter-type" class="form-label app-field-label">Jenis Surat</label>
                        <select id="letter-type" class="form-select app-touch-target" x-model="letterType"><option>Surat Keterangan Domisili</option></select>
                    </div>
                </div>
            </div>
            <div class="card app-card">
                <div class="card-body d-flex flex-column gap-3">
                    <h2 class="app-card-title fw-semibold mb-1">3. Cari Warga dengan NIK</h2>
                    <div>
                        <label for="resident-nik" class="form-label app-field-label">NIK</label>
                        <div class="row g-2">
                            <div class="col-sm"><input id="resident-nik" class="form-control app-touch-target" placeholder="Masukkan 16 digit NIK" x-model="nik"></div>
                            <div class="col-12 col-sm-auto"><button type="button" class="btn btn-primary app-touch-target w-100" @click="searchNik()"><i class="bi bi-search me-1"></i>Cari Warga</button></div>
                        </div>
                    </div>
                    <template x-if="searchMessage"><div class="app-search-message" x-text="searchMessage"></div></template>
                    <x-info-alert>Pastikan NIK yang dimasukkan sudah benar. Data warga akan diambil dari database desa.</x-info-alert>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary app-touch-target" :disabled="!canContinue">Selanjutnya →</button>
            </div>
        </div>
    </div>
</div>
@endsection