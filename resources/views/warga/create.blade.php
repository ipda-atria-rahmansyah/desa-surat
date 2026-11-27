@extends('layouts.app')

@section('content')
<div class="app-warga-form-page">
    <div class="mb-4">
        <h1 class="app-page-title fw-bold mb-2">Tambah Data Warga</h1>
        <p class="app-warga-description mb-0">Silakan lengkapi data warga dengan informasi yang benar.</p>
    </div>

    <div class="card app-card app-warga-form-card">
        <div class="app-warga-form-heading d-flex align-items-center gap-3">
            <span class="app-warga-form-heading-icon d-flex align-items-center justify-content-center rounded-circle" aria-hidden="true"><i class="bi bi-person-fill"></i></span>
            <h2 class="app-card-title fw-semibold mb-0">Informasi Data Warga</h2>
        </div>
        @include('warga.partials.form')
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('[data-warga-form]');
        const nik = form?.querySelector('[name="nik"]');
        const submit = form?.querySelector('[data-submit-warga]');

        if (!form) return;

        nik?.addEventListener('input', () => {
            nik.value = nik.value.replace(/\D/g, '').slice(0, 16);
        });

        form.addEventListener('submit', () => {
            if (!submit) return;
            submit.disabled = true;
            submit.querySelector('[data-submit-label]').textContent = 'Menyimpan...';
        }, { once: true });
    });
</script>
@endpush