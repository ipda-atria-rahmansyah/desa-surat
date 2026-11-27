<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Pages\ModulePageController;
use App\Http\Controllers\Pengaturan\DesaController;
use App\Http\Controllers\Pengaturan\PejabatController;
use App\Http\Controllers\Warga\WargaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    // Dashboard hanya dapat dibuka pengguna login dan mengambil scope dari session auth.
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/buat-surat', 'pages.letter.create-letter-step-1-3')->name('create-letter-step-1-to-3');
    Route::view('/buat-surat/langkah-4', 'pages.letter.create-letter-step-4')->name('create-letter-step-4');
    Route::view('/buat-surat/langkah-5', 'pages.letter.create-letter-step-5')->name('create-letter-step-5');
    Route::get('/data-warga', [ModulePageController::class, 'warga'])->name('warga.index');
    Route::get('/data-warga/tambah', [WargaController::class, 'create'])->name('warga.create');
    Route::post('/data-warga', [WargaController::class, 'store'])->name('warga.store');
    Route::get('/arsip-surat', [ModulePageController::class, 'arsip'])->name('arsip.index');
    Route::get('/rekapitulasi-surat', [ModulePageController::class, 'rekap'])->name('rekap.index');
    Route::middleware('role:admin')->prefix('pengaturan')->name('settings.')->group(function () {
        Route::get('/', fn () => redirect()->route('settings.pengguna'))->name('index');
        Route::get('/pengguna', [ModulePageController::class, 'pengguna'])->name('pengguna');
        Route::get('/pengguna/tambah', [ModulePageController::class, 'buatPengguna'])->name('pengguna.create');
        Route::post('/pengguna', [ModulePageController::class, 'simpanPengguna'])->name('pengguna.store');
        Route::post('/pengguna/{user}/toggle-status', [ModulePageController::class, 'toggleStatusPengguna'])->name('pengguna.toggle-status');
        Route::get('/pejabat-penandatangan', [ModulePageController::class, 'pejabatPenandatangan'])->name('pejabat-penandatangan');
        // Form tambah pejabat penandatangan; hanya Admin yang dapat membuka dan menyimpan data.
        Route::get('/pejabat-penandatangan/tambah', [PejabatController::class, 'create'])->name('pejabat-penandatangan.create');
        Route::post('/pejabat-penandatangan', [PejabatController::class, 'store'])->name('pejabat-penandatangan.store');
        Route::get('/data-desa', [DesaController::class, 'show'])->name('data-desa');
        Route::patch('/data-desa', [DesaController::class, 'update'])->name('data-desa.update');
    });
});
