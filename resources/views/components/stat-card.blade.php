@props(['label', 'value', 'note', 'icon', 'tone'])

<div class="card app-card h-100">
    <div class="card-body d-flex align-items-center gap-3">
        <span class="app-stat-icon app-stat-icon--{{ $tone }} d-flex align-items-center justify-content-center"><i class="bi {{ $icon }}"></i></span>
        <div class="min-w-0">
            <div class="app-stat-label">{{ $label }}</div>
            <div class="app-stat-value fw-bold">{{ $value }}</div>
            <div class="app-stat-note">{{ $note }}</div>
        </div>
    </div>
</div>