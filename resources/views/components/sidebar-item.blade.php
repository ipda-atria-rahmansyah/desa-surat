@props(['label', 'icon', 'active' => false])

<a href="#" @class(['app-nav-link d-flex align-items-center gap-2 text-decoration-none', 'active' => $active])>
    <i class="bi {{ $icon }}"></i><span>{{ $label }}</span>
</a>