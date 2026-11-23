<?php

use App\Services\HalamanStatisService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman statis tampil dengan header, footer, dan isi sesuai kontrak', function (string $path, string $judul, string $bagianPertama) {
    $this->get($path)
        ->assertOk()
        ->assertViewIs('pengguna.halaman-statis')
        ->assertViewHas('halaman')
        ->assertSee('Navigasi utama')
        ->assertSee('Metode Pembayaran:')
        ->assertSee($judul)
        ->assertSee($bagianPertama)
        ->assertSee('Baca juga');
})->with([
    'kebijakan privasi' => ['/kebijakan-privasi', 'Kebijakan privasi', 'Data yang kami minta'],
    'syarat dan ketentuan' => ['/syarat', 'Syarat dan ketentuan', 'Area layanan dan cara memesan'],
]);

test('footer menautkan ke kedua halaman statis', function () {
    $this->get('/')
        ->assertSee('href="'.route('kebijakan-privasi').'"', false)
        ->assertSee('href="'.route('syarat').'"', false);
});

test('syarat memuat aturan pembatalan dan area layanan dari PRD', function () {
    $this->get('/syarat')
        ->assertOk()
        ->assertSee('12 jam sebelum jam kerja')
        ->assertSee('Kabupaten Pangkep')
        ->assertSee('perkiraan dan bisa berubah setelah survei');
});

test('kebijakan privasi menyebut data yang diminta dan cara menghubungi kami', function () {
    $this->get('/kebijakan-privasi')
        ->assertOk()
        ->assertSee('nama, nomor telepon, alamat, email, dan kata sandi')
        ->assertSee('UU Perlindungan Data Pribadi')
        ->assertSee('Hubungi kami lewat WhatsApp');
});

test('setiap bagian halaman statis punya judul dan isi', function (string $metode) {
    $halaman = app(HalamanStatisService::class)->{$metode}();

    expect($halaman['bagian'])->not->toBeEmpty();

    foreach ($halaman['bagian'] as $bagian) {
        expect($bagian['judul'])->not->toBeEmpty()
            ->and(! empty($bagian['paragraf']) || ! empty($bagian['poin']))->toBeTrue();
    }
})->with(['kebijakanPrivasi', 'syarat']);

test('alamat halaman statis yang salah menghasilkan 404', function () {
    $this->get('/kebijakan')->assertNotFound();
    $this->get('/syarat-dan-ketentuan')->assertNotFound();
});

test('halaman masuk menautkan ke kedua halaman statis', function () {
    $this->get(route('login'))
        ->assertSee('href="'.route('kebijakan-privasi').'"', false)
        ->assertSee('href="'.route('syarat').'"', false);
});
