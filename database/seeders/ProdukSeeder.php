<?php

namespace Database\Seeders;

use App\Enums\KategoriProduk;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    /**
     * Data awal toko: 4 unit AC dan 6 komponen. Foto tidak diisi di sini. Admin mengunggahnya lewat form produk.
     */
    public function run(): void
    {
        $daftar = [
            [
                'nama' => 'Flash Inverter 0,5 PK',
                'kategori' => KategoriProduk::UnitAc,
                'deskripsi' => 'AC split inverter untuk kamar sekitar 3 x 3 meter.',
                'merek' => 'Daikin',
                'model' => 'FTKQ15UVM4',
                'spesifikasi' => ['Daya 340 watt', 'Freon R32'],
                'harga' => 4250000,
                'stok' => 4,
            ],
            [
                'nama' => 'Standard 1 PK',
                'kategori' => KategoriProduk::UnitAc,
                'deskripsi' => 'AC split standar untuk ruangan sekitar 3 x 5 meter.',
                'merek' => 'Panasonic',
                'model' => 'CS-YN9WKJ',
                'spesifikasi' => ['Daya 800 watt', 'Freon R32'],
                'harga' => 3650000,
                'stok' => 6,
            ],
            [
                'nama' => 'Jetstream 0,5 PK',
                'kategori' => KategoriProduk::UnitAc,
                'deskripsi' => 'AC split standar untuk kamar sekitar 3 x 3 meter.',
                'merek' => 'Sharp',
                'model' => 'AH-A5SAY',
                'spesifikasi' => ['Daya 390 watt', 'Freon R32'],
                'harga' => 3150000,
                'stok' => 5,
            ],
            [
                'nama' => 'Standard 1,5 PK',
                'kategori' => KategoriProduk::UnitAc,
                'deskripsi' => 'AC split standar untuk ruangan sekitar 4 x 5 meter.',
                'merek' => 'Gree',
                'model' => 'GWC-12MOO5',
                'spesifikasi' => ['Daya 1.050 watt', 'Freon R32'],
                'harga' => 4850000,
                'stok' => 3,
            ],
            [
                'nama' => 'Kapasitor AC 30+1,5 uF 450V',
                'kategori' => KategoriProduk::Kelistrikan,
                'deskripsi' => 'Untuk AC Daikin, Panasonic, dan Changhong 0,5 sampai 1 PK. Ganti bila AC tidak mau menyala atau kompresor berdengung.',
                'harga' => 85000,
                'stok' => 16,
            ],
            [
                'nama' => 'Freon R32 tabung 3 kg',
                'kategori' => KategoriProduk::Freon,
                'deskripsi' => 'Untuk AC baru yang memakai freon R32. Tabung masih bersegel.',
                'harga' => 195000,
                'stok' => 12,
            ],
            [
                'nama' => 'Pipa tembaga 1/4 + 3/8 inci, tebal 0,61 mm',
                'kategori' => KategoriProduk::Pipa,
                'deskripsi' => 'Panjang 5 meter per roll. Bisa dipakai untuk AC dengan freon R32 dan R410A.',
                'harga' => 240000,
                'stok' => 9,
                'satuan' => 'roll (5 m)',
            ],
            [
                'nama' => 'Remote AC universal',
                'kategori' => KategoriProduk::Aksesoris,
                'deskripsi' => 'Bisa untuk Daikin, Sharp, Panasonic, Gree, LG, dan AUX. Sudah termasuk baterai.',
                'harga' => 65000,
                'stok' => 20,
            ],
            [
                'nama' => 'Pompa pembuangan air AC Kingpump',
                'kategori' => KategoriProduk::Aksesoris,
                'deskripsi' => 'Untuk AC yang airnya menetes ke lantai atau plafon karena saluran pembuangan tidak bisa dibuat miring.',
                'harga' => 380000,
                'stok' => 2,
            ],
            [
                'nama' => 'Bracket outdoor galvanis',
                'kategori' => KategoriProduk::Aksesoris,
                'deskripsi' => 'Dudukan unit luar untuk AC sampai 2 PK. Sudah termasuk dynabolt.',
                'harga' => 75000,
                'stok' => 15,
                'satuan' => 'pasang',
            ],
        ];

        foreach ($daftar as $produk) {
            Produk::updateOrCreate(
                ['nama' => $produk['nama']],
                $produk + ['slug' => Str::slug($produk['nama'])],
            );
        }
    }
}
