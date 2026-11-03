<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class UlasanController extends Controller
{
    /**
     * Tampilkan ulasan pelanggan. Datanya masih dummy sampai tabel review tersedia.
     */
    public function index(): View
    {
        return view('pengguna.ulasan', [
            'ringkasan' => self::ringkasanDummy(),
            'kategoriUlasan' => [
                ['slug' => 'cuci-ac-split', 'nama' => 'Cuci AC Split', 'jumlah' => 74],
                ['slug' => 'isi-freon', 'nama' => 'Isi Freon', 'jumlah' => 26],
                ['slug' => 'perbaikan-kelistrikan', 'nama' => 'Perbaikan Kelistrikan', 'jumlah' => 15],
                ['slug' => 'bongkar-pasang', 'nama' => 'Bongkar Pasang', 'jumlah' => 9],
                ['slug' => 'pembelian-komponen', 'nama' => 'Pembelian Komponen', 'jumlah' => 4],
            ],
            'ulasan' => self::ulasanDummy(),
        ]);
    }

    /**
     * Ringkasan rating dummy. Dipakai juga oleh beranda agar angkanya sama di kedua halaman.
     *
     * @return array{rata_rata: float, jumlah: int, persen_puas: int, sebaran: array<int, int>}
     */
    public static function ringkasanDummy(): array
    {
        return [
            'rata_rata' => 4.9,
            'jumlah' => 128,
            'persen_puas' => 98,
            'sebaran' => [5 => 114, 4 => 11, 3 => 3, 2 => 0, 1 => 0],
        ];
    }

    /**
     * Ulasan dummy, terbaru lebih dulu. Dipakai juga oleh beranda.
     *
     * @return list<array{nama: string, lokasi: string, kategori: string, layanan: string, rating: int, komentar: string, teknisi: ?string, tanggal: string}>
     */
    public static function ulasanDummy(): array
    {
        return [
            ['nama' => 'Hj. Nurhaedah', 'lokasi' => 'Kel. Pangkajene', 'kategori' => 'cuci-ac-split', 'layanan' => 'Cuci AC split dinding (2 unit)', 'rating' => 5, 'komentar' => 'Lantai dan dinding dilapisi plastik dulu sebelum dicuci, jadi tidak ada cipratan air kotor. AC langsung dingin lagi.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-05-18'],
            ['nama' => 'Ahmad Syarif', 'lokasi' => 'Kec. Minasatene', 'kategori' => 'perbaikan-kelistrikan', 'layanan' => 'Perbaikan kelistrikan dan ganti kapasitor', 'rating' => 5, 'komentar' => 'Datang sesuai jam yang dijanjikan. Biayanya sama dengan estimasi di awal.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-05-14'],
            ['nama' => 'Drs. Ridwan M.', 'lokasi' => 'Kec. Bungoro', 'kategori' => 'cuci-ac-split', 'layanan' => 'Cuci AC cassette 3 PK', 'rating' => 5, 'komentar' => 'AC kantor dibersihkan sampai saluran pembuangannya. Bunyinya tidak berisik lagi dan bau apeknya hilang.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-05-09'],
            ['nama' => 'Wahyu Pratama', 'lokasi' => 'Kel. Samalewa, Bungoro', 'kategori' => 'isi-freon', 'layanan' => 'Isi freon R32 dan tambah pipa', 'rating' => 5, 'komentar' => 'Tekanan freon ditunjukkan ke saya sebelum dan sesudah diisi, jadi saya tahu memang kurang.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-05-02'],
            ['nama' => 'Ibu Sitti Aminah', 'lokasi' => 'Kec. Segeri', 'kategori' => 'bongkar-pasang', 'layanan' => 'Bongkar pasang AC 1 PK', 'rating' => 4, 'komentar' => 'Pemasangannya rapi. Terlambat sekitar 15 menit karena hujan, tapi dikabari lewat WhatsApp sebelumnya.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-04-27'],
            ['nama' => 'Muhammad Arifin', 'lokasi' => 'Kec. Pangkajene', 'kategori' => 'pembelian-komponen', 'layanan' => 'Beli remote AC universal', 'rating' => 5, 'komentar' => 'Remotenya langsung disetel di toko dan saya diajari cara pakainya. Harganya sama dengan di katalog.', 'teknisi' => null, 'tanggal' => '2026-04-21'],
        ];
    }
}
