<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => User::PERAN_ADMIN]);
});

it('menampilkan daftar pelanggan', function () {
    User::factory()->create(['role' => User::PERAN_PELANGGAN, 'name' => 'Budi Santoso']);

    $this->actingAs($this->admin)
        ->get(route('admin.pelanggan.index'))
        ->assertOk()
        ->assertSee('Budi Santoso');
});

it('tidak menampilkan akun selain pelanggan', function () {
    User::factory()->create(['role' => User::PERAN_TEKNISI, 'name' => 'Teknisi Rahasia']);

    $this->actingAs($this->admin)
        ->get(route('admin.pelanggan.index'))
        ->assertOk()
        ->assertDontSee('Teknisi Rahasia');
});

it('mencari pelanggan berdasarkan nama', function () {
    User::factory()->create(['role' => User::PERAN_PELANGGAN, 'name' => 'Budi Santoso']);
    User::factory()->create(['role' => User::PERAN_PELANGGAN, 'name' => 'Citra Lestari']);

    $this->actingAs($this->admin)
        ->get(route('admin.pelanggan.index', ['cari' => 'Budi']))
        ->assertOk()
        ->assertSee('Budi Santoso')
        ->assertDontSee('Citra Lestari');
});

it('menampilkan detail pelanggan', function () {
    $pelanggan = User::factory()->create(['role' => User::PERAN_PELANGGAN, 'name' => 'Budi Santoso']);

    $this->actingAs($this->admin)
        ->get(route('admin.pelanggan.show', $pelanggan->id))
        ->assertOk()
        ->assertSee('Budi Santoso');
});

it('mengembalikan 404 bila pelanggan tidak ditemukan', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.pelanggan.show', 99999))
        ->assertNotFound();
});

it('mengembalikan 404 bila id milik akun non-pelanggan', function () {
    $teknisi = User::factory()->create(['role' => User::PERAN_TEKNISI]);

    $this->actingAs($this->admin)
        ->get(route('admin.pelanggan.show', $teknisi->id))
        ->assertNotFound();
});

it('menolak non-admin', function () {
    $pelanggan = User::factory()->create(['role' => User::PERAN_PELANGGAN]);

    $this->actingAs($pelanggan)
        ->get(route('admin.pelanggan.index'))
        ->assertForbidden();
});

it('mengarahkan tamu ke halaman masuk', function () {
    $this->get(route('admin.pelanggan.index'))->assertRedirect('/masuk');
});
