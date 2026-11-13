<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Surat Menyurat Desa Sukamaju</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-login-page">
    <main class="app-login-shell">
        <section class="app-login-intro">
            <div class="app-login-brand d-flex align-items-center gap-3">
                <span class="app-login-emblem d-flex align-items-center justify-content-center" aria-hidden="true"><i class="bi bi-buildings-fill"></i></span>
                <div>
                    <h1 class="app-login-brand-title mb-1">Desa Sukamaju</h1>
                    <p class="app-login-brand-subtitle mb-0">Sistem Surat Menyurat</p>
                </div>
            </div>
            <div class="app-login-welcome">
                <h2 class="mb-3">Selamat Datang!</h2>
                <p class="mb-0">Masuk ke akun Anda untuk<br>mengakses sistem surat menyurat desa.</p>
            </div>
            <div class="app-login-landscape" aria-hidden="true">
                <i class="bi bi-flag-fill app-login-flag"></i>
                <i class="bi bi-building-fill app-login-building"></i>
                <i class="bi bi-tree-fill app-login-tree app-login-tree--one"></i>
                <i class="bi bi-tree-fill app-login-tree app-login-tree--two"></i>
            </div>
        </section>

        <section class="app-login-form-panel">
            <div class="app-login-form-wrap">
                <div class="app-login-lock d-flex align-items-center justify-content-center mx-auto mb-3"><i class="bi bi-shield-lock"></i></div>
                <h2 class="app-login-title text-center mb-2">Login</h2>
                <p class="app-login-subtitle text-center mb-5">Silakan masuk dengan akun Anda</p>

                <form action="#" method="post" class="d-flex flex-column gap-3">
                    <div class="app-login-input input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" class="form-control" placeholder="Username atau NIK" aria-label="Username atau NIK">
                    </div>
                    <div class="app-login-input input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" placeholder="Password" aria-label="Password">
                        <button class="btn app-login-toggle" type="button" aria-label="Tampilkan password"><i class="bi bi-eye-slash-fill"></i></button>
                    </div>
                    <div class="d-flex align-items-center justify-content-between app-login-options">
                        <label class="d-flex align-items-center gap-2 mb-0"><input class="form-check-input m-0" type="checkbox"> <span>Ingat saya</span></label>
                        <a href="#" class="app-login-link">Lupa password?</a>
                    </div>
                    <button type="submit" class="btn app-login-submit"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk</button>
                </form>

                <div class="app-login-divider d-flex align-items-center gap-3 my-4"><span></span><b>atau</b><span></span></div>
                <div class="app-login-help d-flex align-items-start gap-3"><i class="bi bi-info-circle"></i><span>Untuk akses sistem, hubungi operator desa<br>jika mengalami kendala.</span></div>
            </div>
        </section>
    </main>
</body>
</html>