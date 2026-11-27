@extends('layouts.app')

{{--
    Halaman daftar arsip surat.
    Dipakai oleh route arsip.index dan menerima $surat sebagai paginator
    App\Models\Surat dengan relasi warga dan jenisSurat yang sudah dimuat.
    Tombol aksi dibuat nonaktif karena route lihat, unduh, dan cetak belum ada.
--}}

@section('content')
<div class="app-arsip-page">
    <div class="mb-3">
        <h1 class="app-page-title fw-bold mb-1">Arsip Surat</h1>
        <p class="app-description mb-0">Lihat dan kelola arsip surat yang telah dibuat.</p>
    </div>

    <form class="row g-2 align-items-end mb-3" method="get" action="{{ route('arsip.index') }}" novalidate>
        <div class="col-12 col-lg-6">
            <label class="visually-hidden" for="arsip-search">Pencarian arsip</label>
            <div class="input-group app-arsip-search">
                <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                <input id="arsip-search" class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nomor surat, nama warga, atau jenis surat..." autocomplete="off">
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <label class="visually-hidden" for="tanggal-mulai">Tanggal mulai</label>
            <div class="input-group app-arsip-date">
                <span class="input-group-text"><i class="bi bi-calendar3" aria-hidden="true"></i></span>
                <input id="tanggal-mulai" class="form-control" type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" aria-label="Tanggal mulai">
            </div>
        </div>
        <div class="col-6 col-lg-2">
            <label class="visually-hidden" for="tanggal-selesai">Tanggal selesai</label>
            <div class="input-group app-arsip-date">
                <span class="input-group-text"><i class="bi bi-calendar3" aria-hidden="true"></i></span>
                <input id="tanggal-selesai" class="form-control" type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" aria-label="Tanggal selesai">
            </div>
        </div>
        <div class="col-12 col-lg-2">
            <button class="btn btn-primary app-arsip-filter app-touch-target w-100" type="submit"><i class="bi bi-funnel-fill me-1" aria-hidden="true"></i>Filter</button>
        </div>
    </form>

    @if ($errors->any())
        <div class="alert alert-danger app-arsip-alert" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card app-card app-arsip-card">
        <div class="table-responsive">
            <table class="table app-table app-arsip-table align-middle">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nomor Surat</th>
                        <th scope="col">Tanggal Surat</th>
                        <th scope="col">Nama Warga</th>
                        <th scope="col">Jenis Surat</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surat as $item)
                        <tr>
                            <td>{{ $surat->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $item->nomor_surat }}</td>
                            <td>{{ $item->tanggal_surat?->translatedFormat('d M Y') }}</td>
                            <td>{{ $item->warga?->nama_lengkap }}</td>
                            <td>{{ $item->jenisSurat?->nama_jenis }}</td>
                            <td class="text-center">
                                <div class="app-arsip-actions">
                                    <button class="btn app-arsip-action app-arsip-action--view" type="button" disabled aria-label="Lihat surat" title="Lihat surat"><i class="bi bi-eye-fill" aria-hidden="true"></i></button>
                                    <button class="btn app-arsip-action app-arsip-action--download" type="button" disabled aria-label="Unduh surat" title="Unduh surat"><i class="bi bi-download" aria-hidden="true"></i></button>
                                    <button class="btn app-arsip-action app-arsip-action--print" type="button" disabled aria-label="Cetak surat" title="Cetak surat"><i class="bi bi-printer-fill" aria-hidden="true"></i></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted py-4" colspan="6">Belum ada arsip surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="app-arsip-footer d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <span class="text-muted">Menampilkan {{ $surat->firstItem() ?? 0 }}–{{ $surat->lastItem() ?? 0 }} dari {{ $surat->total() }} data</span>
            {{ $surat->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection