<?php

use App\Models\JenisAc;
use App\Models\Layanan;
use App\Models\TarifLayanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

test('tab jenis ac menampilkan daftar jenis ac dari database', function () {
    JenisAc::factory()->create([
        'nama' => 'Cassette',
        'keterangan' => 'AC plafon kantor dan toko',
    ]);
    JenisAc::factory()->create(['nama' => 'Standing floor']);

    $this->get(route('admin.layanan.index', ['tab' => 'jenis_ac']))
        ->assertOk()
        ->assertSee('Cassette')
        ->assertSee('AC plafon kantor dan toko')
        ->assertSee('Standing floor');
});

test('tab jenis ac bisa dicari', function () {
    JenisAc::factory()->create(['nama' => 'Cassette']);
    JenisAc::factory()->create(['nama' => 'Standing floor']);

    $this->get(route('admin.layanan.index', ['tab' => 'jenis_ac', 'cari' => 'cassette']))
        ->assertOk()
        ->assertSee('Cassette')
        ->assertDontSee('Standing floor');
});

test('admin bisa menambah jenis ac', function () {
    $this->post(route('admin.layanan.jenis-ac.store'), [
        'nama' => '  Cassette  ',
        'keterangan' => 'AC plafon kantor dan toko',
    ])->assertRedirect(route('admin.layanan.index', ['tab' => 'jenis_ac']));

    $this->assertDatabaseHas('jenis_ac', [
        'nama' => 'Cassette',
        'keterangan' => 'AC plafon kantor dan toko',
    ]);
});

test('nama jenis ac wajib diisi dan tidak boleh sama', function () {
    JenisAc::factory()->create(['nama' => 'Cassette']);

    $this->post(route('admin.layanan.jenis-ac.store'), ['nama' => ''])
        ->assertSessionHasErrors('nama');

    $this->post(route('admin.layanan.jenis-ac.store'), ['nama' => 'Cassette'])
        ->assertSessionHasErrors('nama');
});

test('admin bisa mengubah jenis ac', function () {
    $jenisAc = JenisAc::factory()->create(['nama' => 'Cassette']);

    $this->put(route('admin.layanan.jenis-ac.update', $jenisAc), [
        'nama' => 'Cassette 4 PK',
        'keterangan' => 'AC plafonruang besar',
    ])->assertRedirect(route('admin.layanan.index', ['tab' => 'jenis_ac']));

    $this->assertDatabaseHas('jenis_ac', [
        'id_jenis_ac' => $jenisAc->id_jenis_ac,
        'nama' => 'Cassette 4 PK',
        'keterangan' => 'AC plafonruang besar',
    ]);
});

test('hapus jenis ac ikut menghapus tarifnya', function () {
    $jenisAc = JenisAc::factory()->create();
    $layanan = Layanan::factory()->create();

    TarifLayanan::factory()->create([
        'id_layanan' => $layanan->id_layanan,
        'id_jenis_ac' => $jenisAc->id_jenis_ac,
    ]);

    $this->delete(route('admin.layanan.jenis-ac.destroy', $jenisAc))
        ->assertRedirect(route('admin.layanan.index', ['tab' => 'jenis_ac']));

    $this->assertDatabaseMissing('jenis_ac', ['id_jenis_ac' => $jenisAc->id_jenis_ac]);
    $this->assertDatabaseMissing('tarif_layanan', ['id_jenis_ac' => $jenisAc->id_jenis_ac]);
});

test('halaman ubah tarif menampilkan matriks layanan dan jenis ac', function () {
    $layanan = Layanan::factory()->create(['nama' => 'Cuci AC']);
    $jenisAc = JenisAc::factory()->create(['nama' => 'Cassette']);

    TarifLayanan::factory()->create([
        'id_layanan' => $layanan->id_layanan,
        'id_jenis_ac' => $jenisAc->id_jenis_ac,
        'tarif' => 200000,
    ]);

    $this->get(route('admin.layanan.tarif.edit'))
        ->assertOk()
        ->assertSee('Cuci AC')
        ->assertSee('Cassette')
        ->assertSee('200000');
});

test('admin bisa menyimpan matriks tarif', function () {
    $layanan = Layanan::factory()->create();
    $jenisAc = JenisAc::factory()->create();

    $this->put(route('admin.layanan.tarif.update'), [
        'tarif' => [
            $layanan->id_layanan => [
                $jenisAc->id_jenis_ac => 175000,
            ],
        ],
    ])->assertRedirect(route('admin.layanan.index', ['tab' => 'tarif']));

    $this->assertDatabaseHas('tarif_layanan', [
        'id_layanan' => $layanan->id_layanan,
        'id_jenis_ac' => $jenisAc->id_jenis_ac,
        'tarif' => 175000,
    ]);
});

test('sel tarif kosong berarti layanan tidak tersedia untuk jenis ac itu', function () {
    $layanan = Layanan::factory()->create();
    $jenisAc = JenisAc::factory()->create();

    $this->put(route('admin.layanan.tarif.update'), [
        'tarif' => [
            $layanan->id_layanan => [
                $jenisAc->id_jenis_ac => '',
            ],
        ],
    ])->assertRedirect(route('admin.layanan.index', ['tab' => 'tarif']));

    $this->assertDatabaseHas('tarif_layanan', [
        'id_layanan' => $layanan->id_layanan,
        'id_jenis_ac' => $jenisAc->id_jenis_ac,
        'tarif' => null,
    ]);
});

test('tarif harus berupa angka', function () {
    $this->put(route('admin.layanan.tarif.update'), [
        'tarif' => [1 => [1 => 'bukan angka']],
    ])->assertSessionHasErrors('tarif.1.1');
});
