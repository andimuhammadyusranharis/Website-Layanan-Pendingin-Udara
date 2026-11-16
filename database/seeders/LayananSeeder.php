<?php

namespace Database\Seeders;

use App\Enums\KategoriLayanan;
use App\Models\Layanan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LayananSeeder extends Seeder
{
    /**
     * Foto tidak diisi di sini. Admin mengunggahnya lewat form layanan.
     */
    public function run(): void
    {
        $daftar = [
            [
                'nama' => 'Cuci AC',
                'ringkasan' => 'Bersihkan unit indoor dan outdoor',
                'deskripsi' => 'Unit dalam dan unit luar dibersihkan. Lantai dan dinding dilapisi plastik supaya air kotor tidak menciprat. Cocok untuk AC yang kurang dingin, berbau, atau meneteskan air.',
                'kategori' => KategoriLayanan::CuciAc,
                'jenis_ac' => 'Split dinding 0,5 sampai 2,5 PK',
                'durasi' => '45 sampai 60 menit',
                'fitur' => ['Filter, kipas, dan sirip pendingin dicuci', 'Unit luar disemprot dan arus listrik dicek', 'Saluran pembuangan air dibersihkan'],
                'satuan' => 'unit',
                'tarif_mulai' => 75000,
                'keterangan_harga' => 'Mulai dari',
                'catatan_harga' => null,
                'perlu_survei' => false,
                'bagi_hasil' => 50,
                'aktif' => true,
            ],
            [
                'nama' => 'Perbaikan',
                'ringkasan' => 'Cek dan perbaiki kerusakan AC',
                'deskripsi' => 'Untuk AC yang mati total, lampunya berkedip, atau unit luarnya tidak menyala. Teknisi memeriksa penyebabnya lebih dulu, lalu menyampaikan biaya perbaikan sebelum dikerjakan.',
                'kategori' => KategoriLayanan::PerbaikanFreon,
                'jenis_ac' => 'Semua AC split',
                'durasi' => '60 sampai 90 menit',
                'fitur' => ['Kapasitor diganti bila rusak', 'Sensor suhu dan layar diperiksa', 'Kabel luar disambung ulang dan dibungkus'],
                'satuan' => null,
                'tarif_mulai' => 100000,
                'keterangan_harga' => 'Biaya pengecekan',
                'catatan_harga' => 'Belum termasuk suku cadang',
                'perlu_survei' => false,
                'bagi_hasil' => 40,
                'aktif' => true,
            ],
            [
                'nama' => 'Pasang baru',
                'ringkasan' => 'Pasang unit AC baru beserta pipa',
                'deskripsi' => 'Pemasangan AC baru lengkap dengan pipa dan kabel. Unit dipasang lurus, pipa divakum sebelum freon dialirkan, lalu hasil akhir dirapikan.',
                'kategori' => KategoriLayanan::PasangBongkar,
                'jenis_ac' => 'Split dinding',
                'durasi' => '2 sampai 3 jam',
                'fitur' => ['Unit dipasang lurus dan rapi', 'Pipa divakum agar tidak ada udara dan uap air', 'Uji nyala dan suhu sebelum selesai'],
                'satuan' => 'unit',
                'tarif_mulai' => 350000,
                'keterangan_harga' => 'Pasang mulai dari',
                'catatan_harga' => null,
                'perlu_survei' => false,
                'bagi_hasil' => 40,
                'aktif' => true,
            ],
            [
                'nama' => 'Bongkar pasang',
                'ringkasan' => 'Pindahkan unit AC ke lokasi lain',
                'deskripsi' => 'Untuk AC bekas atau pindah ruangan. Freon ditampung dulu saat unit dibongkar, lalu unit dipasang kembali di lokasi baru.',
                'kategori' => KategoriLayanan::PasangBongkar,
                'jenis_ac' => 'Split dinding',
                'durasi' => '2 sampai 3 jam',
                'fitur' => ['Freon ditampung dulu saat AC dibongkar', 'Pipa dibungkus rapi', 'Unit dipasang ulang dan diuji'],
                'satuan' => 'unit',
                'tarif_mulai' => 250000,
                'keterangan_harga' => 'Pasang mulai dari',
                'catatan_harga' => 'Bongkar saja Rp100.000',
                'perlu_survei' => false,
                'bagi_hasil' => 40,
                'aktif' => true,
            ],
            [
                'nama' => 'Isi freon',
                'ringkasan' => 'Tambah atau isi ulang freon',
                'deskripsi' => 'Untuk AC yang kurang dingin karena freon berkurang. Sambungan pipa dicek dulu supaya freon tidak bocor lagi.',
                'kategori' => KategoriLayanan::PerbaikanFreon,
                'jenis_ac' => 'Semua AC split',
                'durasi' => '30 sampai 45 menit',
                'fitur' => ['Sambungan pipa dicek kebocorannya', 'Tekanan freon diukur sebelum dan sesudah diisi', 'Tersedia freon R32, R410A, dan R22'],
                'satuan' => 'paket',
                'tarif_mulai' => 150000,
                'keterangan_harga' => 'Mulai dari',
                'catatan_harga' => 'R32, R410A, R22',
                'perlu_survei' => false,
                'bagi_hasil' => 40,
                'aktif' => true,
            ],
            [
                'nama' => 'Konsultasi dan survei',
                'ringkasan' => 'Untuk AC central, multi split, VRF, dan ducted',
                'deskripsi' => 'Jenis AC ini perlu dilihat langsung dulu. Teknisi datang memeriksa unit dan ruangan, lalu biaya pekerjaan disampaikan setelah survei.',
                'kategori' => KategoriLayanan::Komersial,
                'jenis_ac' => 'Central, multi split, VRF, ducted',
                'durasi' => null,
                'fitur' => ['Jadwal survei disepakati lewat WhatsApp', 'Biaya pekerjaan disampaikan setelah survei'],
                'satuan' => null,
                'tarif_mulai' => null,
                'keterangan_harga' => 'Biaya',
                'catatan_harga' => null,
                'perlu_survei' => true,
                'bagi_hasil' => 50,
                'aktif' => true,
            ],
            [
                'nama' => 'Paket cuci + isi freon',
                'ringkasan' => 'Promo musim kemarau',
                'deskripsi' => 'Paket hemat cuci AC sekaligus isi freon, berlaku selama musim kemarau.',
                'kategori' => KategoriLayanan::CuciAc,
                'jenis_ac' => 'Split dinding',
                'durasi' => '90 menit',
                'fitur' => ['Cuci unit dalam dan unit luar', 'Isi freon sampai tekanan normal'],
                'satuan' => 'paket',
                'tarif_mulai' => 200000,
                'keterangan_harga' => 'Mulai dari',
                'catatan_harga' => null,
                'perlu_survei' => false,
                'bagi_hasil' => 45,
                'aktif' => false,
            ],
        ];

        foreach ($daftar as $urutan => $layanan) {
            Layanan::updateOrCreate(
                ['nama' => $layanan['nama']],
                $layanan + ['slug' => Str::slug($layanan['nama']), 'urutan' => $urutan + 1],
            );
        }
    }
}
