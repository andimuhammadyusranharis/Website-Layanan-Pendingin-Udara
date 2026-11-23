<?php

use App\Models\JenisAc;
use App\Models\Layanan;
use App\Models\TarifLayanan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman estimasi terbuka untuk tamu dan menampilkan pilihan dari database', function () {
    $layanan = Layanan::factory()->create(['nama' => 'Cuci AC']);
    $jenisAc = JenisAc::factory()->create(['nama' => 'Cassette']);

    $this->get(route('estimasi'))
        ->assertOk()
        ->assertSee('Estimasi biaya servis AC')
        ->assertSee('Cuci AC')
        ->assertSee('Cassette');

    expect($layanan->id_layanan)->toBeInt()
        ->and($jenisAc->id_jenis_ac)->toBeInt();
});

test('estimasi menghitung tarif per unit dikali jumlah unit', function () {
    $layanan = Layanan::factory()->create(['nama' => 'Cuci AC']);
    $jenisAc = JenisAc::factory()->create(['nama' => 'Cassette']);

    TarifLayanan::factory()->create([
        'id_layanan' => $layanan->id_layanan,
        'id_jenis_ac' => $jenisAc->id_jenis_ac,
        'tarif' => 75000,
    ]);

    $this->get(route('estimasi', [
        'layanan' => $layanan->id_layanan,
        'jenis_ac' => $jenisAc->id_jenis_ac,
        'jumlah' => 3,
    ]))
        ->assertOk()
        ->assertSee('Cuci AC')
        ->assertSee('Cassette')
        ->assertSee('3 unit')
        ->assertSee('Rp75.000')
        ->assertSee('Rp225.000');
});

test('kombinasi tanpa tarif ditolak dengan pesan yang jelas', function () {
    $layanan = Layanan::factory()->create(['nama' => 'Cuci AC']);
    $jenisAc = JenisAc::factory()->create(['nama' => 'Cassette']);

    TarifLayanan::factory()->create([
        'id_layanan' => $layanan->id_layanan,
        'id_jenis_ac' => $jenisAc->id_jenis_ac,
        'tarif' => null,
    ]);

    $this->get(route('estimasi', [
        'layanan' => $layanan->id_layanan,
        'jenis_ac' => $jenisAc->id_jenis_ac,
        'jumlah' => 2,
    ]))
        ->assertOk()
        ->assertSee('Layanan ini tidak tersedia untuk jenis AC tersebut.');
});

test('jumlah unit di luar batas ditolak', function () {
    $layanan = Layanan::factory()->create();
    $jenisAc = JenisAc::factory()->create();

    $this->get(route('estimasi', [
        'layanan' => $layanan->id_layanan,
        'jenis_ac' => $jenisAc->id_jenis_ac,
        'jumlah' => 0,
    ]))
        ->assertOk()
        ->assertSee('Jumlah unit harus antara 1 sampai 1000.');
});

test('layanan nonaktif tidak muncul sebagai pilihan estimasi', function () {
    Layanan::factory()->nonaktif()->create(['nama' => 'Paket Cuci Promo']);
    Layanan::factory()->create(['nama' => 'Cuci AC Reguler']);

    $this->get(route('estimasi'))
        ->assertOk()
        ->assertSee('Cuci AC Reguler')
        ->assertDontSee('Paket Cuci Promo');
});

test('halaman detail layanan menampilkan tabel tarif per jenis ac', function () {
    $layanan = Layanan::factory()->create([
        'nama' => 'Cuci AC',
        'slug' => 'cuci-ac',
    ]);
    $jenisAc = JenisAc::factory()->create(['nama' => 'Cassette']);

    TarifLayanan::factory()->create([
        'id_layanan' => $layanan->id_layanan,
        'id_jenis_ac' => $jenisAc->id_jenis_ac,
        'tarif' => 200000,
    ]);

    $this->get(route('layanan.show', $layanan->slug))
        ->assertOk()
        ->assertSee('Tarif per jenis AC')
        ->assertSee('Cassette')
        ->assertSee('Rp200.000');
});
