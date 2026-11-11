<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Akun uji tetap (bukan acak) supaya semua anggota tim melihat data yang sama.
 * Semua akun memakai kata sandi "password".
 *
 * | Peran     | Email                          | No. WhatsApp  | Catatan                          |
 * |-----------|--------------------------------|---------------|----------------------------------|
 * | Admin     | admin@tigaputrateknik.test     | 081234567890  | Juga punya profil teknisi pemilik |
 * | Teknisi   | syamsul@tigaputrateknik.test   | 081311112222  | Mitra, aktif                     |
 * | Teknisi   | rizky@tigaputrateknik.test     | 081333334444  | Mitra, menunggu persetujuan      |
 * | Pelanggan | pelanggan@tigaputrateknik.test | 085255556666  | Alamat sudah diisi               |
 */
class AkunSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Pak Abdul Haris',
            'email' => 'admin@tigaputrateknik.test',
            'no_telepon' => '081234567890',
        ]);
        Teknisi::factory()->pemilik()->for($admin)->create(['domisili' => 'Pangkajene']);

        Teknisi::factory()->aktif()->for(User::factory()->teknisi()->state([
            'name' => 'Pak Syamsul',
            'email' => 'syamsul@tigaputrateknik.test',
            'no_telepon' => '081311112222',
        ]))->create(['domisili' => 'Bungoro']);

        Teknisi::factory()->for(User::factory()->teknisi()->state([
            'name' => 'Ahmad Rizky',
            'email' => 'rizky@tigaputrateknik.test',
            'no_telepon' => '081333334444',
        ]))->create(['domisili' => 'Minasatene', 'sertifikasi' => 'Sertifikasi BNSP Pendingin']);

        Pelanggan::factory()->for(User::factory()->state([
            'name' => 'Raihan Nur Faiz',
            'email' => 'pelanggan@tigaputrateknik.test',
            'no_telepon' => '085255556666',
        ]))->create([
            'alamat' => 'Jl. Sultan Hasanuddin No. 12, Pangkajene',
            'patokan' => 'Depan masjid raya',
        ]);
    }
}
