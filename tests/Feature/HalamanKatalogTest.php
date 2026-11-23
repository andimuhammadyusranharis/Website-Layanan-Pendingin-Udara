<?php

use App\Enums\KategoriLayanan;
use App\Models\Layanan;
use App\Models\Ulasan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('daftar layanan menampilkan kartu ringkas dari database dengan tautan ke halaman detail', function () {
    Layanan::factory()->create(['nama' => 'Cuci AC Split', 'slug' => 'cuci-ac-split', 'tarif_mulai' => 75000, 'satuan' => 'unit']);
    Layanan::factory()->create(['nama' => 'Isi Freon R32', 'slug' => 'isi-freon-r32', 'tarif_mulai' => 150000]);

    $this->get('/layanan')
        ->assertOk()
        ->assertSee('Cuci AC Split')
        ->assertSee('Rp75.000')
        ->assertSee('Rp150.000')
        ->assertSee('/ unit')
        ->assertSee('2 layanan ditampilkan')
        ->assertSee('Lihat detail')
        ->assertSee(route('layanan.show', 'cuci-ac-split'), false)
        ->assertDontSee('Pesan layanan');
});

test('daftar layanan tidak memuat poin pekerjaan dan durasi, tetapi tetap bisa dicari lewat poin itu', function () {
    Layanan::factory()->create([
        'nama' => 'Cuci AC Split',
        'durasi' => '45 sampai 60 menit',
        'fitur' => ['Filter dan kipas dicuci'],
    ]);

    $this->get('/layanan')
        ->assertOk()
        ->assertDontSee('45 sampai 60 menit')
        ->assertDontSee('Filter dan kipas dicuci')
        ->assertSee('filter dan kipas dicuci', false);
});

test('tombol saring hanya memuat kategori yang punya layanan aktif', function () {
    Layanan::factory()->create(['kategori' => KategoriLayanan::CuciAc]);
    Layanan::factory()->create(['kategori' => KategoriLayanan::PerbaikanFreon]);
    Layanan::factory()->nonaktif()->create(['kategori' => KategoriLayanan::Komersial]);

    $this->get('/layanan')
        ->assertOk()
        ->assertSee('data-nilai="cuci-ac"', false)
        ->assertSee('data-nilai="perbaikan-freon"', false)
        ->assertDontSee('data-nilai="komersial"', false);
});

test('tombol saring tidak ditampilkan bila hanya ada satu kategori', function () {
    Layanan::factory()->count(2)->create(['kategori' => KategoriLayanan::CuciAc]);

    $this->get('/layanan')
        ->assertOk()
        ->assertDontSee('data-saring-pilihan', false);
});

test('layanan nonaktif atau yang sudah dihapus admin tidak tampil di halaman pengguna', function () {
    Layanan::factory()->create(['nama' => 'Layanan Berjalan']);
    Layanan::factory()->nonaktif()->create(['nama' => 'Layanan Dihentikan']);
    $dihapus = Layanan::factory()->create(['nama' => 'Layanan Dihapus']);
    $dihapus->delete();

    $this->get('/layanan')
        ->assertOk()
        ->assertSee('Layanan Berjalan')
        ->assertSee('1 layanan ditampilkan')
        ->assertDontSee('Layanan Dihentikan')
        ->assertDontSee('Layanan Dihapus');
});

test('layanan menampilkan pesan kosong bila belum ada layanan aktif', function () {
    $this->get('/layanan')
        ->assertOk()
        ->assertSee('0 layanan ditampilkan')
        ->assertSee('Daftar layanan belum tersedia.')
        ->assertDontSee('Cari layanan');
});

test('daftar layanan yang perlu survei menampilkan keterangan tanpa harga', function () {
    $this->view('pengguna.layanan', [
        'kategoriLayanan' => [],
        'layanan' => [
            ['id' => 1, 'slug' => 'cuci-ac', 'nama' => 'Cuci AC', 'kategori' => '', 'kategori_label' => null, 'jenis_ac' => null, 'durasi' => null, 'deskripsi' => 'Cuci unit', 'fitur' => [], 'keterangan_harga' => 'Mulai dari', 'harga_mulai' => 75000, 'satuan' => null, 'catatan_harga' => null, 'perlu_survei' => false, 'gambar' => null],
            ['id' => 2, 'slug' => 'ac-sentral', 'nama' => 'AC Sentral', 'kategori' => '', 'kategori_label' => null, 'jenis_ac' => null, 'durasi' => null, 'deskripsi' => 'Perlu dilihat langsung', 'fitur' => [], 'keterangan_harga' => 'Biaya', 'harga_mulai' => null, 'satuan' => null, 'catatan_harga' => null, 'perlu_survei' => true, 'gambar' => null],
        ],
        'simulasiEstimasi' => ['judul' => 'Contoh', 'rincian' => [], 'total' => 0],
    ])->assertSee('Setelah survei')->assertSee('Rp75.000');
});

test('detail layanan menampilkan deskripsi, poin pekerjaan, harga, dan tombol pesan ke whatsapp', function () {
    Layanan::factory()->create([
        'nama' => 'Cuci AC Split',
        'slug' => 'cuci-ac-split',
        'kategori' => KategoriLayanan::CuciAc,
        'deskripsi' => 'Unit dalam dan unit luar dibersihkan menyeluruh.',
        'jenis_ac' => 'Split dinding 0,5 sampai 2,5 PK',
        'durasi' => '45 sampai 60 menit',
        'fitur' => ['Filter dan kipas dicuci', 'Saluran air dibersihkan'],
        'tarif_mulai' => 75000,
        'satuan' => 'unit',
        'catatan_harga' => 'Belum termasuk suku cadang',
    ]);

    $this->get('/layanan/cuci-ac-split')
        ->assertOk()
        ->assertViewIs('pengguna.layanan-detail')
        ->assertSee('Cuci AC Split')
        ->assertSee('Unit dalam dan unit luar dibersihkan menyeluruh.')
        ->assertSee('Filter dan kipas dicuci')
        ->assertSee('Split dinding 0,5 sampai 2,5 PK')
        ->assertSee('45 sampai 60 menit')
        ->assertSee('Rp75.000')
        ->assertSee('Belum termasuk suku cadang')
        ->assertSee('Jejak halaman')
        ->assertSee('Pesan layanan')
        ->assertSee('https://wa.me/6281234567890?text=', false);
});

test('detail layanan yang perlu survei menampilkan ajukan survei tanpa harga dan tanpa tombol pesan', function () {
    Layanan::factory()->perluSurvei()->create(['nama' => 'AC Sentral Gedung', 'slug' => 'ac-sentral-gedung']);

    $this->get('/layanan/ac-sentral-gedung')
        ->assertOk()
        ->assertSee('Ajukan survei')
        ->assertSee('Setelah survei')
        ->assertDontSee('Pesan layanan');
});

test('detail layanan menawarkan layanan lain dengan yang sekategori lebih dulu', function () {
    $utama = Layanan::factory()->create(['nama' => 'Layanan Utama', 'slug' => 'layanan-utama', 'kategori' => KategoriLayanan::CuciAc]);
    Layanan::factory()->create(['nama' => 'Beda Kategori', 'kategori' => KategoriLayanan::Komersial, 'urutan' => 1]);
    Layanan::factory()->create(['nama' => 'Sekategori Dekat', 'kategori' => KategoriLayanan::CuciAc, 'urutan' => 2]);
    Layanan::factory()->nonaktif()->create(['nama' => 'Tidak Aktif']);

    $html = $this->get('/layanan/layanan-utama')->assertOk()->getContent();

    expect($html)->toContain('Layanan lainnya')
        ->and(strpos($html, 'Sekategori Dekat'))->toBeLessThan(strpos($html, 'Beda Kategori'))
        ->and($html)->not->toContain('Tidak Aktif');

    expect(substr_count($html, 'Layanan Utama'))->toBeGreaterThan(0);
    expect($utama->slug)->toBe('layanan-utama');
});

test('detail layanan nonaktif, terhapus, atau dengan slug salah menghasilkan 404', function () {
    Layanan::factory()->nonaktif()->create(['slug' => 'sudah-nonaktif']);
    Layanan::factory()->create(['slug' => 'akan-dihapus'])->delete();

    $this->get('/layanan/sudah-nonaktif')->assertNotFound();
    $this->get('/layanan/akan-dihapus')->assertNotFound();
    $this->get('/layanan/tidak-ada')->assertNotFound();
});

test('ulasan menampilkan ringkasan rating dan tanggal berbahasa indonesia', function () {
    Ulasan::factory()->create(['rating' => 5, 'created_at' => '2026-05-18 10:00:00']);
    Ulasan::factory()->create(['rating' => 4]);

    $this->get('/ulasan')
        ->assertOk()
        ->assertSee('4,5')
        ->assertSee('Dari 2 ulasan pelanggan')
        ->assertSee('18 Mei 2026')
        ->assertSee('data-rating="4"', false);
});

test('ulasan menampilkan keadaan kosong bila belum ada ulasan', function () {
    $this->view('pengguna.ulasan', [
        'ringkasan' => ['rata_rata' => 0, 'jumlah' => 0, 'persen_puas' => 0, 'sebaran' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0], 'aspek' => []],
        'kategoriUlasan' => [],
        'ulasan' => [],
    ])
        ->assertSee('Belum ada ulasan.')
        ->assertDontSee('Dari 0 ulasan');
});

test('toko memberi tanda stok dan menonaktifkan barang yang habis', function () {
    $this->get('/toko')
        ->assertOk()
        ->assertSee('Sisa 2')
        ->assertSee('Stok 16')
        ->assertSee('data-merek="daikin panasonic"', false);

    $this->view('pengguna.toko', [
        'kategoriProduk' => [],
        'merekPopuler' => [],
        'unitAc' => [],
        'produk' => [
            ['id' => 1, 'slug_kategori' => 'freon', 'kategori' => 'Freon', 'nama' => 'Freon R22', 'deskripsi' => 'Tabung kecil.', 'harga' => 150000, 'satuan' => null, 'stok' => 0, 'gambar' => null],
        ],
    ])
        ->assertSee('Stok habis')
        ->assertSee('disabled', false)
        ->assertSee('Foto menyusul');
});

test('kontak menyediakan tautan telepon, whatsapp, dan peta', function () {
    $this->get('/kontak')
        ->assertOk()
        ->assertSee('href="tel:081234567890"', false)
        ->assertSee('https://www.google.com/maps/search/?api=1&amp;query=', false)
        ->assertSee('sejak 2006');
});
