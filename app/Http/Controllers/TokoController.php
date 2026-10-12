<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TokoController extends Controller
{
    /**
     * Tampilkan katalog toko. Datanya masih dummy sampai tabel produk tersedia.
     */
    public function index(): View
    {
        return view('pengguna.toko', [
            'kategoriProduk' => [
                ['slug' => 'unit-ac', 'nama' => 'Unit AC Baru (Pilihan Split)'],
                ['slug' => 'kelistrikan', 'nama' => 'Kapasitor & Kelistrikan'],
                ['slug' => 'freon', 'nama' => 'Gas Freon (R32, R410A, R22)'],
                ['slug' => 'pipa', 'nama' => 'Pipa Tembaga & Insulasi'],
                ['slug' => 'aksesoris', 'nama' => 'Remote Universal & Aksesoris'],
            ],
            'merekPopuler' => ['Daikin', 'Panasonic', 'Sharp', 'Gree', 'LG', 'Mitsubishi Electric'],
            'unitAc' => [
                ['id' => 101, 'merek' => 'Daikin', 'model' => 'FTKQ15UVM4', 'nama' => 'Flash Inverter 0.5 PK', 'spesifikasi' => ['Daya 340W (Low Watt)', 'Freon R32 Ramah Lingkungan'], 'garansi' => 'Garansi Kompresor 3 Tahun Resmi', 'harga' => 4250000, 'stok' => 4, 'gambar' => 'images/toko-unit-daikin.jpg'],
                ['id' => 102, 'merek' => 'Panasonic', 'model' => 'CS-YN9WKJ', 'nama' => 'Standard Eco Tough 1.0 PK', 'spesifikasi' => ['Daya 800W Dingin Kilat', 'Freon R32 Blue Fin Condenser'], 'garansi' => 'Garansi 3 Tahun Anti-Karat Pangkep', 'harga' => 3650000, 'stok' => 6, 'gambar' => 'images/toko-unit-panasonic.jpg'],
                ['id' => 103, 'merek' => 'Sharp', 'model' => 'AH-A5SAY', 'nama' => 'Jetstream Series 0.5 PK', 'spesifikasi' => ['Daya 390W Hemat Listrik', 'Coanda Airflow Nyaman Bayi'], 'garansi' => 'Garansi Resmi 3 Tahun Kompresor', 'harga' => 3150000, 'stok' => 5, 'gambar' => 'images/toko-unit-sharp.jpg'],
                ['id' => 104, 'merek' => 'Gree', 'model' => 'GWC-12MOO5', 'nama' => 'Standard Heavy Duty 1.5 PK', 'spesifikasi' => ['Daya 1050W Area Luas', 'Freon R32 Frost Anode'], 'garansi' => 'Garansi 5 Tahun Sparepart & Kompresor', 'harga' => 4850000, 'stok' => 3, 'gambar' => 'images/toko-unit-gree.jpg'],
            ],
            'produk' => [
                ['id' => 1, 'kategori' => 'Kelistrikan AC', 'nama' => 'Kapasitor AC Dual 30+1.5 uF 450V', 'deskripsi' => 'Kompatibel untuk unit Daikin, Panasonic, Changhong 0.5 - 1 PK. Menjaga kompresor start halus & stabil.', 'harga' => 85000, 'satuan' => null, 'stok' => 16, 'gambar' => 'images/toko-kapasitor.jpg'],
                ['id' => 2, 'kategori' => 'Refrigerant / Gas Freon', 'nama' => 'Tabung Gas Freon Murni R32 Segel Pabrik (Can 3kg)', 'deskripsi' => 'Kemurnian 99.9%, dingin optimal, tidak merusak kompresor inverter dan bertekanan standar internasional.', 'harga' => 195000, 'satuan' => null, 'stok' => 12, 'gambar' => 'images/toko-freon.jpg'],
                ['id' => 3, 'kategori' => 'Pipa & Insulasi', 'nama' => 'Pipa Tembaga Roll Tebal 0.61mm Standard Inverter', 'deskripsi' => 'Panjang 5 meter per roll (1/4 + 3/8 inch). Tahan tekanan freon R32/R410A tanpa resiko retak atau bengkok.', 'harga' => 240000, 'satuan' => 'roll (5m)', 'stok' => 9, 'gambar' => 'images/toko-pipa.jpg'],
                ['id' => 4, 'kategori' => 'Aksesoris & Modul', 'nama' => 'Remote Universal Multi AC (Support 1000+ Tipe)', 'deskripsi' => 'Langsung sinkron untuk Daikin, Sharp, Panasonic, Gree, LG, AUX. Disertai baterai & panduan bahasa Indonesia.', 'harga' => 65000, 'satuan' => null, 'stok' => 20, 'gambar' => 'images/toko-remote.jpg'],
                ['id' => 5, 'kategori' => 'Drainase & Pembuangan Air', 'nama' => 'Pompa Drainase Otomatis Anti Bocor Kingpump', 'deskripsi' => 'Solusi indoor AC yang bocor ke lantai atau plafon. Motor hening & tahan panas dengan pelampung otomatis.', 'harga' => 380000, 'satuan' => null, 'stok' => 2, 'gambar' => 'images/toko-pompa.jpg'],
                ['id' => 6, 'kategori' => 'Dudukan & Bracket', 'nama' => 'Bracket Outdoor Tebal Anti Karat Galvanis', 'deskripsi' => 'Kuat menahan beban hingga AC 2 PK. Lapisan tahan hawa garam pesisir Pangkep. Sudah termasuk dynabolt lengkap.', 'harga' => 75000, 'satuan' => 'pasang', 'stok' => 15, 'gambar' => 'images/toko-bracket.jpg'],
            ],
        ]);
    }
}
