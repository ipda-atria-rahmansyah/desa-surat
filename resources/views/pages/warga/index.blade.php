@extends('layouts.app')
@section('content')
<div class="app-warga-page">
    <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3 mb-4">
        <div>
            <h1 class="app-page-title fw-bold mb-2">Data Warga</h1>
            <p class="app-warga-description mb-0">Kelola data warga untuk kebutuhan administrasi surat-menyurat.</p>
        </div>
        <a href="#" class="btn app-warga-add-button d-inline-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-plus-lg"></i><span>Tambah Warga</span>
        </a>
    </div>

    <form class="app-warga-search input-group mb-3" method="GET" action="{{ route('warga.index') }}" data-warga-search-form>
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <label class="visually-hidden" for="warga-search">Cari nama atau NIK</label>
        <input id="warga-search" name="q" type="search" class="form-control" value="{{ request('q') }}" placeholder="Cari nama, NIK, atau alamat..." aria-label="Cari nama atau NIK" autocomplete="off">
    </form>

    <div class="card app-card app-warga-card" data-warga-results aria-live="polite">
        @include('pages.warga.partials.results')
    </div>
</div>
@endsection