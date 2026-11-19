@extends('layouts.app')

@section('content')
<div class="card app-card">
    <div class="card-body text-center py-5">
        <i class="bi bi-shield-lock display-4 text-success"></i>
        <h1 class="app-page-title fw-bold mt-3">Akses Ditolak</h1>
        <p class="app-description">Anda tidak memiliki hak untuk mengakses halaman ini.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-success">Kembali ke Dashboard</a>
    </div>
</div>
@endsection