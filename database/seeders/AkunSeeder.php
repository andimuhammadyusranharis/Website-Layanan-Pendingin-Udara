<?php

namespace Database\Seeders;

use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Akun admin uji yang tetap (bukan acak) supaya semua anggota tim memakai akun yang sama.
 * Kata sandinya "password". Hanya untuk lingkungan lokal, jangan dijalankan di server produksi.
 *
 * | Peran | Email                      | No. WhatsApp | Catatan                           |
 * |-------|----------------------------|--------------|-----------------------------------|
 * | Admin | admin@tigaputrateknik.test | 081234567890 | Juga punya profil teknisi pemilik |
 *
 * Akun pelanggan dibuat lewat halaman daftar, dan akun teknisi lewat form terpisah.
 */
class AkunSeeder extends Seeder
{
    public function run(): void
    {
        // Seeder ini membuat akun baru, bukan memperbarui. Lewati bila akun admin sudah ada
        // supaya `php artisan migrate --seed` aman dijalankan lagi di database yang sudah terisi.
        if (User::where('email', 'admin@tigaputrateknik.test')->exists()) {
            return;
        }

        $admin = User::factory()->admin()->create([
            'name' => 'Admin Tiga Putra Teknik',
            'email' => 'admin@tigaputrateknik.test',
            'no_telepon' => '081234567890',
        ]);

        Teknisi::factory()->pemilik()->for($admin)->create(['domisili' => 'Pangkajene']);
    }
}
