{{--
    Halaman baca Informasi Data Desa untuk Admin.
    Dipakai oleh route settings.data-desa.
    Variabel: $desa (?App\Models\Desa) record pertama berdasarkan id_desa atau null.
    Tombol Edit Data Desa sengaja hanya visual sesuai scope M16.
--}}
@extends('layouts.app')

@section('content')
<div id="data-desa">
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
            <button type="button" class="btn btn-primary app-touch-target app-data-desa-edit-button">
                <i class="bi bi-pencil-square me-2"></i>Edit Data Desa
            </button>
        </div>
    @else
        <div class="alert app-alert-info app-data-desa-alert d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle flex-shrink-0"></i>
            <span>Data desa belum tersedia. Silakan tambahkan data desa terlebih dahulu.</span>
        </div>
    @endif
</div>
@endsection
