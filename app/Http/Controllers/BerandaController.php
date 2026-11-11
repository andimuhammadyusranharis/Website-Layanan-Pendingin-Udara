<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BerandaController extends Controller
{
    /**
     * Tampilkan beranda. Datanya masih dummy sampai tabel layanan, produk, dan review tersedia.
     * Galeri dikosongkan sampai ada foto asli pekerjaan pemilik.
     */
    public function index(): View
    {
        return view('pengguna.beranda', [
            'galeri' => [],
            'ringkasanUlasan' => UlasanController::ringkasanDummy(),
            'layananUnggulan' => [
                ['id' => 1, 'nama' => 'Cuci AC split dinding', 'deskripsi' => 'Filter, kipas, dan unit luar dibersihkan.', 'ikon' => 'air', 'harga_mulai' => 75000],
                ['id' => 2, 'nama' => 'Perbaikan AC', 'deskripsi' => 'AC bocor air, mati total, atau tidak dingin.', 'ikon' => 'home_repair_service', 'harga_mulai' => 100000],
                ['id' => 3, 'nama' => 'Bongkar dan pasang AC', 'deskripsi' => 'Pasang AC baru, bekas, atau pindah ruangan.', 'ikon' => 'construction', 'harga_mulai' => 250000],
                ['id' => 4, 'nama' => 'Isi dan tambah freon', 'deskripsi' => 'Freon R32, R410A, dan R22.', 'ikon' => 'speed', 'harga_mulai' => 125000],
            ],
            'produkUnggulan' => [
                ['id' => 1, 'nama' => 'Kapasitor AC 30+1,5 uF 450V', 'kategori' => 'Kelistrikan', 'harga' => 85000, 'stok' => 16, 'gambar' => 'images/toko-kapasitor.jpg'],
                ['id' => 3, 'nama' => 'Pipa tembaga 1/4 + 3/8 inci', 'kategori' => 'Pipa tembaga', 'harga' => 240000, 'stok' => 9, 'gambar' => 'images/toko-pipa.jpg'],
                ['id' => 4, 'nama' => 'Remote AC universal', 'kategori' => 'Remote', 'harga' => 65000, 'stok' => 20, 'gambar' => 'images/toko-remote.jpg'],
                ['id' => 2, 'nama' => 'Freon R32 tabung 3 kg', 'kategori' => 'Freon', 'harga' => 195000, 'stok' => 12, 'gambar' => 'images/toko-freon.jpg'],
            ],
            'ulasan' => array_slice(UlasanController::ulasanDummy(), 0, 3),
        ]);
    }
}
