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

    <div class="app-warga-search input-group mb-3">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="search" class="form-control" placeholder="Cari nama, NIK, atau alamat..." aria-label="Cari nama, NIK, atau alamat">
    </div>

    <div class="card app-card app-warga-card">
        <div class="table-responsive">
            <table class="table app-warga-table align-middle mb-0">
                <thead>
                    <tr><th scope="col">No</th><th scope="col">NIK</th><th scope="col">Nama Lengkap</th><th scope="col">Alamat</th><th scope="col" class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($warga as $item)
                        <tr>
                            <td>{{ $item['no'] }}</td><td>{{ $item['nik'] }}</td><td>{{ $item['nama'] }}</td><td>{{ $item['alamat'] }}</td>
                            <td><div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn app-warga-action app-warga-action--view" aria-label="Lihat {{ $item['nama'] }}"><i class="bi bi-eye-fill"></i></a>
                                <a href="#" class="btn app-warga-action app-warga-action--edit" aria-label="Ubah {{ $item['nama'] }}"><i class="bi bi-pencil-fill"></i></a>
                                <a href="#" class="btn app-warga-action app-warga-action--delete" aria-label="Hapus {{ $item['nama'] }}"><i class="bi bi-trash-fill"></i></a>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="app-warga-footer d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <span>Menampilkan 1–{{ count($warga) }} dari {{ count($warga) }} data</span>
            <nav aria-label="Pagination data warga"><ul class="pagination mb-0 gap-2">
                <li class="page-item"><a class="page-link" href="#" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></a></li>
                <li class="page-item active"><a class="page-link" href="#" aria-current="page">1</a></li>
                <li class="page-item"><a class="page-link" href="#" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></a></li>
            </ul></nav>
        </div>
    </div>
</div>
@endsection