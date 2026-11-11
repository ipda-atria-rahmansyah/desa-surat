<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Surat Menyurat Desa Sukamaju</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="offcanvas-lg offcanvas-start app-sidebar d-flex flex-column p-3" tabindex="-1" id="appSidebar" aria-label="Navigasi utama">
        <div class="d-flex align-items-center gap-2 mb-4">
            <span class="app-brand-mark d-flex align-items-center justify-content-center"><i class="bi bi-buildings-fill"></i></span>
            <div><div class="app-brand-title fw-bold">Desa Sukamaju</div><div class="app-brand-subtitle">Sistem Surat Menyurat</div></div>
        </div>
        <nav class="d-flex flex-column gap-1">
            <x-sidebar-item label="Dashboard" icon="bi-house-door-fill" :active="true" />
            <x-sidebar-item label="Buat Surat" icon="bi-pencil-square" />
            <x-sidebar-item label="Riwayat Surat" icon="bi-clock-history" />
            <x-sidebar-item label="Data Warga" icon="bi-people-fill" />
            <x-sidebar-item label="Pengaturan" icon="bi-gear-fill" />
        </nav>
        <div class="app-nav-logout mt-auto pt-3"><a href="#" class="app-nav-link d-flex align-items-center gap-2 text-decoration-none"><i class="bi bi-box-arrow-left"></i><span>Keluar</span></a></div>
    </div>
    <main class="app-main">
        <header class="app-topbar d-flex align-items-center justify-content-between px-3 px-md-4">
            <button class="btn border-0 p-0 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-label="Buka menu"><i class="bi bi-list fs-4"></i></button>
            <span class="d-none d-lg-inline"><i class="bi bi-list fs-5"></i></span>
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-bell-fill text-success"></i>
                <div class="dropdown">
                    <button class="btn border-0 p-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="app-avatar rounded-circle bg-body-secondary d-flex align-items-center justify-content-center"><i class="bi bi-person-fill"></i></span>
                        <span class="d-none d-md-inline fw-semibold">Operator</span><i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end"><li><span class="dropdown-item">Operator</span></li></ul>
                </div>
            </div>
        </header>
        <section class="app-content">@yield('content')</section>
    </main>
</body>
</html>