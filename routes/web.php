<?php

use App\Support\DashboardData;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('pages.dashboard.dashboard', DashboardData::all()))->name('dashboard');

Route::view('/login', 'pages.auth.login')->name('login');

Route::view('/buat-surat', 'pages.letter.create-letter-step-1-3')->name('create-letter-step-1-to-3');

Route::view('/buat-surat/langkah-4', 'pages.letter.create-letter-step-4')->name('create-letter-step-4');

Route::view('/buat-surat/langkah-5', 'pages.letter.create-letter-step-5')->name('create-letter-step-5');
