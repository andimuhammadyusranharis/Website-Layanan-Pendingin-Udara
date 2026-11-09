<?php

use Illuminate\Support\Carbon;

test('dashboard admin menampilkan ringkasan, jadwal, pembayaran, stok, dan pendaftar', function () {
    Carbon::setTestNow('2026-11-04 08:00:00');

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
