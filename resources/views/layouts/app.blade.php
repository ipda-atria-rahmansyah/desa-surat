<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Surat Menyurat Desa Sukamaju</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @php
        $isAdmin = session('role', 'Admin') === 'Admin';
        $settingsActive = request()->routeIs('settings.*');
        $userName = session('user_name', 'Operator');
        $userRole = session('role', 'Admin');
        $pageMeta = match (true) {
            request()->routeIs('dashboard') => ['Dashboard', 'Beranda'],
            request()->routeIs('create-letter*') => ['Buat Surat', 'Beranda / Persuratan'],
            request()->routeIs('settings.*') => ['Pengaturan', 'Beranda / Pengaturan'],
            default => ['Dashboard', 'Beranda'],
        };
        $notifications = ['Surat baru berhasil diterbitkan.', 'Pengingat: periksa data surat hari ini.'];
    @endphp
    <div class="offcanvas-lg offcanvas-start app-sidebar d-flex flex-column p-3" tabindex="-1" id="appSidebar" aria-label="Navigasi utama">
        <div class="d-flex align-items-center gap-2 mb-4 flex-shrink-0">
            <span class="app-brand-mark d-flex align-items-center justify-content-center"><i class="bi bi-buildings-fill"></i></span>
            <div><div class="app-brand-title fw-bold">Desa Sukamaju</div><div class="app-brand-subtitle">Sistem Surat Menyurat</div></div>
        </div>
        <nav class="app-sidebar-menu d-flex flex-column gap-1" aria-label="Menu aplikasi">
            <div class="app-nav-group-label">UTAMA</div>
            <x-sidebar-item :href="route('dashboard')" label="Dashboard" icon="bi-house-door-fill" :active="request()->routeIs('dashboard')" />

            <div class="app-nav-group-label">DATA</div>
            <x-sidebar-item label="Data Warga" icon="bi-people-fill" />

            <div class="app-nav-group-label">PERSURATAN</div>
            <x-sidebar-item :href="route('create-letter-step-1-to-3')" label="Buat Surat" icon="bi-pencil-square" :active="request()->routeIs('create-letter*')" />
            <x-sidebar-item label="Arsip Surat" icon="bi-archive-fill" />
            <x-sidebar-item label="Rekapitulasi Surat" icon="bi-bar-chart-fill" />

            @if ($isAdmin)
                <div class="app-nav-group-label">ADMINISTRASI</div>
                <button class="app-nav-link app-nav-toggle d-flex align-items-center gap-2 border-0 text-start w-100 {{ $settingsActive ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#settingsSubmenu" aria-controls="settingsSubmenu" aria-expanded="{{ $settingsActive ? 'true' : 'false' }}">
                    <i class="bi bi-gear-fill"></i><span class="flex-grow-1">Pengaturan</span><i class="bi bi-chevron-down app-nav-chevron"></i>
                </button>
                <div class="collapse app-nav-submenu {{ $settingsActive ? 'show' : '' }}" id="settingsSubmenu">
                    <a class="app-nav-sublink text-decoration-none" href="#"><i class="bi bi-person-gear"></i><span>Akun Pengguna</span></a>
                    <a class="app-nav-sublink text-decoration-none" href="#"><i class="bi bi-pen"></i><span>Pejabat Penandatangan</span></a>
                    <a class="app-nav-sublink text-decoration-none" href="#"><i class="bi bi-building"></i><span>Identitas Desa</span></a>
                </div>
            @endif
        </nav>
        <div class="app-sidebar-footer mt-auto pt-3 flex-shrink-0">
            <div class="app-sidebar-user d-flex align-items-center gap-2 mb-2">
                <span class="app-sidebar-user-avatar rounded-circle d-flex align-items-center justify-content-center"><i class="bi bi-person-fill"></i></span>
                <div class="min-width-0"><div class="app-sidebar-user-name text-truncate">{{ session('user_name', 'Operator') }}</div><div class="app-sidebar-user-role">{{ session('role', 'Admin') }}</div></div>
            </div>
            <a href="#" class="app-nav-link d-flex align-items-center gap-2 text-decoration-none"><i class="bi bi-box-arrow-left"></i><span>Keluar</span></a>
        </div>
    </div>
    <main class="app-main">
        <header class="app-topbar d-flex align-items-center justify-content-between px-3 px-md-4">
            <div class="d-flex align-items-center gap-3 min-width-0">
                <button class="btn app-topbar-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-label="Buka menu"><i class="bi bi-list"></i></button>
                <button class="btn app-topbar-icon d-none d-lg-inline-flex" type="button" data-app-sidebar-toggle aria-label="Ciutkan sidebar"><i class="bi bi-list"></i></button>
                <div class="app-topbar-heading min-width-0">
                    <div class="app-topbar-title text-truncate">{{ $pageMeta[0] }}</div>
                    <div class="app-topbar-breadcrumb d-none d-md-block text-truncate">{{ $pageMeta[1] }} / {{ $pageMeta[0] }}</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 gap-md-3">
                {{-- <a href="{{ route('create-letter-step-1-to-3') }}" class="btn btn-primary app-topbar-quick-action app-touch-target"><i class="bi bi-pencil-square"></i><span class="d-none d-sm-inline">Buat Surat</span></a> --}}
                <div class="dropdown">
                    <button class="btn app-topbar-icon app-notification-button position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifikasi">
                        <i class="bi bi-bell-fill"></i><span class="app-notification-badge">{{ count($notifications) }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end app-notification-menu p-0">
                        <div class="app-dropdown-heading">Notifikasi</div>
                        @forelse ($notifications as $notification)
                            <div class="app-notification-item"><i class="bi bi-info-circle"></i><span>{{ $notification }}</span></div>
                        @empty
                            <div class="app-notification-empty">Tidak ada notifikasi baru</div>
                        @endforelse
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn app-profile-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="app-avatar rounded-circle bg-body-secondary d-flex align-items-center justify-content-center"><i class="bi bi-person-fill"></i></span>
                        <span class="app-profile-text d-none d-md-flex flex-column text-start"><strong>{{ $userName }}</strong><small>{{ $userRole }}</small></span><i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end app-profile-menu">
                        <li><div class="app-profile-header"><strong>{{ $userName }}</strong><small>{{ $userRole }}</small></div></li>
                        <li><hr class="dropdown-divider"></li><li><a class="dropdown-item" href="#"><i class="bi bi-person"></i>Profil Saya</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-key"></i>Ubah Kata Sandi</a></li>
                        @if ($isAdmin)<li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i>Pengaturan</a></li>@endif
                        <li><hr class="dropdown-divider"></li><li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right"></i>Keluar</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <section class="app-content">@yield('content')</section>
    </main>
    @stack('scripts')
</body>
</html>
