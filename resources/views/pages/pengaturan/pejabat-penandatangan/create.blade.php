{{--
    Halaman form tambah pejabat penandatangan untuk route
    settings.pejabat-penandatangan.create.
    Variabel: $statusAktifDefault (bool) untuk nilai awal switch status.
--}}
@extends('layouts.app')

@section('content')
<div class="app-pengaturan-page app-pengguna-form-page">
    <div class="mb-4">
        <h1 class="app-page-title fw-bold mb-1">Tambah Penjabat Penandatangan</h1>
        <p class="app-description mb-0">Silakan lengkapi data pejabat yang berwenang menandatangani surat.</p>
    </div>

    <div class="card app-card app-pengguna-form-card">
        <div class="app-pengguna-form-heading d-flex align-items-center gap-3">
            <i class="bi bi-person-badge-fill" aria-hidden="true"></i>
            <h2 class="app-card-title fw-semibold mb-0">Informasi Penjabat</h2>
        </div>
        @include('pages.pengaturan.pejabat-penandatangan.partials.form', ['statusAktifDefault' => $statusAktifDefault])
    </div>
</div>
@endsection