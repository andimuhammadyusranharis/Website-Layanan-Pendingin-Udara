<?php

use App\Models\Pelanggan;
use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

test('halaman masuk dan daftar bisa dibuka tamu', function () {
    $this->get('/masuk')->assertOk()->assertSee('Masuk ke Akun Anda')->assertDontSee('name="peran"', false);
    $this->get('/daftar')->assertOk()->assertSee('Buat Akun');
    $this->get('/login')->assertRedirect('/masuk');
});

test('pelanggan baru bisa daftar dan langsung masuk dengan profil pelanggan', function () {
    $this->post('/daftar', [
        'name' => '  Raihan   Nur Faiz ',
        'email' => 'Raihan@Email.com',
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
        'setuju' => '1',
    ])->assertRedirect(route('beranda'))->assertSessionHas('status');

    $user = User::firstWhere('email', 'raihan@email.com');

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Raihan Nur Faiz')
        ->and($user->role)->toBe(User::PERAN_PELANGGAN)
        ->and($user->pelanggan)->toBeInstanceOf(Pelanggan::class);
    $this->assertAuthenticatedAs($user);
});

test('daftar menolak email terpakai, sandi pendek, konfirmasi beda, dan tanpa persetujuan', function () {
    User::factory()->create(['email' => 'ada@email.com']);

    $this->from('/daftar')->post('/daftar', [
        'name' => 'Budi',
        'email' => 'ada@email.com',
        'password' => 'pendek',
        'password_confirmation' => 'lain',
    ])->assertRedirect('/daftar')->assertSessionHasErrors(['email', 'password', 'setuju']);

    $this->assertGuest();
});

test('daftar tidak bisa dipakai untuk membuat akun admin', function () {
    $this->post('/daftar', [
        'name' => 'Penyusup',
        'email' => 'penyusup@email.com',
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
        'setuju' => '1',
        'role' => 'admin',
    ]);

    expect(User::firstWhere('email', 'penyusup@email.com')->role)->toBe(User::PERAN_PELANGGAN);
});

test('masuk bisa memakai email atau nomor whatsapp dengan format apa pun', function (string $identitas) {
    $user = User::factory()->denganTelepon('081234567890')->create(['email' => 'budi@email.com']);

    $this->post('/masuk', ['identitas' => $identitas, 'password' => 'password'])
        ->assertRedirect(route('beranda'));

    $this->assertAuthenticatedAs($user);
})->with(['budi@email.com', 'BUDI@email.com', '081234567890', '+62 812-3456-7890']);

test('admin diarahkan ke dashboard setelah masuk', function () {
    $admin = User::factory()->admin()->create();

    $this->post('/masuk', ['identitas' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.dashboard'));
});

test('masuk gagal bila kata sandi salah', function () {
    $admin = User::factory()->admin()->create();

    $this->post('/masuk', ['identitas' => $admin->email, 'password' => 'salah'])
        ->assertSessionHasErrors(['identitas' => 'Nomor WhatsApp, email, atau kata sandi salah.']);

    $this->assertGuest();
});

test('peran yang dikirim lewat form diabaikan', function () {
    $pelanggan = User::factory()->create();

    $this->post('/masuk', ['peran' => 'admin', 'identitas' => $pelanggan->email, 'password' => 'password'])
        ->assertRedirect(route('beranda'));

    $this->get('/admin')->assertForbidden();
});

test('teknisi hanya bisa masuk setelah disetujui admin', function () {
    $menunggu = Teknisi::factory()->create();
    $aktif = Teknisi::factory()->aktif()->create();

    $this->post('/masuk', ['identitas' => $menunggu->user->email, 'password' => 'password'])
        ->assertSessionHasErrors(['identitas' => 'Akun teknisi Anda masih menunggu persetujuan admin.']);
    $this->assertGuest();

    $this->post('/masuk', ['identitas' => $aktif->user->email, 'password' => 'password'])
        ->assertRedirect();
    $this->assertAuthenticatedAs($aktif->user);
});

test('masuk dikunci sementara setelah 5 kali gagal', function () {
    $user = User::factory()->create(['email' => 'budi@email.com']);
    RateLimiter::clear('budi@email.com|127.0.0.1');

    foreach (range(1, 5) as $percobaan) {
        $this->post('/masuk', ['identitas' => 'budi@email.com', 'password' => 'salah']);
    }

    $this->post('/masuk', ['identitas' => 'budi@email.com', 'password' => 'password'])
        ->assertSessionHasErrors('identitas');

    $this->assertGuest();
});

test('pengguna bisa keluar', function () {
    $this->actingAs(User::factory()->create())
        ->post('/keluar')
        ->assertRedirect(route('beranda'));

    $this->assertGuest();
});

test('halaman admin hanya untuk admin', function () {
    $this->get('/admin')->assertRedirect('/masuk');

    $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();

    $this->actingAs(User::factory()->admin()->create())->get('/admin')->assertOk();
});

test('pengguna yang sudah masuk tidak bisa membuka halaman masuk lagi', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('/masuk')
        ->assertRedirect(route('admin.dashboard'));
});
