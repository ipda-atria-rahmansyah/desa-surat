{{--
    Badge status surat pada tabel dashboard.
    Dipakai oleh dashboard; menerima $status string dari database dengan nilai
    draft, selesai, atau dibatalkan.
--}}
@props(['status'])

@php
    $label = match ($status) {
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        default => 'Diproses',
    };
@endphp

<span @class(['badge app-status', 'app-status--done' => $status === 'selesai', 'app-status--wait' => $status === 'draft', 'bg-danger-subtle text-danger-emphasis' => $status === 'dibatalkan'])>{{ $label }}</span>