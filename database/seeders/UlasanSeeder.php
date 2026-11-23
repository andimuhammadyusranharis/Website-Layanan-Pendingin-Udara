<?php

namespace Database\Seeders;

use App\Models\Ulasan;
use Illuminate\Database\Seeder;

class UlasanSeeder extends Seeder
{
    /**
     * Data tetap supaya semua anggota tim melihat ulasan yang sama.
     * updateOrCreate membuat seeder aman dijalankan berulang tanpa menggandakan data.
     */
    public function run(): void
    {
        $daftar = [
            ['nama_pelanggan' => 'Hj. Nurhaedah', 'rating' => 5, 'komentar' => 'Lantai dan dinding dilapisi plastik dulu sebelum dicuci, jadi tidak ada cipratan air kotor. AC langsung dingin lagi.'],
            ['nama_pelanggan' => 'Ahmad Syarif', 'rating' => 5, 'komentar' => 'Datang sesuai jam yang dijanjikan. Biayanya sama dengan estimasi di awal.'],
            ['nama_pelanggan' => 'Drs. Ridwan M.', 'rating' => 5, 'komentar' => 'AC kantor dibersihkan sampai saluran pembuangannya. Bunyinya tidak berisik lagi dan bau apeknya hilang.'],
            ['nama_pelanggan' => 'Wahyu Pratama', 'rating' => 5, 'komentar' => 'Tekanan freon ditunjukkan ke saya sebelum dan sesudah diisi, jadi saya tahu memang kurang.'],
            ['nama_pelanggan' => 'Ibu Sitti Aminah', 'rating' => 4, 'komentar' => 'Pemasangannya rapi. Terlambat sekitar 15 menit karena hujan, tapi dikabari lewat WhatsApp sebelumnya.'],
            ['nama_pelanggan' => 'Muhammad Arifin', 'rating' => 3, 'komentar' => 'Remotenya cocok dan langsung disetel di toko, tapi saya harus menunggu cukup lama sebelum dilayani.'],
        ];

        foreach ($daftar as $data) {
            Ulasan::query()->updateOrCreate(
                ['nama_pelanggan' => $data['nama_pelanggan'], 'komentar' => $data['komentar']],
                ['rating' => $data['rating'], 'tampil' => true],
            );
        }
    }
}
