<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PrediksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/kelola-menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/data-penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::get('/laporan-penjualan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/prediksi-kemasan', [PrediksiController::class, 'index'])->name('prediksi.index');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
