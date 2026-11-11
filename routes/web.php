<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\LayananController as AdminLayananController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Autentikasi\DaftarController;
use App\Http\Controllers\Autentikasi\GoogleController;
use App\Http\Controllers\Autentikasi\MasukController;
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

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [MasukController::class, 'create'])->name('login');
    Route::post('/masuk', [MasukController::class, 'store'])->name('login.store');
    Route::get('/daftar', [DaftarController::class, 'create'])->name('registrasi');
    Route::post('/daftar', [DaftarController::class, 'store'])->name('registrasi.store');
    Route::get('/auth/google', [GoogleController::class, 'alihkan'])->name('google.alihkan');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

Route::post('/keluar', [MasukController::class, 'destroy'])->middleware('auth')->name('logout');

// Alamat lama dari tampilan awal tetap diarahkan ke halaman yang baru.
Route::redirect('/login', '/masuk');
Route::redirect('/registrasi', '/daftar');

Route::middleware(['auth', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/layanan', [AdminLayananController::class, 'index'])->name('layanan.index');
});
