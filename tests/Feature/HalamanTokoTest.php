<?php

use App\Enums\KategoriProduk;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('toko hanya menampilkan produk aktif dan memisahkan unit AC dari komponen', function () {
    Produk::factory()->unitAc()->create(['nama' => 'Flash Inverter 0,5 PK']);
    Produk::factory()->create(['nama' => 'Kapasitor AC 30 uF']);
    Produk::factory()->nonaktif()->create(['nama' => 'Freon R22 lama']);

    $this->get(route('toko.index'))
        ->assertOk()
        ->assertSee('2 barang ditampilkan')
        ->assertSeeInOrder(['Flash Inverter 0,5 PK', 'Suku cadang dan komponen', 'Kapasitor AC 30 uF'])
        ->assertDontSee('Freon R22 lama');
});

test('tombol saring kategori dan merek hanya dibuat dari produk aktif', function () {
    Produk::factory()->unitAc()->create(['merek' => 'Daikin']);
    Produk::factory()->unitAc()->create(['merek' => 'Daikin']);
    Produk::factory()->create(['kategori' => KategoriProduk::Freon]);
    Produk::factory()->unitAc()->nonaktif()->create(['merek' => 'Sharp']);
    Produk::factory()->nonaktif()->create(['kategori' => KategoriProduk::Pipa]);

    $html = $this->get(route('toko.index'))->assertOk()->getContent();

    expect($html)
        ->toContain('data-saring-pilihan="kategori" data-nilai="unit-ac"')
        ->toContain('data-saring-pilihan="kategori" data-nilai="freon"')
        ->not->toContain('data-saring-pilihan="kategori" data-nilai="pipa"')
        ->not->toContain('data-saring-pilihan="merek" data-nilai="sharp"');

    expect(substr_count($html, 'data-saring-pilihan="merek" data-nilai="daikin"'))->toBe(1);
});

test('kartu produk menautkan ke halaman detailnya', function () {
    Produk::factory()->create(['slug' => 'remote-ac-universal']);

    $this->get(route('toko.index'))
        ->assertOk()
        ->assertSee('href="'.route('toko.show', 'remote-ac-universal').'"', false);
});

test('toko menampilkan pesan bila belum ada produk aktif', function () {
    Produk::factory()->nonaktif()->create();

    $html = $this->get(route('toko.index'))
        ->assertOk()
        ->assertSee('0 barang ditampilkan')
        ->getContent();

    expect($html)
        ->toMatch('/<div data-saring-kosong\s+class=/')
        ->toMatch('/<section data-saring-bagian\s+hidden/');
});

test('detail produk menampilkan merek, harga, stok, dan spesifikasi', function () {
    Produk::factory()->unitAc()->create([
        'nama' => 'Flash Inverter 0,5 PK',
        'slug' => 'flash-inverter-05-pk',
        'merek' => 'Daikin',
        'model' => 'FTKQ15UVM4',
        'deskripsi' => 'AC split inverter untuk kamar sekitar 3 x 3 meter.',
        'spesifikasi' => ['Daya 340 watt', 'Freon R32'],
        'harga' => 4250000,
        'stok' => 4,
    ]);

    $this->get(route('toko.show', 'flash-inverter-05-pk'))
        ->assertOk()
        ->assertViewIs('pengguna.toko-detail')
        ->assertSee('Jejak halaman')
        ->assertSee('Daikin FTKQ15UVM4')
        ->assertSee('AC split inverter untuk kamar sekitar 3 x 3 meter.')
        ->assertSee('Rp4.250.000')
        ->assertSee('Stok 4 unit')
        ->assertSeeInOrder(['Spesifikasi', 'Daya 340 watt', 'Freon R32']);
});

test('detail produk dengan stok habis menonaktifkan tombol beli', function () {
    Produk::factory()->habis()->create(['slug' => 'freon-r22']);

    $this->get(route('toko.show', 'freon-r22'))
        ->assertOk()
        ->assertSee('Stok habis')
        ->assertSee('disabled', false);
});

test('detail produk nonaktif atau dengan slug salah menghasilkan 404', function () {
    Produk::factory()->nonaktif()->create(['slug' => 'sudah-nonaktif']);

    $this->get(route('toko.show', 'sudah-nonaktif'))->assertNotFound();
    $this->get(route('toko.show', 'tidak-ada'))->assertNotFound();
});

test('nama dan deskripsi di halaman detail tampil sebagai teks, bukan HTML', function () {
    Produk::factory()->create([
        'slug' => 'produk-aneh',
        'nama' => 'Remote <b>murah</b>',
        'deskripsi' => '<script>alert("x")</script>',
    ]);

    $this->get(route('toko.show', 'produk-aneh'))
        ->assertOk()
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>alert("x")</script>', false)
        ->assertDontSee('<b>murah</b>', false);
});
