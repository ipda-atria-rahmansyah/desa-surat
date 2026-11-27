@extends('layouts.app')

@section('content')
<div class="app-create-letter">
    <div class="mb-3">
        <h1 class="app-page-title fw-bold mb-1">Buat Surat</h1>
        <p class="app-description mb-0">Silakan pilih kategori dan jenis surat, kemudian cari data warga berdasarkan NIK.</p>
    </div>

    <x-letter-stepper :current="5" />

    <div class="card app-card app-success-card">
        <div class="card-body d-flex flex-column align-items-center text-center">
            <div class="app-success-illustration" aria-hidden="true">
                <span class="app-success-blob app-success-blob--one"></span>
                <span class="app-success-blob app-success-blob--two"></span>
                <span class="app-success-paper"><i class="bi bi-file-earmark-text"></i><b><i class="bi bi-check-lg"></i></b></span>
                <span class="app-success-rays app-success-rays--one"></span>
                <span class="app-success-rays app-success-rays--two"></span>
                <span class="app-success-rays app-success-rays--three"></span>
            </div>
            <h2 class="app-success-title fw-bold mb-1">Surat Berhasil Dibuat!</h2>
            <p class="app-success-description mb-4">Surat Anda telah berhasil dibuat dan siap digunakan.</p>

            <div class="app-success-details d-flex align-items-center text-start w-100 mb-4">
                <i class="bi bi-info-circle app-success-details-icon"></i>
                <div class="app-success-detail-list">
                    <div><span>Nomor Surat</span><b>:</b><strong>140/SDM/2025</strong></div>
                    <div><span>Tanggal Surat</span><b>:</b><strong>28 Oktober 2025</strong></div>
                </div>
            </div>

            <a href="{{ route('create-letter-step-1-to-3') }}" class="btn btn-primary app-success-action app-touch-target">
                <i class="bi bi-file-earmark-plus"></i><span>Buat Surat Lagi</span><i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endsection
