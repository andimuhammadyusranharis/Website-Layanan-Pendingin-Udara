<?php

use App\Models\User;

test('halaman pengguna tampil dengan header, footer, dan data sesuai kontrak', function (string $path, string $view, array $variabel) {
    $response = $this->get($path);

    $response->assertOk()
        ->assertViewIs($view)
        ->assertViewHasAll($variabel)
        ->assertSee('Navigasi utama')
        ->assertSee('Metode Pembayaran:');
})->with([
    'beranda' => ['/', 'pengguna.beranda', ['galeri', 'ringkasanUlasan', 'layananUnggulan', 'produkUnggulan', 'ulasan']],
    'layanan' => ['/layanan', 'pengguna.layanan', ['kategoriLayanan', 'layanan', 'simulasiEstimasi']],
    'toko' => ['/toko', 'pengguna.toko', ['kategoriProduk', 'merekPopuler', 'unitAc', 'produk']],
    'ulasan' => ['/ulasan', 'pengguna.ulasan', ['ringkasan', 'kategoriUlasan', 'ulasan']],
    'kontak' => ['/kontak', 'pengguna.kontak', ['profil', 'kontak']],
]);

test('header menandai menu halaman yang sedang dibuka', function () {
    $html = $this->get('/toko')->getContent();

    expect($html)->toMatch('/aria-current="page"[^>]*>Toko</')
        ->and(substr_count($html, 'aria-current="page"'))->toBe(2);
});

test('tamu melihat tombol masuk dan daftar di header', function () {
    $this->get('/')
        ->assertSee('Masuk')
        ->assertSee('Daftar')
        ->assertDontSee('Masuk sebagai');
});

test('pengguna yang login melihat namanya di header', function () {
    $user = User::factory()->make(['name' => 'Raihan Faiz']);

    $this->actingAs($user)
        ->get('/')
        ->assertSee('Raihan Faiz')
        ->assertDontSee('Daftar');
});

test('lencana keranjang hanya tampil bila keranjang berisi', function () {
    $this->get('/')->assertDontSee('ring-2 ring-white', false);

    $this->withSession(['keranjang' => [1 => 2, 5 => 1]])
        ->get('/')
        ->assertSee('Keranjang belanja (2 barang)');
});
