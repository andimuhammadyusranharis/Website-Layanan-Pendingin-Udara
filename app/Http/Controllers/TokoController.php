<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TokoController extends Controller
{
    /**
     * Tampilkan katalog toko. Datanya masih dummy sampai tabel produk tersedia.
     * Foto menunggu foto asli barang di toko, sementara tampil panel "Foto menyusul".
     */
    protected array $kategoriProduk;
    protected array $merekPopuler;
    protected array $unitAc;
    protected array $produk;

    public function __construct()
    {
        $kategoriProduk = [
            ['slug' => 'unit-ac', 'nama' => 'Unit AC baru'],
            ['slug' => 'kelistrikan', 'nama' => 'Kapasitor dan kelistrikan'],
            ['slug' => 'freon', 'nama' => 'Freon'],
            ['slug' => 'pipa', 'nama' => 'Pipa tembaga'],
            ['slug' => 'aksesoris', 'nama' => 'Remote dan aksesoris'],
        ];
        $merekPopuler = ['Daikin', 'Panasonic', 'Sharp', 'Gree', 'LG', 'Mitsubishi Electric'];
        $unitAc = [
            ['id' => 101, 'slug_kategori' => 'unit-ac', 'merek' => 'Daikin', 'model' => 'FTKQ15UVM4', 'nama' => 'Flash Inverter 0,5 PK', 'spesifikasi' => ['Daya 340 watt', 'Freon R32'], 'harga' => 4250000, 'stok' => 4, 'gambar' => 'images/toko-unit-daikin.jpg'],
            ['id' => 102, 'slug_kategori' => 'unit-ac', 'merek' => 'Panasonic', 'model' => 'CS-YN9WKJ', 'nama' => 'Standard 1 PK', 'spesifikasi' => ['Daya 800 watt', 'Freon R32'], 'harga' => 3650000, 'stok' => 6, 'gambar' => 'images/toko-unit-panasonic.jpg'],
            ['id' => 103, 'slug_kategori' => 'unit-ac', 'merek' => 'Sharp', 'model' => 'AH-A5SAY', 'nama' => 'Jetstream 0,5 PK', 'spesifikasi' => ['Daya 390 watt', 'Freon R32'], 'harga' => 3150000, 'stok' => 5, 'gambar' => 'images/toko-unit-sharp.jpg'],
            ['id' => 104, 'slug_kategori' => 'unit-ac', 'merek' => 'Gree', 'model' => 'GWC-12MOO5', 'nama' => 'Standard 1,5 PK', 'spesifikasi' => ['Daya 1.050 watt', 'Freon R32'], 'harga' => 4850000, 'stok' => 3, 'gambar' => 'images/toko-unit-gree.jpg'],
        ];
        $produk = [
            ['id' => 1, 'slug_kategori' => 'kelistrikan', 'kategori' => 'Kelistrikan', 'nama' => 'Kapasitor AC 30+1,5 uF 450V', 'deskripsi' => 'Untuk AC Daikin, Panasonic, dan Changhong 0,5 sampai 1 PK. Ganti bila AC tidak mau menyala atau kompresor berdengung.', 'harga' => 85000, 'satuan' => null, 'stok' => 16, 'gambar' => 'images/toko-kapasitor.jpg'],
            ['id' => 2, 'slug_kategori' => 'freon', 'kategori' => 'Freon', 'nama' => 'Freon R32 tabung 3 kg', 'deskripsi' => 'Untuk AC baru yang memakai freon R32. Tabung masih bersegel.', 'harga' => 195000, 'satuan' => null, 'stok' => 12, 'gambar' => 'images/toko-freon.jpg'],
            ['id' => 3, 'slug_kategori' => 'pipa', 'kategori' => 'Pipa tembaga', 'nama' => 'Pipa tembaga 1/4 + 3/8 inci, tebal 0,61 mm', 'deskripsi' => 'Panjang 5 meter per roll. Bisa dipakai untuk AC dengan freon R32 dan R410A.', 'harga' => 240000, 'satuan' => 'roll (5 m)', 'stok' => 9, 'gambar' => 'images/toko-pipa.jpg'],
            ['id' => 4, 'slug_kategori' => 'aksesoris', 'kategori' => 'Remote', 'nama' => 'Remote AC universal', 'deskripsi' => 'Bisa untuk Daikin, Sharp, Panasonic, Gree, LG, dan AUX. Sudah termasuk baterai.', 'harga' => 65000, 'satuan' => null, 'stok' => 20, 'gambar' => 'images/toko-remote.jpg'],
            ['id' => 5, 'slug_kategori' => 'aksesoris', 'kategori' => 'Pembuangan air', 'nama' => 'Pompa pembuangan air AC Kingpump', 'deskripsi' => 'Untuk AC yang airnya menetes ke lantai atau plafon karena saluran pembuangan tidak bisa dibuat miring.', 'harga' => 380000, 'satuan' => null, 'stok' => 2, 'gambar' => 'images/toko-pompa.jpg'],
            ['id' => 6, 'slug_kategori' => 'aksesoris', 'kategori' => 'Dudukan', 'nama' => 'Bracket outdoor galvanis', 'deskripsi' => 'Dudukan unit luar untuk AC sampai 2 PK. Sudah termasuk dynabolt.', 'harga' => 75000, 'satuan' => 'pasang', 'stok' => 15, 'gambar' => 'images/toko-bracket.jpg'],
        ];
    }
    public function index(): View
    {
        return view('pengguna.toko', [
            'kategoriProduk' => $this->kategoriProduk,
            'merekPopuler' => $this->merekPopuler,
            'unitAc' => $this->unitAc,
            'produk' => $this->produk,
        ]);
    }
}
