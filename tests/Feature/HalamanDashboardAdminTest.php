<?php

use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

test('dashboard admin menampilkan ringkasan, jadwal, pembayaran, stok, dan pendaftar', function () {
    Carbon::setTestNow('2026-11-04 08:00:00');
    Produk::factory()->create(['stok' => 1]);
    Produk::factory()->create(['stok' => 3]);

    $this->get('/admin')
        ->assertOk()
        ->assertSee('Dashboard operasional')
        ->assertSee('Rabu, 4 November 2026')
        ->assertSeeInOrder(['Pesanan menunggu', '4', 'Jadwal kunjungan', '5', 'Verifikasi bayar', '3', 'Stok kritis', '2'])
        ->assertSee('Raihan Nur Faiz')
        ->assertSee('Belum ditugaskan')
        ->assertSee('Rp250.000')
        ->assertSee('#ORD-0045')
        ->assertSee('Sisa 1')
        ->assertSee('Ahmad Rizky (26 th)')
        ->assertSee('aria-current="page"', false);
});

test('stok kritis diambil dari produk aktif yang stoknya menipis dan tombol restok membuka produk itu', function () {
    $hampirHabis = Produk::factory()->unitAc()->create(['nama' => 'Standard 1,5 PK', 'merek' => 'Gree', 'stok' => 2]);
    $habis = Produk::factory()->habis()->create(['nama' => 'Freon R22 tabung kecil']);
    Produk::factory()->create(['nama' => 'Remote AC universal', 'stok' => 20]);
    Produk::factory()->nonaktif()->create(['nama' => 'Pompa air lama', 'stok' => 1]);

    $this->get('/admin')
        ->assertOk()
        ->assertSeeInOrder(['Freon R22 tabung kecil', 'Sisa 0', 'Gree Standard 1,5 PK', 'Sisa 2'])
        ->assertSee('href="'.route('admin.produk.edit', $habis).'"', false)
        ->assertSee('href="'.route('admin.produk.edit', $hampirHabis).'"', false)
        ->assertDontSee('Remote AC universal')
        ->assertDontSee('Pompa air lama');
});

test('tombol aksi nonaktif selama route tujuannya belum dibuat', function () {
    $this->get('/admin')
        ->assertOk()
        ->assertSee('title="Belum tersedia"', false)
        ->assertSee('disabled', false);
});

test('dashboard admin menampilkan keadaan kosong bila tidak ada data', function () {
    $this->view('admin.dashboard', [
        'ringkasan' => ['pesanan_menunggu' => 0, 'jadwal_hari_ini' => 0, 'verifikasi_bayar' => 0, 'stok_kritis' => 0],
        'jadwalHariIni' => [],
        'verifikasiPembayaran' => [],
        'pesananBaru' => [],
        'stokKritis' => [],
        'pendaftarTeknisi' => [],
    ])
        ->assertSee('Tidak ada kunjungan hari ini.')
        ->assertSee('Tidak ada pembayaran yang perlu dicek.')
        ->assertSee('Semua pesanan sudah ditangani.')
        ->assertSee('Semua stok aman.')
        ->assertSee('Belum ada pendaftar baru.');
});
