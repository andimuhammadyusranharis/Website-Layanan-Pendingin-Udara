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
            'ringkasan' => [
                'rata_rata' => 4.9,
                'jumlah' => 128,
                'persen_puas' => 98,
                'sebaran' => [5 => 114, 4 => 11, 3 => 3, 2 => 0, 1 => 0],
                'aspek' => [
                    ['nama' => 'Ketepatan Waktu', 'nilai' => 4.9],
                    ['nama' => 'Kebersihan Ruang', 'nilai' => 5.0],
                    ['nama' => 'Transparansi Biaya', 'nilai' => 4.9],
                ],
            ],
            'kategoriUlasan' => [
                ['slug' => 'cuci-ac-split', 'nama' => 'Cuci AC Split', 'jumlah' => 74],
                ['slug' => 'isi-freon', 'nama' => 'Isi Freon', 'jumlah' => 26],
                ['slug' => 'perbaikan-kelistrikan', 'nama' => 'Perbaikan Kelistrikan', 'jumlah' => 15],
                ['slug' => 'bongkar-pasang', 'nama' => 'Bongkar Pasang', 'jumlah' => 9],
                ['slug' => 'pembelian-komponen', 'nama' => 'Pembelian Komponen', 'jumlah' => 4],
            ],
            'ulasan' => [
                ['nama' => 'Hj. Nurhaedah', 'lokasi' => 'Kel. Pangkajene', 'kategori' => 'cuci-ac-split', 'layanan' => 'Cuci AC Split Dinding (2 Unit)', 'rating' => 5, 'komentar' => 'Pengerjaan Pak Haris sangat rapi. Dinding dan lantai dipasangi plastik pelindung sehingga tidak ada cipratan air kotor sama sekali. AC langsung dingin normal.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-05-18'],
                ['nama' => 'Ahmad Syarif', 'lokasi' => 'Kec. Minasatene', 'kategori' => 'perbaikan-kelistrikan', 'layanan' => 'Perbaikan Kelistrikan & Ganti Kapasitor', 'rating' => 5, 'komentar' => 'Respon cepat dan teknisi datang tepat waktu. Tarif transparan sesuai estimasi di awal tanpa biaya tambahan. Sangat direkomendasikan.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-05-14'],
                ['nama' => 'Drs. Ridwan M.', 'lokasi' => 'Kec. Bungoro (Ruko Kantor)', 'kategori' => 'cuci-ac-split', 'layanan' => 'Cuci AC Cassette 3 PK', 'rating' => 5, 'komentar' => 'Pembersihan unit kantor bersih tuntas sampai talang drainase. Suara hembusan kembali tenang dan bau apek hilang total.', 'teknisi' => 'Pak Abdul Haris & Tim', 'tanggal' => '2026-05-09'],
                ['nama' => 'Wahyu Pratama', 'lokasi' => 'Kel. Samalewa, Bungoro', 'kategori' => 'isi-freon', 'layanan' => 'Isi Freon R32 & Tambah Pipa', 'rating' => 5, 'komentar' => 'Pengecekan tekanan manifold diperlihatkan langsung ke saya sebelum dan sesudah diisi freon. Nota garansi 30 hari diberikan secara fisik dan digital.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-05-02'],
                ['nama' => 'Ibu Sitti Aminah', 'lokasi' => 'Kec. Segeri', 'kategori' => 'bongkar-pasang', 'layanan' => 'Bongkar Pasang AC 1 PK', 'rating' => 4, 'komentar' => 'Pemasangan rapi dan pipa terbungkus rapi dengan duct tape tebal. Hanya sedikit terlambat 15 menit karena hujan di jalan poros, tapi diinfokan via WA lebih dulu.', 'teknisi' => 'Pak Abdul Haris', 'tanggal' => '2026-04-27'],
                ['nama' => 'Muhammad Arifin', 'lokasi' => 'Kec. Pangkajene', 'kategori' => 'pembelian-komponen', 'layanan' => 'Beli Remote Universal AC', 'rating' => 5, 'komentar' => 'Komponen remote langsung diprogram dan diajari cara settingnya. Harganya sama persis dengan yang ada di katalog toko online.', 'teknisi' => null, 'tanggal' => '2026-04-21'],
            ],
        ]);
    }
}
