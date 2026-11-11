<?php

use App\Models\Pelanggan;
use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as AkunGoogle;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['services.google.client_id' => 'id-uji', 'services.google.client_secret' => 'rahasia-uji']);
});

function akunGoogle(string $email, string $id = 'google-123', bool $terverifikasi = true): AkunGoogle
{
    return (new AkunGoogle)
        ->setRaw(['email_verified' => $terverifikasi])
        ->map(['id' => $id, 'name' => 'Budi Santoso', 'email' => $email]);
}

test('tombol google tampil bila kredensial diisi dan hilang bila kosong', function () {
    $this->get('/masuk')->assertOk()->assertSee('Masuk dengan Google');
    $this->get('/daftar')->assertOk()->assertSee('Daftar dengan Google');

    config(['services.google.client_id' => null]);

    $this->get('/masuk')->assertOk()->assertDontSee('Masuk dengan Google');
    $this->get('/auth/google')->assertNotFound();
});

test('pengguna diarahkan ke halaman izin google', function () {
    Socialite::fake('google');

    $this->get('/auth/google')->assertRedirect();
});

test('akun google baru menjadi pelanggan tanpa kata sandi dan langsung masuk', function () {
    Socialite::fake('google', akunGoogle('Budi@Gmail.com'));

    $this->get('/auth/google/callback')->assertRedirect(route('beranda'))->assertSessionHas('status');

    $user = User::firstWhere('email', 'budi@gmail.com');

    expect($user)->not->toBeNull()
        ->and($user->google_id)->toBe('google-123')
        ->and($user->role)->toBe(User::PERAN_PELANGGAN)
        ->and($user->password)->toBeNull()
        ->and($user->pelanggan)->toBeInstanceOf(Pelanggan::class);
    $this->assertAuthenticatedAs($user);
});

test('akun lama dengan email yang sama ditautkan, bukan digandakan', function () {
    $admin = User::factory()->admin()->create(['email' => 'pemilik@gmail.com']);
    Socialite::fake('google', akunGoogle('pemilik@gmail.com'));

    $this->get('/auth/google/callback')->assertRedirect(route('admin.dashboard'));

    expect(User::count())->toBe(1)
        ->and($admin->fresh()->google_id)->toBe('google-123')
        ->and($admin->fresh()->role)->toBe(User::PERAN_ADMIN);
    $this->assertAuthenticatedAs($admin);
});

test('akun yang sudah tertaut dikenali dari id google walau emailnya berubah', function () {
    $user = User::factory()->create(['email' => 'lama@gmail.com']);
    $user->forceFill(['google_id' => 'google-123'])->save();
    Socialite::fake('google', akunGoogle('baru@gmail.com'));

    $this->get('/auth/google/callback')->assertRedirect(route('beranda'));

    expect(User::count())->toBe(1);
    $this->assertAuthenticatedAs($user);
});

test('email google yang belum terverifikasi ditolak', function () {
    User::factory()->admin()->create(['email' => 'pemilik@gmail.com']);
    Socialite::fake('google', akunGoogle('pemilik@gmail.com', terverifikasi: false));

    $this->get('/auth/google/callback')->assertRedirect('/masuk')->assertSessionHasErrors('google');

    $this->assertGuest();
});

test('teknisi yang belum disetujui tidak bisa masuk lewat google', function () {
    $teknisi = Teknisi::factory()->create();
    Socialite::fake('google', akunGoogle($teknisi->user->email));

    $this->get('/auth/google/callback')
        ->assertRedirect('/masuk')
        ->assertSessionHasErrors(['google' => 'Akun teknisi Anda masih menunggu persetujuan admin.']);

    $this->assertGuest();
});

test('izin yang dibatalkan di google kembali ke halaman masuk dengan pesan', function () {
    Socialite::fake('google', fn () => throw new RuntimeException('access_denied'));

    $this->get('/auth/google/callback')->assertRedirect('/masuk')->assertSessionHasErrors('google');

    $this->assertGuest();
});

test('akun tanpa kata sandi tidak bisa masuk lewat form kata sandi', function () {
    $user = User::factory()->create(['email' => 'budi@gmail.com']);
    $user->forceFill(['password' => null, 'google_id' => 'google-123'])->save();

    $this->post('/masuk', ['identitas' => 'budi@gmail.com', 'password' => 'password'])
        ->assertSessionHasErrors('identitas');

    $this->assertGuest();
});
