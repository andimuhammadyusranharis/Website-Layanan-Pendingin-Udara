<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Tampilkan daftar layanan. Datanya masih dummy sampai tabel layanan dan tarif_layanan tersedia.
     * Foto menunggu foto asli pekerjaan, sementara tampil panel "Foto menyusul".
     */
    public function index(): View
    {
        return view('pengguna.layanan', [
            'kategoriLayanan' => [
                ['slug' => 'split-dinding', 'nama' => 'AC split dinding'],
                ['slug' => 'cassette-standing', 'nama' => 'AC cassette dan standing'],
                ['slug' => 'perbaikan-freon', 'nama' => 'Perbaikan dan freon'],
                ['slug' => 'bongkar-pasang', 'nama' => 'Bongkar pasang'],
                ['slug' => 'komersial', 'nama' => 'AC sentral dan gedung'],
            ],
            'layanan' => [
                [
                    'id' => 1,
                    'nama' => 'Cuci AC split dinding',
                    'kategori' => 'split-dinding',
                    'jenis_ac' => 'Split dinding 0,5 sampai 2,5 PK',
                    'durasi' => '45 sampai 60 menit',
                    'deskripsi' => 'Unit dalam dan unit luar dibersihkan. Lantai dan dinding dilapisi plastik supaya air kotor tidak menciprat.',
                    'fitur' => ['Filter, kipas, dan sirip pendingin dicuci', 'Unit luar disemprot dan arus listrik dicek', 'Saluran pembuangan air dibersihkan'],
                    'keterangan_harga' => 'Mulai dari',
                    'harga_mulai' => 75000,
                    'satuan' => 'unit',
                    'catatan_harga' => null,
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-cuci-split.jpg',
                ],
                [
                    'id' => 2,
                    'nama' => 'Cuci AC cassette dan standing',
                    'kategori' => 'cassette-standing',
                    'jenis_ac' => 'Cassette dan standing floor',
                    'durasi' => '90 sampai 120 menit',
                    'deskripsi' => 'Untuk AC 2,5 sampai 5 PK di ruko, kantor, aula, dan rumah makan.',
                    'fitur' => ['Bak penampung air dan pompanya dibersihkan', 'Kipas dibongkar lalu dicuci', 'Filter dan kisi-kisi angin dicuci'],
                    'keterangan_harga' => 'Mulai dari',
                    'harga_mulai' => 150000,
                    'satuan' => 'unit',
                    'catatan_harga' => '2,5 sampai 5 PK',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-cuci-cassette.jpg',
                ],
                [
                    'id' => 3,
                    'nama' => 'Isi dan tambah freon',
                    'kategori' => 'perbaikan-freon',
                    'jenis_ac' => 'Semua AC split',
                    'durasi' => '30 sampai 45 menit',
                    'deskripsi' => 'Untuk AC yang kurang dingin karena freon berkurang. Sambungan pipa dicek dulu supaya freon tidak bocor lagi.',
                    'fitur' => ['Sambungan pipa dicek kebocorannya', 'Tekanan freon diukur sebelum dan sesudah diisi', 'Tersedia freon R32, R410A, dan R22'],
                    'keterangan_harga' => 'Mulai dari',
                    'harga_mulai' => 125000,
                    'satuan' => 'paket',
                    'catatan_harga' => 'R32, R410A, R22',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-isi-freon.jpg',
                ],
                [
                    'id' => 4,
                    'nama' => 'Perbaikan kelistrikan dan modul',
                    'kategori' => 'perbaikan-freon',
                    'jenis_ac' => 'Semua AC split',
                    'durasi' => '60 sampai 90 menit',
                    'deskripsi' => 'Untuk AC yang mati total, lampunya berkedip, atau unit luarnya tidak menyala.',
                    'fitur' => ['Kapasitor diganti bila rusak', 'Sensor suhu dan layar diperiksa', 'Kabel luar disambung ulang dan dibungkus'],
                    'keterangan_harga' => 'Biaya pengecekan',
                    'harga_mulai' => 100000,
                    'satuan' => null,
                    'catatan_harga' => 'Belum termasuk suku cadang',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-perbaikan.jpg',
                ],
                [
                    'id' => 5,
                    'nama' => 'Bongkar dan pasang AC',
                    'kategori' => 'bongkar-pasang',
                    'jenis_ac' => 'Split dinding',
                    'durasi' => '2 sampai 3 jam',
                    'deskripsi' => 'Untuk AC baru, AC bekas, atau pindah ruangan. Unit dipasang lurus dan pipanya divakum sebelum freon dialirkan.',
                    'fitur' => ['Pipa divakum agar tidak ada udara dan uap air', 'Pipa dibungkus rapi', 'Freon ditampung dulu saat AC dibongkar'],
                    'keterangan_harga' => 'Pasang mulai dari',
                    'harga_mulai' => 250000,
                    'satuan' => 'unit',
                    'catatan_harga' => 'Bongkar saja Rp100.000',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-bongkar-pasang.jpg',
                ],
                [
                    'id' => 6,
                    'nama' => 'Survei AC sentral dan gedung',
                    'kategori' => 'komersial',
                    'jenis_ac' => 'Central, multi split, VRF, ducted',
                    'durasi' => null,
                    'deskripsi' => 'Jenis AC ini perlu dilihat langsung dulu. Teknisi datang memeriksa unit dan ruangan, lalu biaya pekerjaan disampaikan setelah survei.',
                    'fitur' => ['Jadwal survei disepakati lewat WhatsApp', 'Biaya pekerjaan disampaikan setelah survei'],
                    'keterangan_harga' => 'Biaya',
                    'harga_mulai' => null,
                    'satuan' => null,
                    'catatan_harga' => null,
                    'perlu_survei' => true,
                    'gambar' => 'images/layanan-komersial.jpg',
                ],
            ],
            'simulasiEstimasi' => [
                'judul' => 'Cuci 3 unit AC split di rumah',
                'rincian' => [
                    ['nama' => 'Cuci AC split, 3 unit', 'biaya' => 225000],
                ],
                'total' => 225000,
            ],
        ]);
    }
}
