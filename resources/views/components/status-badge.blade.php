@props(['status'])

<span @class(['badge app-status', 'app-status--done' => $status === 'Selesai', 'app-status--wait' => $status === 'Diproses'])>{{ $status }}</span>