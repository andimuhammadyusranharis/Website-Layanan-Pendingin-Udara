<?php

use App\Enums\KategoriProduk;
use App\Models\Produk;
use App\Models\User;
use App\Services\ProdukService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

/**
 * @param  array<string, mixed>  $timpa
 * @return array<string, mixed>
 */
function dataProdukValid(array $timpa = []): array
{
    return array_merge([
        'nama' => 'Selang pembuangan AC',
        'kategori' => 'aksesoris',
        'deskripsi' => 'Selang lentur untuk saluran air buangan unit indoor.',
        'harga' => 25000,
        'stok' => 10,
        'satuan' => 'meter',
        'aktif' => '1',
    ], $timpa);
}

function fotoProdukPalsu(string $nama = 'foto.png', int $tambahanByte = 0): UploadedFile
{
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

    return UploadedFile::fake()->createWithContent($nama, $png.str_repeat("\0", $tambahanByte));
}

beforeEach(function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->admin()->create());
});

test('daftar produk membaca data dari database dan bisa dicari lewat nama, merek, atau model', function () {
    Produk::factory()->create(['nama' => 'Kapasitor AC 30 uF']);
    Produk::factory()->unitAc()->create(['nama' => 'Standard 1 PK', 'merek' => 'Panasonic', 'model' => 'CS-YN9WKJ']);

    $this->get(route('admin.produk.index'))
        ->assertOk()
        ->assertSee('Saat ini ada 2 produk.')
        ->assertSee('Kapasitor AC 30 uF')
        ->assertSee('Panasonic CS-YN9WKJ');

    $this->get(route('admin.produk.index', ['cari' => 'panasonic']))
        ->assertSee('Standard 1 PK')
        ->assertDontSee('Kapasitor AC 30 uF');

    $this->get(route('admin.produk.index', ['cari' => 'yn9wkj']))
        ->assertSee('Standard 1 PK')
        ->assertDontSee('Kapasitor AC 30 uF');
});

test('pencarian tetap mengikuti saringan status', function () {
    Produk::factory()->create(['nama' => 'Remote AC baru']);
    Produk::factory()->nonaktif()->create(['nama' => 'Remote AC lama']);

    $this->get(route('admin.produk.index', ['cari' => 'remote', 'status' => 'aktif']))
        ->assertOk()
        ->assertSee('Remote AC baru')
        ->assertDontSee('Remote AC lama');

    $this->get(route('admin.produk.index', ['status' => 'nonaktif']))
        ->assertOk()
        ->assertSee('Remote AC lama')
        ->assertDontSee('Remote AC baru');
});

test('daftar produk diurutkan menurut kategori lalu nama', function () {
    Produk::factory()->create(['nama' => 'Pipa B', 'kategori' => KategoriProduk::Pipa]);
    Produk::factory()->create(['nama' => 'Tabung R32', 'kategori' => KategoriProduk::Freon]);
    Produk::factory()->create(['nama' => 'Pipa A', 'kategori' => KategoriProduk::Pipa]);

    expect(app(ProdukService::class)->daftar()->pluck('nama')->all())->toBe(['Tabung R32', 'Pipa A', 'Pipa B']);
});

test('daftar produk menandai stok habis dan stok menipis', function () {
    Produk::factory()->habis()->create();
    Produk::factory()->create(['stok' => 2]);

    $this->get(route('admin.produk.index'))
        ->assertOk()
        ->assertSee('Habis')
        ->assertSee('Sisa 2');
});

test('form tambah produk menampilkan bagian unit AC, harga, foto, dan pilihan kategori', function () {
    $this->get(route('admin.produk.create'))
        ->assertOk()
        ->assertSee('Khusus unit AC')
        ->assertSee('Harga dan stok')
        ->assertSee('Unggah foto')
        ->assertSee('<option value="unit-ac"', false)
        ->assertSee('enctype="multipart/form-data"', false)
        ->assertSee('Simpan produk');
});

test('produk baru tersimpan lewat store beserta slug dan pesan berhasil', function () {
    $this->post(route('admin.produk.store'), dataProdukValid())
        ->assertRedirect(route('admin.produk.index'))
        ->assertSessionHas('status', 'Produk Selang pembuangan AC berhasil ditambahkan.');

    expect(Produk::sole())
        ->slug->toBe('selang-pembuangan-ac')
        ->kategori->toBe(KategoriProduk::Aksesoris)
        ->harga->toBe(25000)
        ->stok->toBe(10)
        ->satuan->toBe('meter')
        ->aktif->toBeTrue()
        ->merek->toBeNull()
        ->gambar->toBeNull();
});

test('unit AC tersimpan dengan merek, model, dan spesifikasi per baris', function () {
    $this->post(route('admin.produk.store'), dataProdukValid([
        'nama' => 'Flash Inverter 0,5 PK',
        'kategori' => 'unit-ac',
        'merek' => 'Daikin',
        'model' => 'FTKQ15UVM4',
        'spesifikasi' => "Daya 340 watt\n\n  Freon   R32 ",
    ]))->assertSessionHasNoErrors();

    expect(Produk::sole())
        ->kategori->toBe(KategoriProduk::UnitAc)
        ->merek->toBe('Daikin')
        ->model->toBe('FTKQ15UVM4')
        ->spesifikasi->toBe(['Daya 340 watt', 'Freon R32']);
});

test('kolom khusus unit AC dikosongkan saat kategori diganti menjadi komponen', function () {
    $produk = Produk::factory()->unitAc()->create();

    $this->put(route('admin.produk.update', $produk), dataProdukValid([
        'kategori' => 'freon',
        'merek' => $produk->merek,
        'model' => $produk->model,
        'spesifikasi' => implode("\n", $produk->spesifikasi),
    ]))->assertSessionHasNoErrors();

    expect($produk->fresh())
        ->kategori->toBe(KategoriProduk::Freon)
        ->merek->toBeNull()
        ->model->toBeNull()
        ->spesifikasi->toBeNull();
});

test('slug dibuat unik bila nama menghasilkan slug yang sama', function () {
    Produk::factory()->create(['nama' => 'Remote AC', 'slug' => 'remote-ac']);

    $this->post(route('admin.produk.store'), dataProdukValid(['nama' => 'Remote  AC!']))
        ->assertSessionHasNoErrors();

    expect(Produk::where('nama', 'Remote AC!')->value('slug'))->toBe('remote-ac-2');
});

test('store menolak data yang tidak valid dengan pesan yang jelas', function (array $timpa, string $kolom, string $pesan) {
    Produk::factory()->create(['nama' => 'Remote AC universal']);

    $this->post(route('admin.produk.store'), dataProdukValid($timpa))
        ->assertSessionHasErrors([$kolom => $pesan]);

    expect(Produk::count())->toBe(1);
})->with([
    'nama kosong' => [['nama' => ''], 'nama', 'Isi nama produk.'],
    'nama sudah dipakai' => [['nama' => 'Remote AC universal'], 'nama', 'Nama produk ini sudah ada.'],
    'nama lebih dari 150 huruf' => [['nama' => str_repeat('a', 151)], 'nama', 'Nama produk maksimal 150 huruf.'],
    'kategori kosong' => [['kategori' => ''], 'kategori', 'Pilih kategori produk.'],
    'kategori tidak dikenal' => [['kategori' => 'mobil'], 'kategori', 'Kategori yang dipilih tidak dikenal.'],
    'deskripsi kosong' => [['deskripsi' => ''], 'deskripsi', 'Isi deskripsi produk.'],
    'unit AC tanpa merek' => [['kategori' => 'unit-ac', 'merek' => ''], 'merek', 'Isi merek untuk unit AC.'],
    'spesifikasi lebih dari 6 baris' => [['kategori' => 'unit-ac', 'merek' => 'LG', 'spesifikasi' => implode("\n", range(1, 7))], 'spesifikasi', 'Spesifikasi maksimal 6 baris.'],
    'harga kosong' => [['harga' => ''], 'harga', 'Isi harga produk.'],
    'harga nol' => [['harga' => 0], 'harga', 'Harga minimal Rp1.'],
    'harga memakai titik' => [['harga' => '4.250.000'], 'harga', 'Harga harus berupa angka tanpa titik atau koma.'],
    'stok kosong' => [['stok' => ''], 'stok', 'Isi jumlah stok.'],
    'stok negatif' => [['stok' => -1], 'stok', 'Stok tidak boleh kurang dari 0.'],
]);

test('foto yang diunggah tersimpan di disk dan path-nya masuk ke database', function () {
    $this->post(route('admin.produk.store'), dataProdukValid(['gambar' => fotoProdukPalsu()]))
        ->assertSessionHasNoErrors();

    $produk = Produk::sole();

    expect($produk->gambar)->toStartWith('produk/');
    expect($produk->url_gambar)->toBe('storage/'.$produk->gambar);
    Storage::disk('public')->assertExists($produk->gambar);
});

test('store menolak foto yang bukan gambar atau terlalu besar', function (UploadedFile $berkas, string $pesan) {
    $this->post(route('admin.produk.store'), dataProdukValid(['gambar' => $berkas]))
        ->assertSessionHasErrors(['gambar' => $pesan]);

    expect(Produk::count())->toBe(0);
})->with([
    'berkas pdf' => [fn () => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'), 'Berkas harus berupa gambar.'],
    'gambar lebih dari 2 MB' => [fn () => fotoProdukPalsu('besar.png', 3 * 1024 * 1024), 'Ukuran foto maksimal 2 MB.'],
]);

test('form ubah produk menampilkan data yang tersimpan', function () {
    $produk = Produk::factory()->unitAc()->create(['nama' => 'Standard 1 PK', 'spesifikasi' => ['Daya 800 watt', 'Freon R32']]);

    $this->get(route('admin.produk.edit', $produk))
        ->assertOk()
        ->assertSee('value="Standard 1 PK"', false)
        ->assertSee("Daya 800 watt\nFreon R32", false)
        ->assertSee('Simpan perubahan');
});

test('update mengubah data produk tanpa mengubah slug', function () {
    $produk = Produk::factory()->create(['nama' => 'Nama Lama', 'slug' => 'nama-lama', 'harga' => 10000]);

    $this->put(route('admin.produk.update', $produk), dataProdukValid(['nama' => 'Nama Baru', 'harga' => 12500, 'stok' => 0, 'aktif' => null]))
        ->assertRedirect(route('admin.produk.index'))
        ->assertSessionHas('status', 'Produk Nama Baru berhasil diperbarui.');

    expect($produk->fresh())
        ->nama->toBe('Nama Baru')
        ->slug->toBe('nama-lama')
        ->harga->toBe(12500)
        ->stok->toBe(0)
        ->aktif->toBeFalse();
});

test('update boleh memakai nama produk itu sendiri tetapi tidak nama produk lain', function () {
    $produk = Produk::factory()->create(['nama' => 'Remote AC']);
    Produk::factory()->create(['nama' => 'Bracket outdoor']);

    $this->put(route('admin.produk.update', $produk), dataProdukValid(['nama' => 'Remote AC']))
        ->assertSessionHasNoErrors();

    $this->put(route('admin.produk.update', $produk), dataProdukValid(['nama' => 'Bracket outdoor']))
        ->assertSessionHasErrors(['nama' => 'Nama produk ini sudah ada.']);
});

test('mengganti foto menghapus foto lama dari disk', function () {
    Storage::disk('public')->put('produk/lama.png', 'lama');
    $produk = Produk::factory()->create(['gambar' => 'produk/lama.png']);

    $this->put(route('admin.produk.update', $produk), dataProdukValid(['gambar' => fotoProdukPalsu('baru.png')]))
        ->assertSessionHasNoErrors();

    $baru = $produk->fresh()->gambar;

    expect($baru)->not->toBe('produk/lama.png');
    Storage::disk('public')->assertMissing('produk/lama.png');
    Storage::disk('public')->assertExists($baru);
});

test('update tanpa foto baru mempertahankan foto yang ada', function () {
    Storage::disk('public')->put('produk/lama.png', 'lama');
    $produk = Produk::factory()->create(['gambar' => 'produk/lama.png']);

    $this->put(route('admin.produk.update', $produk), dataProdukValid())
        ->assertSessionHasNoErrors();

    expect($produk->fresh()->gambar)->toBe('produk/lama.png');
    Storage::disk('public')->assertExists('produk/lama.png');
});

test('centang hapus foto menghapus berkas dan mengosongkan kolom', function () {
    Storage::disk('public')->put('produk/lama.png', 'lama');
    $produk = Produk::factory()->create(['gambar' => 'produk/lama.png']);

    $this->put(route('admin.produk.update', $produk), dataProdukValid(['hapus_gambar' => '1']))
        ->assertSessionHasNoErrors();

    expect($produk->fresh()->gambar)->toBeNull();
    Storage::disk('public')->assertMissing('produk/lama.png');
});

test('destroy menghapus produk beserta fotonya', function () {
    Storage::disk('public')->put('produk/foto.png', 'isi');
    $produk = Produk::factory()->create(['nama' => 'Bracket outdoor', 'gambar' => 'produk/foto.png']);

    $this->delete(route('admin.produk.destroy', $produk))
        ->assertRedirect(route('admin.produk.index'))
        ->assertSessionHas('status', 'Produk Bracket outdoor berhasil dihapus.');

    $this->assertModelMissing($produk);
    Storage::disk('public')->assertMissing('produk/foto.png');
});

test('mengubah produk yang tidak ada menghasilkan 404', function () {
    $this->get(route('admin.produk.edit', 999))->assertNotFound();
});

test('akun non-admin tidak bisa mengelola produk', function () {
    $this->actingAs(User::factory()->create());

    $this->post(route('admin.produk.store'), dataProdukValid())->assertForbidden();

    expect(Produk::count())->toBe(0);
});

test('nama produk di daftar admin tampil sebagai teks, bukan HTML', function () {
    Produk::factory()->create(['nama' => '<script>alert("x")</script>']);

    $this->get(route('admin.produk.index'))
        ->assertOk()
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>alert("x")</script>', false);
});
