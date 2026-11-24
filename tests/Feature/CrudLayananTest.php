<?php

use App\Enums\KategoriLayanan;
use App\Models\Layanan;
use App\Models\User;
use App\Services\LayananService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

/**
 * @param  array<string, mixed>  $timpa
 * @return array<string, mixed>
 */
function dataLayananValid(array $timpa = []): array
{
    return array_merge([
        'nama' => 'Servis AC Cassette',
        'ringkasan' => 'Cek dan servis AC cassette kantor',
        'deskripsi' => 'Servis lengkap AC cassette untuk kantor dan ruko, termasuk pengecekan freon.',
        'kategori' => 'cuci-ac',
        'jenis_ac' => 'Cassette',
        'durasi' => '90 sampai 120 menit',
        'fitur' => "Bak air dibersihkan\nKipas dicuci\n\nFilter dicuci",
        'tarif_mulai' => 300000,
        'satuan' => 'unit',
        'keterangan_harga' => 'Mulai dari',
        'catatan_harga' => null,
        'urutan' => 3,
        'bagi_hasil' => 45,
        'aktif' => '1',
    ], $timpa);
}

function fotoPalsu(string $nama = 'foto.png', int $tambahanByte = 0): UploadedFile
{
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

    return UploadedFile::fake()->createWithContent($nama, $png.str_repeat("\0", $tambahanByte));
}

beforeEach(function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->admin()->create());
});

test('daftar layanan membaca data dari database dan bisa dicari', function () {
    Layanan::factory()->create(['nama' => 'Cuci AC Split']);
    Layanan::factory()->create(['nama' => 'Isi Freon R32']);

    $this->get(route('admin.layanan.index'))
        ->assertOk()
        ->assertSee('Layanan (2)')
        ->assertSee('Cuci AC Split')
        ->assertSee('Isi Freon R32');

    $this->get(route('admin.layanan.index', ['cari' => 'freon']))
        ->assertOk()
        ->assertSee('Isi Freon R32')
        ->assertDontSee('Cuci AC Split');
});

test('daftar layanan bisa disaring menurut status', function () {
    Layanan::factory()->create(['nama' => 'Layanan Berjalan']);
    Layanan::factory()->nonaktif()->create(['nama' => 'Layanan Dihentikan']);

    $this->get(route('admin.layanan.index', ['status' => 'nonaktif']))
        ->assertOk()
        ->assertSee('Layanan Dihentikan')
        ->assertDontSee('Layanan Berjalan');
});

test('daftar layanan menampilkan layanan yang perlu survei tanpa error', function () {
    Layanan::factory()->perluSurvei()->create(['nama' => 'AC Sentral Gedung']);

    $this->get(route('admin.layanan.index'))
        ->assertOk()
        ->assertSee('AC Sentral Gedung')
        ->assertSee('Setelah survei');
});

test('form tambah layanan menampilkan semua kolom untuk tampilan pelanggan', function () {
    $this->get(route('admin.layanan.create'))
        ->assertOk()
        ->assertSee('Informasi dasar')
        ->assertSee('Ringkasan untuk kartu')
        ->assertSee('Apa saja yang dikerjakan')
        ->assertSee('Unggah foto')
        ->assertSee('Cuci AC')
        ->assertSee('enctype="multipart/form-data"', false)
        ->assertSee('Simpan layanan');
});

test('input form tersimpan ke database lewat store beserta slug dan poin pekerjaan', function () {
    $this->post(route('admin.layanan.store'), dataLayananValid())
        ->assertRedirect(route('admin.layanan.index'))
        ->assertSessionHas('status');

    $layanan = Layanan::where('nama', 'Servis AC Cassette')->firstOrFail();

    expect($layanan)
        ->slug->toBe('servis-ac-cassette')
        ->kategori->toBe(KategoriLayanan::CuciAc)
        ->tarif_mulai->toBe(300000)
        ->bagi_hasil->toBe(45)
        ->urutan->toBe(3)
        ->aktif->toBeTrue()
        ->fitur->toBe(['Bak air dibersihkan', 'Kipas dicuci', 'Filter dicuci'])
        ->gambar->toBeNull();
});

test('slug dibuat unik bila nama menghasilkan slug yang sama', function () {
    Layanan::factory()->create(['nama' => 'Cuci AC', 'slug' => 'cuci-ac']);

    $this->post(route('admin.layanan.store'), dataLayananValid(['nama' => 'Cuci  AC!']))
        ->assertSessionHasNoErrors();

    expect(Layanan::where('nama', 'Cuci AC!')->value('slug'))->toBe('cuci-ac-2');
});

test('layanan yang perlu survei boleh disimpan tanpa tarif', function () {
    $this->post(route('admin.layanan.store'), dataLayananValid(['perlu_survei' => '1', 'tarif_mulai' => null]))
        ->assertSessionHasNoErrors();

    expect(Layanan::firstOrFail())
        ->perlu_survei->toBeTrue()
        ->tarif_mulai->toBeNull();
});

test('store menolak data yang tidak valid', function (array $timpa, string $kolom) {
    Layanan::factory()->create(['nama' => 'Cuci AC']);

    $this->post(route('admin.layanan.store'), dataLayananValid($timpa))
        ->assertSessionHasErrors($kolom);

    expect(Layanan::count())->toBe(1);
})->with([
    'nama kosong' => [['nama' => ''], 'nama'],
    'nama sudah dipakai' => [['nama' => 'Cuci AC'], 'nama'],
    'ringkasan kosong' => [['ringkasan' => ''], 'ringkasan'],
    'ringkasan terlalu panjang' => [['ringkasan' => str_repeat('a', 161)], 'ringkasan'],
    'deskripsi kosong' => [['deskripsi' => ''], 'deskripsi'],
    'kategori kosong' => [['kategori' => ''], 'kategori'],
    'kategori tidak dikenal' => [['kategori' => 'tidak-ada'], 'kategori'],
    'tarif kosong padahal tidak perlu survei' => [['tarif_mulai' => null], 'tarif_mulai'],
    'tarif bukan angka' => [['tarif_mulai' => 'murah'], 'tarif_mulai'],
    'tarif negatif' => [['tarif_mulai' => -1], 'tarif_mulai'],
    'keterangan harga kosong' => [['keterangan_harga' => ''], 'keterangan_harga'],
    'bagi hasil lebih dari 100' => [['bagi_hasil' => 101], 'bagi_hasil'],
    'poin pekerjaan lebih dari 8' => [['fitur' => implode("\n", range(1, 9))], 'fitur'],
]);

test('foto yang diunggah tersimpan di disk dan path-nya masuk ke database', function () {
    $this->post(route('admin.layanan.store'), dataLayananValid(['gambar' => fotoPalsu()]))
        ->assertSessionHasNoErrors();

    $layanan = Layanan::firstOrFail();

    expect($layanan->gambar)->toStartWith('layanan/')
        ->and($layanan->url_gambar)->toBe('storage/'.$layanan->gambar);

    Storage::disk('public')->assertExists($layanan->gambar);
});

test('store menolak foto yang bukan gambar atau terlalu besar', function (UploadedFile $berkas) {
    $this->post(route('admin.layanan.store'), dataLayananValid(['gambar' => $berkas]))
        ->assertSessionHasErrors('gambar');

    expect(Layanan::count())->toBe(0);
})->with([
    'berkas pdf' => fn () => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
    'gambar lebih dari 2 MB' => fn () => fotoPalsu('besar.png', 3 * 1024 * 1024),
]);

test('form ubah layanan menampilkan data yang tersimpan', function () {
    $layanan = Layanan::factory()->create(['nama' => 'Perbaikan Modul', 'fitur' => ['Cek kapasitor', 'Cek sensor']]);

    $this->get(route('admin.layanan.edit', $layanan))
        ->assertOk()
        ->assertSee('Perbaikan Modul')
        ->assertSee("Cek kapasitor\nCek sensor", false)
        ->assertSee('Simpan perubahan');
});

test('update mengubah data layanan tanpa mengubah slug', function () {
    $layanan = Layanan::factory()->create(['nama' => 'Nama Lama', 'slug' => 'nama-lama', 'tarif_mulai' => 100000]);

    $this->put(route('admin.layanan.update', $layanan), dataLayananValid(['nama' => 'Nama Baru', 'tarif_mulai' => 125000, 'aktif' => null]))
        ->assertRedirect(route('admin.layanan.index'));

    expect($layanan->fresh())
        ->nama->toBe('Nama Baru')
        ->slug->toBe('nama-lama')
        ->tarif_mulai->toBe(125000)
        ->aktif->toBeFalse();
});

test('update boleh memakai nama layanan itu sendiri tetapi tidak nama layanan lain', function () {
    $layanan = Layanan::factory()->create(['nama' => 'Cuci AC']);
    Layanan::factory()->create(['nama' => 'Isi Freon']);

    $this->put(route('admin.layanan.update', $layanan), dataLayananValid(['nama' => 'Cuci AC']))
        ->assertSessionHasNoErrors();

    $this->put(route('admin.layanan.update', $layanan), dataLayananValid(['nama' => 'Isi Freon']))
        ->assertSessionHasErrors('nama');
});

test('mengganti foto menghapus foto lama dari disk', function () {
    Storage::disk('public')->put('layanan/lama.png', 'lama');
    $layanan = Layanan::factory()->create(['gambar' => 'layanan/lama.png']);

    $this->put(route('admin.layanan.update', $layanan), dataLayananValid(['gambar' => fotoPalsu('baru.png')]))
        ->assertSessionHasNoErrors();

    $baru = $layanan->fresh()->gambar;

    expect($baru)->not->toBe('layanan/lama.png');
    Storage::disk('public')->assertMissing('layanan/lama.png');
    Storage::disk('public')->assertExists($baru);
});

test('update tanpa foto baru mempertahankan foto yang ada', function () {
    Storage::disk('public')->put('layanan/lama.png', 'lama');
    $layanan = Layanan::factory()->create(['gambar' => 'layanan/lama.png']);

    $this->put(route('admin.layanan.update', $layanan), dataLayananValid())
        ->assertSessionHasNoErrors();

    expect($layanan->fresh()->gambar)->toBe('layanan/lama.png');
    Storage::disk('public')->assertExists('layanan/lama.png');
});

test('centang hapus foto menghapus berkas dan mengosongkan kolom', function () {
    Storage::disk('public')->put('layanan/lama.png', 'lama');
    $layanan = Layanan::factory()->create(['gambar' => 'layanan/lama.png']);

    $this->put(route('admin.layanan.update', $layanan), dataLayananValid(['hapus_gambar' => '1']))
        ->assertSessionHasNoErrors();

    expect($layanan->fresh()->gambar)->toBeNull();
    Storage::disk('public')->assertMissing('layanan/lama.png');
});

test('destroy menghapus layanan beserta fotonya', function () {
    Storage::disk('public')->put('layanan/foto.png', 'isi');
    $layanan = Layanan::factory()->create(['gambar' => 'layanan/foto.png']);

    $this->delete(route('admin.layanan.destroy', $layanan))
        ->assertRedirect(route('admin.layanan.index'));

    $this->assertDatabaseMissing('layanan', ['id_layanan' => $layanan->id_layanan]);
    Storage::disk('public')->assertMissing('layanan/foto.png');
});

test('mengubah layanan yang tidak ada menghasilkan 404', function () {
    $this->get(route('admin.layanan.edit', 999))->assertNotFound();
});

test('akun non-admin tidak bisa mengelola layanan', function () {
    $this->actingAs(User::factory()->create());

    $this->post(route('admin.layanan.store'), dataLayananValid())->assertForbidden();

    expect(Layanan::count())->toBe(0);
});

test('LayananService dibuat otomatis oleh container beserta constructor-nya', function () {
    $service = app(LayananService::class);

    $layanan = $service->simpan(dataLayananValid(['fitur' => ['Satu', 'Dua']]));

    expect($service->jumlah())->toBe(1)
        ->and($layanan)->toBeInstanceOf(Layanan::class)
        ->and($layanan->slug)->toBe('servis-ac-cassette');
});

test('daftar layanan diurutkan menurut kolom urutan', function () {
    Layanan::factory()->create(['nama' => 'Kedua', 'urutan' => 2]);
    Layanan::factory()->create(['nama' => 'Pertama', 'urutan' => 1]);

    expect(app(LayananService::class)->daftar()->pluck('nama')->all())->toBe(['Pertama', 'Kedua']);
});

test('panel admin memuat dialog konfirmasi dan tombol hapus memakai varian bahaya', function () {
    Layanan::factory()->create(['nama' => 'Cuci AC']);

    $this->get(route('admin.layanan.index'))
        ->assertOk()
        ->assertSee('data-dialog-konfirmasi', false)
        ->assertSee('data-konfirmasi="Hapus layanan Cuci AC? Tindakan ini tidak bisa dibatalkan."', false)
        ->assertSee('data-konfirmasi-judul="Hapus layanan?"', false)
        ->assertSee('data-konfirmasi-varian="bahaya"', false);
});

test('menambah, mengubah, dan menghapus layanan menampilkan notifikasi berhasil', function () {
    $this->followingRedirects()
        ->post(route('admin.layanan.store'), dataLayananValid(['nama' => 'Servis Baru']))
        ->assertSee('data-notifikasi="berhasil"', false)
        ->assertSee('Layanan Servis Baru berhasil ditambahkan.');

    $layanan = Layanan::where('nama', 'Servis Baru')->firstOrFail();

    $this->followingRedirects()
        ->put(route('admin.layanan.update', $layanan), dataLayananValid(['nama' => 'Servis Diubah']))
        ->assertSee('Layanan Servis Diubah berhasil diperbarui.');

    $this->followingRedirects()
        ->delete(route('admin.layanan.destroy', $layanan->fresh()))
        ->assertSee('Layanan Servis Diubah berhasil dihapus.');
});

test('notifikasi gagal memakai peran alert dan tidak hilang sendiri', function () {
    $this->withSession(['galat' => 'Layanan tidak bisa dihapus.'])
        ->get(route('admin.layanan.index'))
        ->assertSee('data-notifikasi="galat"', false)
        ->assertSee('role="alert"', false)
        ->assertSee('Layanan tidak bisa dihapus.');
});

test('halaman admin tanpa pesan tidak menampilkan notifikasi', function () {
    $this->get(route('admin.layanan.index'))
        ->assertOk()
        ->assertDontSee('data-notifikasi=', false);
});
