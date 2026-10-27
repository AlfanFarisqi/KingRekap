<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PrediksiController;
use Illuminate\Support\Facades\Route;

// Halaman Utama & Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Kelola Menu
Route::get('/kelola-menu', [MenuController::class, 'index'])->name('menu.index');
Route::post('/kelola-menu', [MenuController::class, 'store'])->name('menu.store');
Route::put('/kelola-menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
Route::delete('/kelola-menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');

// Data Penjualan
Route::get('/data-penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::post('/data-penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
Route::put('/data-penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.update');
Route::delete('/data-penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

// Laporan Penjualan
Route::get('/laporan-penjualan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan-penjualan/data', [LaporanController::class, 'getData'])->name('laporan.data');
Route::get('/laporan-penjualan/export', [LaporanController::class, 'export'])->name('laporan.export');

// Prediksi Kemasan
Route::get('/prediksi-kemasan', [PrediksiController::class, 'index'])->name('prediksi.index');
Route::post('/prediksi-kemasan/hitung', [PrediksiController::class, 'calculate'])->name('prediksi.calculate');

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
