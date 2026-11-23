<?php

use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => User::PERAN_ADMIN]);
    $this->akunTeknisi = User::factory()->create(['role' => User::PERAN_TEKNISI]);
    $this->teknisi = Teknisi::factory()->create(['id_user' => $this->akunTeknisi->id]);
});

it('menyetujui teknisi', function () {
    $this->actingAs($this->admin)
        ->patch(route('admin.teknisi.setujui', $this->teknisi->id_teknisi))
        ->assertRedirect(route('admin.teknisi.index'))
        ->assertSessionHas('status');

    $teknisi = $this->teknisi->fresh();

    expect($teknisi->status)->toBe(Teknisi::STATUS_AKTIF)
        ->and($teknisi->disetujui_pada)->not->toBeNull();
});

it('menolak teknisi', function () {
    $this->actingAs($this->admin)
        ->patch(route('admin.teknisi.tolak', $this->teknisi->id_teknisi))
        ->assertRedirect(route('admin.teknisi.index'));

    $teknisi = $this->teknisi->fresh();

    expect($teknisi->status)->toBe(Teknisi::STATUS_NONAKTIF)
        ->and($teknisi->disetujui_pada)->toBeNull();
});

it('mengembalikan 404 bila teknisi tidak ditemukan', function () {
    $this->actingAs($this->admin)
        ->patch(route('admin.teknisi.setujui', 99999))
        ->assertNotFound();

    $this->actingAs($this->admin)
        ->patch(route('admin.teknisi.tolak', 99999))
        ->assertNotFound();
});

it('menolak non-admin', function () {
    $pelanggan = User::factory()->create(['role' => User::PERAN_PELANGGAN]);

    $this->actingAs($pelanggan)
        ->patch(route('admin.teknisi.setujui', $this->teknisi->id_teknisi))
        ->assertForbidden();

    expect($this->teknisi->fresh()->status)->toBe(Teknisi::STATUS_MENUNGGU);
});

it('mengarahkan tamu ke halaman masuk', function () {
    $this->patch(route('admin.teknisi.setujui', $this->teknisi->id_teknisi))
        ->assertRedirect('/masuk');
});

it('hanya mengizinkan teknisi masuk setelah disetujui', function () {
    expect($this->akunTeknisi->fresh()->bolehMasuk())->toBeFalse();

    $this->actingAs($this->admin)
        ->patch(route('admin.teknisi.setujui', $this->teknisi->id_teknisi));

    expect($this->akunTeknisi->fresh()->bolehMasuk())->toBeTrue();
});
