<?php

test('pesanan menampilkan daftar, menandai menu aktif, dan membagi per halaman', function () {
    $this->get('/admin/pesanan')
        ->assertOk()
        ->assertSee('Daftar pesanan')
        ->assertSee('14 pesanan')
        ->assertSee('#ORD-0045')
        ->assertSee('Telepon/WA')
        ->assertSee('Menampilkan 1-6 dari 14 pesanan')
        ->assertSee('aria-current="page"', false)
        ->assertDontSee('#ORD-0039');

    $this->get('/admin/pesanan?page=2')
        ->assertOk()
        ->assertSee('#ORD-0039');
});

test('pesanan bisa dicari dan disaring menurut status serta sumber', function () {
    $this->get('/admin/pesanan?cari=fadli')
        ->assertOk()
        ->assertSee('Muh. Fadli')
        ->assertDontSee('Raihan Nur Faiz');

    $this->get('/admin/pesanan?status=dibatalkan&sumber=telepon')
        ->assertOk()
        ->assertSee('Sri Wahyuni')
        ->assertSee('1 pesanan')
        ->assertDontSee('Kartini Usman');

    $this->get('/admin/pesanan?cari=tidakada')
        ->assertOk()
        ->assertSee('Tidak ada pesanan yang cocok.');
});

test('jadwal menampilkan kunjungan hari ini secara bawaan dan bisa pindah ke besok', function () {
    $this->get('/admin/jadwal')
        ->assertOk()
        ->assertSee('Menampilkan 6 dari 6 kunjungan hari ini')
        ->assertSeeInOrder(['07:30-08:30', '09:00-10:00', '15:00-16:00'])
        ->assertDontSee('Ibu Sitti Aminah');

    $this->get('/admin/jadwal?tanggal=besok&teknisi=Pak+Syamsul')
        ->assertOk()
        ->assertSee('Ibu Sitti Aminah')
        ->assertDontSee('Hj. Nurhaedah');
});

test('pembayaran menampilkan yang perlu diverifikasi beserta tab lainnya', function () {
    $this->get('/admin/pembayaran')
        ->assertOk()
        ->assertSee('Perlu verifikasi (3)')
        ->assertSee('Rp 250.000')
        ->assertSee('Setujui')
        ->assertSee('data-konfirmasi="Tolak pembayaran ORD-0041?"', false)
        ->assertDontSee('Drs. Ridwan M.');

    $this->get('/admin/pembayaran?tab=semua&metode=qris_toko')
        ->assertOk()
        ->assertSee('Drs. Ridwan M.')
        ->assertSee('Ditolak')
        ->assertDontSee('Rahmat Hidayat');

    $this->get('/admin/pembayaran?tab=setoran')
        ->assertOk()
        ->assertSee('Belum disetor')
        ->assertSee('Pak Syamsul');
});

test('layanan menampilkan daftar, matriks tarif, dan jenis AC', function () {
    $this->get('/admin/layanan')
        ->assertOk()
        ->assertSee('Layanan (7)')
        ->assertSee('Rp 75.000')
        ->assertSee('Nonaktif')
        ->assertSee('Hapus layanan Cuci AC? Tindakan ini tidak bisa dibatalkan.');

    $this->get('/admin/layanan?status=nonaktif')
        ->assertOk()
        ->assertSee('Paket cuci + isi freon')
        ->assertDontSee('Bersihkan unit indoor dan outdoor');

    $this->get('/admin/layanan?tab=tarif')
        ->assertOk()
        ->assertSee('Standing floor')
        ->assertSee('Rp 850.000');

    $this->get('/admin/layanan?tab=jenis_ac')
        ->assertOk()
        ->assertSee('AC plafon kantor dan toko');
});
