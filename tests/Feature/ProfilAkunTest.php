<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->user = User::factory()->create([
        'role' => User::PERAN_PELANGGAN,
        'no_telepon' => '081234567890',
        'password' => 'passwordlama',
    ]);
});

function dataProfil(array $ubah = []): array
{
    return array_merge([
        'name' => 'Nama Baru',
        'no_telepon' => '+62 812-3456-7899',
        'alamat' => 'Jl. Contoh No. 1, Makassar',
        'patokan' => 'Depan minimarket',
    ], $ubah);
}

function dataPassword(array $ubah = []): array
{
    return array_merge([
        'current_password' => 'passwordlama',
        'password' => 'passwordbaru',
        'password_confirmation' => 'passwordbaru',
    ], $ubah);
}

it('memperbarui profil dan membuat baris pelanggan bila belum ada', function () {
    $this->actingAs($this->user)
        ->put(route('akun.profil.update'), dataProfil())
        ->assertRedirect(route('akun.profil'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'name' => 'Nama Baru',
        'no_telepon' => '081234567899',
    ]);
    $this->assertDatabaseHas('pelanggan', [
        'id_user' => $this->user->id,
        'alamat' => 'Jl. Contoh No. 1, Makassar',
        'patokan' => 'Depan minimarket',
    ]);
});

it('menolak profil dengan data tidak valid', function (array $ubah, string $kolom) {
    $this->actingAs($this->user)
        ->put(route('akun.profil.update'), dataProfil($ubah))
        ->assertSessionHasErrors($kolom);
})->with([
    'nama kosong' => [['name' => ''], 'name'],
    'telepon kosong' => [['no_telepon' => ''], 'no_telepon'],
    'telepon bukan nomor' => [['no_telepon' => 'abc'], 'no_telepon'],
]);

it('menolak nomor telepon yang dipakai akun lain', function () {
    User::factory()->create(['no_telepon' => '081111111111']);

    $this->actingAs($this->user)
        ->put(route('akun.profil.update'), dataProfil(['no_telepon' => '0811-1111-1111']))
        ->assertSessionHasErrors('no_telepon');
});

it('mengizinkan nomor telepon milik sendiri', function () {
    $this->actingAs($this->user)
        ->put(route('akun.profil.update'), dataProfil(['no_telepon' => '081234567890']))
        ->assertSessionHasNoErrors();
});

it('tidak mengubah role walau dikirim lewat form', function () {
    $this->actingAs($this->user)
        ->put(route('akun.profil.update'), dataProfil(['role' => 'admin']));

    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'role' => 'pelanggan',
    ]);
});

it('mengarahkan tamu ke halaman masuk', function () {
    $this->get('/akun/profil')->assertRedirect('/masuk');
    $this->put(route('akun.profil.update'), dataProfil())->assertRedirect('/masuk');
});

it('menolak admin memakai halaman profil pelanggan', function () {
    $admin = User::factory()->create(['role' => User::PERAN_ADMIN]);

    $this->actingAs($admin)
        ->put(route('akun.profil.update'), dataProfil())
        ->assertForbidden();
});

it('mengganti kata sandi', function () {
    $this->actingAs($this->user)
        ->put(route('akun.profil.password'), dataPassword())
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('akun.profil'));

    expect(Hash::check('passwordbaru', $this->user->fresh()->password))->toBeTrue();
});

it('menolak penggantian kata sandi yang tidak valid', function (array $ubah, string $kolom) {
    $this->actingAs($this->user)
        ->put(route('akun.profil.password'), dataPassword($ubah))
        ->assertSessionHasErrors($kolom);
})->with([
    'kata sandi lama salah' => [['current_password' => 'salah'], 'current_password'],
    'kurang dari 8 karakter' => [['password' => 'pendek', 'password_confirmation' => 'pendek'], 'password'],
    'konfirmasi tidak cocok' => [['password_confirmation' => 'beda'], 'password'],
]);

it('menolak akun tanpa kata sandi (akun Google)', function () {
    DB::table('users')->where('id', $this->user->id)->update(['password' => null]);

    $this->actingAs($this->user->fresh())
        ->put(route('akun.profil.password'), dataPassword())
        ->assertForbidden();
});
