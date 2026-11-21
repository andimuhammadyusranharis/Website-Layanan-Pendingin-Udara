<?php

use App\Models\Ulasan;
use App\Models\User;
use App\Services\UlasanService;
use Database\Seeders\UlasanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Halaman publik

test('halaman ulasan membaca ulasan dari database, bukan data dummy', function () {
    Ulasan::factory()->create(['nama_pelanggan' => 'Siti Aminah', 'komentar' => 'Pekerjaannya rapi dan cepat.']);

    $this->get(route('ulasan.index'))
        ->assertOk()
        ->assertSee('Siti Aminah')
        ->assertSee('Pekerjaannya rapi dan cepat.')
        ->assertDontSee('Nurhaedah');
});

test('ulasan yang disembunyikan tidak tampil di halaman publik', function () {
    Ulasan::factory()->create(['nama_pelanggan' => 'Pelanggan Tampil']);
    Ulasan::factory()->tersembunyi()->create(['nama_pelanggan' => 'Pelanggan Tersembunyi']);

    $this->get(route('ulasan.index'))
        ->assertOk()
        ->assertSee('Pelanggan Tampil')
        ->assertDontSee('Pelanggan Tersembunyi')
        ->assertSee('Dari 1 ulasan pelanggan');
});

test('halaman ulasan menampilkan rata-rata dan persen puas dari database', function () {
    Ulasan::factory()->create(['rating' => 5]);
    Ulasan::factory()->create(['rating' => 4]);

    $this->get(route('ulasan.index'))
        ->assertOk()
        ->assertSee('4,5')
        ->assertSee('100% pelanggan puas');
});

test('halaman ulasan menampilkan keadaan kosong bila belum ada ulasan', function () {
    $this->get(route('ulasan.index'))
        ->assertOk()
        ->assertSee('Belum ada ulasan.');
});

// UlasanService

test('ringkasan menghitung rata-rata, persen puas, dan sebaran dari ulasan yang tampil saja', function () {
    foreach ([5, 4, 2, 1] as $rating) {
        Ulasan::factory()->create(['rating' => $rating]);
    }
    Ulasan::factory()->tersembunyi()->create(['rating' => 1]);

    expect(app(UlasanService::class)->ringkasan())->toBe([
        'rata_rata' => 3.0,
        'jumlah' => 4,
        'persen_puas' => 50,
        'sebaran' => [5 => 1, 4 => 1, 3 => 0, 2 => 1, 1 => 1],
    ]);
});

test('ringkasan bernilai nol tanpa error bila belum ada ulasan', function () {
    expect(app(UlasanService::class)->ringkasan())->toBe([
        'rata_rata' => 0.0,
        'jumlah' => 0,
        'persen_puas' => 0,
        'sebaran' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
    ]);
});

test('daftar ulasan tampil diurutkan dari yang terbaru', function () {
    Ulasan::factory()->create(['nama_pelanggan' => 'Lama', 'created_at' => now()->subDay()]);
    Ulasan::factory()->create(['nama_pelanggan' => 'Baru']);

    expect(app(UlasanService::class)->daftarTampil()->pluck('nama_pelanggan')->all())->toBe(['Baru', 'Lama']);
});

test('UlasanService dibuat otomatis oleh container beserta constructor-nya', function () {
    Ulasan::factory()->count(2)->create();

    expect(app(UlasanService::class)->jumlah())->toBe(2);
});

test('UlasanSeeder aman dijalankan berulang', function () {
    $this->seed(UlasanSeeder::class);
    $jumlahAwal = Ulasan::count();

    $this->seed(UlasanSeeder::class);

    expect($jumlahAwal)->toBeGreaterThan(0)
        ->and(Ulasan::count())->toBe($jumlahAwal);
});

// Panel admin

test('daftar ulasan admin menampilkan semua ulasan termasuk yang disembunyikan', function () {
    $this->actingAs(User::factory()->admin()->create());
    Ulasan::factory()->create(['nama_pelanggan' => 'Budi Santoso']);
    Ulasan::factory()->tersembunyi()->create(['nama_pelanggan' => 'Rina Hidayat']);

    $this->get(route('admin.ulasan.index'))
        ->assertOk()
        ->assertSee('Ulasan pelanggan (2)')
        ->assertSee('Budi Santoso')
        ->assertSee('Rina Hidayat');
});

test('daftar ulasan admin bisa dicari dan disaring menurut status', function () {
    $this->actingAs(User::factory()->admin()->create());
    Ulasan::factory()->create(['nama_pelanggan' => 'Budi Santoso']);
    Ulasan::factory()->tersembunyi()->create(['nama_pelanggan' => 'Rina Hidayat']);

    $this->get(route('admin.ulasan.index', ['cari' => 'rina']))
        ->assertOk()
        ->assertSee('Rina Hidayat')
        ->assertDontSee('Budi Santoso');

    $this->get(route('admin.ulasan.index', ['status' => 'tersembunyi']))
        ->assertOk()
        ->assertSee('Rina Hidayat')
        ->assertDontSee('Budi Santoso');
});

test('admin menyembunyikan ulasan lalu ulasan itu hilang dari halaman publik', function () {
    $this->actingAs(User::factory()->admin()->create());
    $ulasan = Ulasan::factory()->create(['komentar' => 'Komentar uji yang harus hilang.']);

    $this->get(route('ulasan.index'))->assertSee('Komentar uji yang harus hilang.');

    $this->patch(route('admin.ulasan.tampilan', $ulasan), ['tampil' => '0'])
        ->assertRedirect(route('admin.ulasan.index'))
        ->assertSessionHas('status');

    expect($ulasan->fresh()->tampil)->toBeFalse();

    $this->get(route('ulasan.index'))->assertDontSee('Komentar uji yang harus hilang.');
});

test('admin menampilkan kembali ulasan yang disembunyikan', function () {
    $this->actingAs(User::factory()->admin()->create());
    $ulasan = Ulasan::factory()->tersembunyi()->create(['komentar' => 'Komentar uji yang harus muncul.']);

    $this->patch(route('admin.ulasan.tampilan', $ulasan), ['tampil' => '1'])
        ->assertSessionHasNoErrors();

    expect($ulasan->fresh()->tampil)->toBeTrue();

    $this->get(route('ulasan.index'))->assertSee('Komentar uji yang harus muncul.');
});

test('mengubah tampilan ulasan menampilkan notifikasi berhasil', function () {
    $this->actingAs(User::factory()->admin()->create());
    $ulasan = Ulasan::factory()->create(['nama_pelanggan' => 'Dewi Lestari']);

    $this->followingRedirects()
        ->patch(route('admin.ulasan.tampilan', $ulasan), ['tampil' => '0'])
        ->assertSee('data-notifikasi="berhasil"', false)
        ->assertSee('Ulasan dari Dewi Lestari sekarang disembunyikan.');
});

test('ubah tampilan menolak isian yang tidak valid', function (array $data) {
    $this->actingAs(User::factory()->admin()->create());
    $ulasan = Ulasan::factory()->create();

    $this->patch(route('admin.ulasan.tampilan', $ulasan), $data)
        ->assertSessionHasErrors('tampil');

    expect($ulasan->fresh()->tampil)->toBeTrue();
})->with([
    'isian kosong' => [[]],
    'bukan nilai ya atau tidak' => [['tampil' => 'mungkin']],
]);

test('mengubah ulasan yang tidak ada menghasilkan 404', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->patch(route('admin.ulasan.tampilan', 999), ['tampil' => '1'])->assertNotFound();
});

test('akun non-admin tidak bisa mengelola ulasan', function () {
    $this->actingAs(User::factory()->create());
    $ulasan = Ulasan::factory()->create();

    $this->get(route('admin.ulasan.index'))->assertForbidden();
    $this->patch(route('admin.ulasan.tampilan', $ulasan), ['tampil' => '0'])->assertForbidden();

    expect($ulasan->fresh()->tampil)->toBeTrue();
});

test('tamu yang belum masuk diarahkan ke halaman masuk', function () {
    $this->get(route('admin.ulasan.index'))->assertRedirect(route('login'));
});
