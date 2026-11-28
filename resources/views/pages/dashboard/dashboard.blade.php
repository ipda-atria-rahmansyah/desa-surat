{{--
    Dashboard server-rendered untuk Admin dan Operator.
    Dipakai oleh route dashboard; variabel berasal dari DashboardController:
    $operatorName, $todayLabel, $isAdmin, $statistics, $letters, $categories,
    $categoryTotal, dan $activities.
--}}
@extends('layouts.app')

@section('content')
<div class="d-flex flex-column gap-3">
    <div class="row align-items-center g-3">
        <div class="col-md">
            <h1 class="app-page-title fs-3 fw-bold mb-1">Dashboard</h1>
            <div class="app-welcome fs-5 mb-1">Selamat datang, <span class="app-welcome-highlight fw-bold">{{ $operatorName }}!</span></div>
            <div class="app-description small">Berikut adalah ringkasan data sistem surat menyurat desa.</div>
        </div>
        <div class="col-12 col-md-auto"><div class="app-date-chip d-flex align-items-center gap-2"><i class="bi bi-calendar3"></i><span>{{ $todayLabel }}</span></div></div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
        @foreach ($statistics as $statistic)
            <div class="col"><x-stat-card :label="$statistic['label']" :value="$statistic['value']" :note="$statistic['note']" :icon="$statistic['icon']" :tone="$statistic['tone']" /></div>
        @endforeach
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card app-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="app-card-title fw-semibold mb-0">Surat Terbaru</h2>
                        <a href="{{ route('arsip.index') }}" class="app-link d-flex align-items-center gap-1">Lihat Semua <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="table-responsive">
                            <table class="table app-table align-middle small">
                            <thead><tr><th>No. Surat</th><th>Jenis Surat</th><th>Nama Warga</th><th>Operator</th><th>Tanggal</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse ($letters as $letter)
                                    @if (is_object($letter))
                                        <tr><td>{{ $letter->nomor_surat }}</td><td>{{ $letter->jenisSurat?->nama_jenis ?? 'Jenis surat tidak tersedia' }}</td><td>{{ $letter->warga?->nama_lengkap ?? 'Warga tidak tersedia' }}</td><td>{{ $letter->pembuat?->name ?? 'Pengguna tidak tersedia' }}</td><td>{{ $letter->tanggal_surat->translatedFormat('d M Y') }}</td><td><x-status-badge :status="$letter->status" /></td></tr>
                                    @else
                                        <tr><td>{{ $letter['number'] }}</td><td>{{ $letter['type'] }}</td><td>{{ $letter['resident'] }}</td><td>-</td><td>{{ $letter['date'] }}</td><td><x-status-badge :status="$letter['status']" /></td></tr>
                                    @endif
                                @empty
                                    <tr><td colspan="6" class="text-center text-body-secondary py-4">Belum ada surat terbaru.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card app-card h-100">
                <div class="card-body">
                    <h2 class="app-card-title fw-semibold mb-3">Grafik Surat per Kategori</h2>
                    <div class="app-chart gap-2">
                            <div class="app-donut {{ $categoryTotal === 0 ? 'app-donut--empty' : '' }} d-flex align-items-center justify-content-center" role="img" aria-label="Grafik surat per kategori">
                            <div class="app-donut-hole d-flex flex-column align-items-center justify-content-center"><span class="app-chart-total fw-bold">{{ $categoryTotal }}</span><span class="app-chart-caption small">Total Surat</span></div>
                        </div>
                        <div class="app-legend d-flex flex-column gap-2">
                            @foreach ($categories as $category)
                                <div class="d-flex align-items-center gap-2"><span class="app-legend-swatch app-legend-swatch--{{ $category['tone'] }}"></span><span>{{ $category['name'] }}</span><span class="ms-auto">{{ $category['value'] }}</span></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($isAdmin)
        <div class="card app-card">
            <div class="card-body">
                <h2 class="app-card-title fw-semibold mb-3">Aktivitas Pengguna Terbaru</h2>
                <div class="table-responsive">
                    <table class="table app-table align-middle small">
                        <thead><tr><th>Pengguna</th><th>Role</th><th>Aktivitas</th><th>Modul</th><th>Keterangan</th><th>Waktu</th></tr></thead>
                        <tbody>
                            @forelse ($activities as $activity)
                                <tr>
                                    <td>{{ $activity->user?->name ?? 'Sistem' }}</td>
                                    <td>{{ $activity->user?->role ?? '-' }}</td>
                                    <td>{{ $activity->activity }}</td>
                                    <td>{{ $activity->module }}</td>
                                    <td>{{ $activity->description ?? '-' }}</td>
                                    <td>{{ $activity->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-body-secondary py-4">Belum ada aktivitas pengguna.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection