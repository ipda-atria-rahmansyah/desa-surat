<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pages\ModulePageController;
use App\Http\Controllers\Warga\WargaController;
use App\Support\DashboardData;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => view('pages.dashboard.dashboard', DashboardData::all()))->name('dashboard');
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
        Route::get('/data-desa', [ModulePageController::class, 'dataDesa'])->name('data-desa');
    });
});
