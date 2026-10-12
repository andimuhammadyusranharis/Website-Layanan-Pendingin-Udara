<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BerandaController extends Controller
{
    /**
     * Tampilkan beranda. Datanya masih dummy sampai tabel layanan, produk, dan review tersedia.
     */
    public function index(): View
    {
        return view('pengguna.beranda', [
            'galeri' => [
                ['gambar' => 'images/beranda-galeri-1.jpg', 'keterangan' => 'Workshop & Kantor Resmi CV. Tiga Putra Teknik'],
                ['gambar' => 'images/beranda-galeri-2.jpg', 'keterangan' => 'Cuci AC split di rumah pelanggan'],
                ['gambar' => 'images/beranda-galeri-3.jpg', 'keterangan' => 'Pemasangan unit baru'],
                ['gambar' => 'images/beranda-galeri-4.jpg', 'keterangan' => 'Stok komponen di toko'],
            ],
            'ringkasanUlasan' => ['rata_rata' => 5.0, 'jumlah' => 48],
            'layananUnggulan' => [
                ['id' => 1, 'nama' => 'Cuci AC Split Dinding', 'deskripsi' => 'Pembersihan blower fan, filter anti-bakteri, dan kondensor outdoor secara higienis.', 'ikon' => 'air', 'harga_mulai' => 75000],
                ['id' => 2, 'nama' => 'Perbaikan Bocor & Mati', 'deskripsi' => 'Solusi AC bocor air ke lantai, modul error, kompresor macet, dan komponen rusak.', 'ikon' => 'home_repair_service', 'harga_mulai' => 100000],
                ['id' => 3, 'nama' => 'Pasang & Bongkar Unit', 'deskripsi' => 'Instalasi rapi dilengkapi vakum pipa standar pabrik demi menjaga kompresor awet.', 'ikon' => 'construction', 'harga_mulai' => 250000],
                ['id' => 4, 'nama' => 'Isi & Tambah Freon', 'deskripsi' => 'Pengisian freon asli R32, R410A, dan R22 murni dengan pengukur manifold presisi.', 'ikon' => 'speed', 'harga_mulai' => 150000],
            ],
            'produkUnggulan' => [
                ['id' => 1, 'nama' => 'Kapasitor AC 30 uF Daikin/Panasonic', 'kategori' => 'Kompresor & Motor', 'harga' => 85000, 'stok' => 16, 'gambar' => 'images/beranda-produk-kapasitor.jpg'],
                ['id' => 2, 'nama' => 'Pipa Tembaga AC 1/4 + 3/8 (Roll 5M)', 'kategori' => 'Pipa & Fitting', 'harga' => 340000, 'stok' => 8, 'gambar' => 'images/beranda-produk-pipa.jpg'],
                ['id' => 3, 'nama' => 'Remote Universal AC Multi Brand', 'kategori' => 'Aksesoris & Remote', 'harga' => 65000, 'stok' => 20, 'gambar' => 'images/beranda-produk-remote.jpg'],
                ['id' => 4, 'nama' => 'Freon R32 Murni Tabung Portable', 'kategori' => 'Refrigerant Freon', 'harga' => 195000, 'stok' => 2, 'gambar' => 'images/beranda-produk-freon.jpg'],
            ],
            'ulasan' => [
                ['nama' => 'Raihan Nur Faiz', 'lokasi' => 'Minasatene, Pangkep', 'rating' => 5, 'komentar' => 'AC di ruang tamu tadinya menetes air deras sampai lantai basah. Teknisi CV. Tiga Putra Teknik datang tepat jam 10 pagi, pengerjaannya sangat bersih pakai pelindung terpal rapi. Sekarang dinginnya semriwing lagi!'],
                ['nama' => 'Ibu Hj. Salmawati', 'lokasi' => 'Tumampua, Pangkajene', 'rating' => 5, 'komentar' => 'Tarifnya sangat jelas di kalkulator web sebelum pesan, tidak ada biaya siluman saat teknisi selesai. Sudah langganan cuci 3 AC rumah setiap 3 bulan sekali. Sangat direkomendasikan untuk warga Pangkajene.'],
                ['nama' => 'Irwan Baso', 'lokasi' => 'Bungoro, Pangkep', 'rating' => 5, 'komentar' => 'Pemasangan unit baru 1 PK di ruko saya di Bungoro dikerjakan sangat rapi, pipa divakum dengan benar bukan sekadar dibuang anginnya. Pak Haris paham sekali teknik pendingin modern.'],
            ],
        ]);
    }
}
