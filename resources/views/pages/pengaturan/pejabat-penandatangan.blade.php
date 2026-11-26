{{--
    Halaman daftar pejabat penandatangan untuk route settings.pejabat-penandatangan.
    Variabel: $pejabat (LengthAwarePaginator), $totalPejabatAktif (int),
    $search (string), dan $selectedStatus (string). Kontrol CRUD hanya visual.
--}}
@extends('layouts.app')

@section('content')
<div id="pejabat-penandatangan" class="app-pengaturan-page">
    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div>
                <h1 class="app-page-title fw-bold mb-1">Penjabat Penandatangan</h1>
                <p class="app-description mb-0">Kelola data pejabat yang berwenang menandatangani surat.</p>
            </div>
        </div>
    </div>

    <div class="card app-card mb-4">
        <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center gap-4">
            <div class="app-stat-icon app-stat-icon--green d-flex align-items-center justify-content-center flex-shrink-0" aria-hidden="true">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="flex-grow-1">
                <h2 class="app-card-title fw-bold mb-2">Informasi</h2>
                <p class="mb-0 text-primary-emphasis">Penjabat penandatangan adalah pihak yang berwenang untuk menandatangani surat yang diterbitkan oleh desa.</p>
            </div>
            <div class="border-top border-lg-start border-lg-top-0 ps-lg-4 pt-3 pt-lg-0 d-flex align-items-center gap-3">
                <span class="app-stat-icon app-stat-icon--green d-flex align-items-center justify-content-center fs-4" aria-hidden="true"><i class="bi bi-check-circle-fill"></i></span>
                <div><div class="fs-4 fw-bold">{{ $totalPejabatAktif }}</div><div class="text-primary-emphasis">Pejabat Aktif</div></div>
            </div>
        </div>
    </div>

    <div class="card app-card mb-4">
        <div class="card-body">
            <form class="row g-2 align-items-center" method="get" action="{{ route('settings.pejabat-penandatangan') }}" novalidate>
                <div class="col-12 col-md">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search" aria-hidden="true"></i></span>
                        <input class="form-control @error('q') is-invalid @enderror" type="search" name="q" value="{{ $search }}" placeholder="Cari nama pejabat..." aria-label="Cari nama pejabat" maxlength="100" autocomplete="off">
                        @error('q')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select @error('status') is-invalid @enderror" name="status" aria-label="Filter status">
                        <option value="" @selected($selectedStatus === '')>Semua Status</option>
                        <option value="aktif" @selected($selectedStatus === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($selectedStatus === 'nonaktif')>Nonaktif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-auto d-grid">
                    <a class="btn btn-success" href="{{ route('settings.pejabat-penandatangan.create') }}"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Tambah Penjabat</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card app-card">
        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <thead><tr><th scope="col">No</th><th scope="col">Nama Pejabat</th><th scope="col">Jabatan</th><th scope="col">Status</th><th scope="col">Terakhir Diperbarui</th><th scope="col" class="text-center">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($pejabat as $item)
                        <tr>
                            <td>{{ $pejabat->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $item->nama_pejabat }}</td>
                            <td>{{ $item->jabatan }}</td>
                            <td><div class="d-flex align-items-center gap-2"><div class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" role="switch" @checked($item->status_aktif) disabled aria-label="Status {{ $item->nama_pejabat }}"></div><span class="badge rounded-pill {{ $item->status_aktif ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">{{ $item->status_aktif ? 'Aktif' : 'Nonaktif' }}</span></div></td>
                            <td>{{ $item->updated_at?->locale('id')->translatedFormat('d M Y H:i') }}</td>
                            <td class="text-center"><div class="d-inline-flex gap-2"><button class="btn btn-sm btn-outline-primary" type="button" disabled aria-disabled="true" aria-label="Ubah {{ $item->nama_pejabat }}"><i class="bi bi-pencil-fill" aria-hidden="true"></i></button><button class="btn btn-sm btn-outline-danger" type="button" disabled aria-disabled="true" aria-label="Hapus {{ $item->nama_pejabat }}"><i class="bi bi-trash-fill" aria-hidden="true"></i></button></div></td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="6">Data pejabat tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 p-3"><small class="text-body-secondary">Menampilkan {{ $pejabat->firstItem() ?? 0 }}–{{ $pejabat->lastItem() ?? 0 }} dari {{ $pejabat->total() }} data</small>@if ($pejabat->hasPages()){{ $pejabat->onEachSide(1)->links('pagination::bootstrap-5') }}@endif</div>
    </div>
</div>
@endsection
