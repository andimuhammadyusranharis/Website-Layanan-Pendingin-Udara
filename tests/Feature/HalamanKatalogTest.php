<?php

test('layanan menampilkan tarif dan mengarahkan pesanan ke whatsapp selama halaman pesan belum ada', function () {
    $this->get('/layanan')
        ->assertOk()
        ->assertSee('Rp75.000')
        ->assertSee('6 layanan ditampilkan')
        ->assertSee('Pesan layanan')
        ->assertSee('https://wa.me/6281234567890?text=', false);
});

test('layanan yang perlu survei tidak menampilkan harga, hanya tombol ajukan survei', function () {
    $html = $this->get('/layanan')->getContent();

    expect($html)->toContain('Ajukan survei')
        ->and(substr_count($html, 'Pesan layanan'))->toBe(5);
});

test('ulasan menampilkan ringkasan rating dan tanggal berbahasa indonesia', function () {
    $this->get('/ulasan')
        ->assertOk()
        ->assertSee('4,9')
        ->assertSee('Dari 128 ulasan pelanggan')
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
