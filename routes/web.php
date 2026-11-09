<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
Route::get('/toko', [TokoController::class, 'index'])->name('toko.index');
Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');

// Middleware auth dan peran admin dipasang setelah login tersedia.
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/registrasi', function () {
    return view('registrasi');
})->name('registrasi');

Route::get('/login', function () {
    return view('login');
})->name('login');
