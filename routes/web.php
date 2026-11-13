<?php

use App\Support\DashboardData;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('pages.dashboard', DashboardData::all()))->name('dashboard');

Route::view('/login', 'pages.login')->name('login');
