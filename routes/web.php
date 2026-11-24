<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\JenisAcController as AdminJenisAcController;
use App\Http\Controllers\Admin\LayananController as AdminLayananController;
use App\Http\Controllers\Admin\PelangganController as AdminPelangganController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\TarifLayananController as AdminTarifLayananController;
use App\Http\Controllers\Admin\TeknisiController as AdminTeknisiController;
use App\Http\Controllers\Admin\UlasanController as AdminUlasanController;
use App\Http\Controllers\Akun\ProfilController;
use App\Http\Controllers\Autentikasi\DaftarController;
use App\Http\Controllers\Autentikasi\GoogleController;
use App\Http\Controllers\Autentikasi\MasukController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\EstimasiController;
use App\Http\Controllers\HalamanStatisController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
Route::get('/layanan/{slug}', [LayananController::class, 'show'])->name('layanan.show');
Route::get('/toko/{slug}', [TokoController::class, 'show'])->name('toko.show');
Route::get('/toko', [TokoController::class, 'index'])->name('toko.index');
Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');

// Modul T2: halaman estimasi biaya untuk pengguna.
Route::get('/estimasi', [EstimasiController::class, 'index'])->name('estimasi');

// Halaman statis
Route::get('/kebijakan-privasi', [HalamanStatisController::class, 'kebijakanPrivasi'])->name('kebijakan-privasi');
Route::get('/syarat', [HalamanStatisController::class, 'syarat'])->name('syarat');

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

    // Modul T2: jenis AC dan tarif per jenis AC.
    Route::prefix('layanan')->name('layanan.')->group(function () {
        Route::resource('jenis-ac', AdminJenisAcController::class)->except('show');
    });

    Route::get('layanan/tarif', [AdminTarifLayananController::class, 'edit'])->name('layanan.tarif.edit');
    Route::put('layanan/tarif', [AdminTarifLayananController::class, 'update'])->name('layanan.tarif.update');

    // Ulasan pelanggan
    Route::get('/ulasan', [AdminUlasanController::class, 'index'])->name('ulasan.index');
    Route::patch('/ulasan/{ulasan}/tampilan', [AdminUlasanController::class, 'ubahTampilan'])->name('ulasan.tampilan');

    Route::resource('layanan', AdminLayananController::class)->except('show');
    Route::resource('produk', ProdukController::class)->except('show');
    Route::resource('pelanggan', AdminPelangganController::class)->only(['index', 'show']);
    Route::get('/teknisi', [AdminTeknisiController::class, 'index'])->name('teknisi.index');
    Route::patch('/teknisi/{teknisi}/setujui', [AdminTeknisiController::class, 'setujui'])->name('teknisi.setujui');
    Route::patch('/teknisi/{teknisi}/tolak', [AdminTeknisiController::class, 'tolak'])->name('teknisi.tolak');
});

Route::middleware(['auth', 'peran:pelanggan'])->prefix('akun')->name('akun.')->group(function () {
    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});
