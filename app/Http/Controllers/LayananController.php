<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Tampilkan daftar layanan. Datanya masih dummy sampai tabel layanan dan tarif_layanan tersedia.
     */
    public function index(): View
    {
        return view('pengguna.layanan', [
            'kategoriLayanan' => [
                ['slug' => 'split-dinding', 'nama' => 'AC Split Dinding'],
                ['slug' => 'cassette-standing', 'nama' => 'AC Cassette & Standing'],
                ['slug' => 'perbaikan-freon', 'nama' => 'Perbaikan & Freon'],
                ['slug' => 'bongkar-pasang', 'nama' => 'Bongkar Pasang & Relokasi'],
                ['slug' => 'komersial', 'nama' => 'Servis Komersial / Instansi'],
            ],
            'layanan' => [
                [
                    'id' => 1,
                    'nama' => 'Cuci AC Split Dinding Higienis',
                    'kategori' => 'split-dinding',
                    'jenis_ac' => 'AC Split Dinding (0,5 - 2,5 PK)',
                    'label' => 'Paling Sering Dipesan',
                    'durasi' => '45-60 Menit',
                    'garansi' => 'Garansi 14 Hari',
                    'deskripsi' => 'Pembersihan mendalam (deep wash) unit indoor dan outdoor, anti jamur, serta proteksi terpal anti-ceceran.',
                    'fitur' => ['Deep steam evaporator & blower indoor', 'Cuci kondensor outdoor & cek ampere arus', 'Pembersihan saluran drainase cegah bocor air'],
                    'keterangan_harga' => 'Tarif Pengerjaan',
                    'harga_mulai' => 75000,
                    'satuan' => 'unit',
                    'catatan_harga' => 'Tanpa Transport',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-cuci-split.jpg',
                ],
                [
                    'id' => 2,
                    'nama' => 'Cuci AC Cassette & Standing Floor',
                    'kategori' => 'cassette-standing',
                    'jenis_ac' => 'Cassette / Floor Standing',
                    'label' => 'Komersial & Perkantoran',
                    'durasi' => '90-120 Menit',
                    'garansi' => 'Garansi 14 Hari',
                    'deskripsi' => 'Pembersihan menyeluruh khusus unit kapasitas 2,5 hingga 5 PK di aula, ruko, restoran, dan minimarket.',
                    'fitur' => ['Dismantle baki drainase & sterilisasi pompa siphon', 'Pembersihan motor blower turbo 3 dimensi', 'Inspeksi filter sarang lebah & kisi swing'],
                    'keterangan_harga' => 'Tarif Mulai',
                    'harga_mulai' => 150000,
                    'satuan' => 'unit',
                    'catatan_harga' => '2,5 PK - 5 PK',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-cuci-cassette.jpg',
                ],
                [
                    'id' => 3,
                    'nama' => 'Isi & Tambah Gas Freon Murni',
                    'kategori' => 'perbaikan-freon',
                    'jenis_ac' => 'Refrigerant Refill',
                    'label' => 'Gas Freon Murni 100%',
                    'durasi' => '30-45 Menit',
                    'garansi' => 'Garansi 30 Hari',
                    'deskripsi' => 'Pengisian freon berkualitas tinggi (R32, R410A, dan R22) dilengkapi deteksi kebocoran sambungan.',
                    'fitur' => ['Uji kebocoran flare nut & sambungan pipa', 'Tekanan PSI disesuaikan spesifikasi kompresor', 'Tersedia tabung murni bersertifikat SNI'],
                    'keterangan_harga' => 'Mulai Tambah Freon',
                    'harga_mulai' => 125000,
                    'satuan' => 'paket',
                    'catatan_harga' => 'R32 / R410 / R22',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-isi-freon.jpg',
                ],
                [
                    'id' => 4,
                    'nama' => 'Perbaikan Kelistrikan & Modul PCB',
                    'kategori' => 'perbaikan-freon',
                    'jenis_ac' => 'Troubleshooting & Komponen',
                    'label' => 'Diagnosa Akurat',
                    'durasi' => '60-90 Menit',
                    'garansi' => 'Garansi 3 Hari',
                    'deskripsi' => 'Penanganan AC mati total, lampu timer kedip-kedip (blinking error), dan ganti kapasitor outdoor.',
                    'fitur' => ['Ganti kapasitor asli merk Daikin, Panasonic, dll', 'Perbaikan sensor suhu termistor & display', 'Solder ulang & isolasi waterproof kabel luar'],
                    'keterangan_harga' => 'Biaya Jasa Diagnosa',
                    'harga_mulai' => 100000,
                    'satuan' => null,
                    'catatan_harga' => 'Belum termasuk sparepart',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-perbaikan.jpg',
                ],
                [
                    'id' => 5,
                    'nama' => 'Bongkar & Pasang Unit AC Baru / Bekas',
                    'kategori' => 'bongkar-pasang',
                    'jenis_ac' => 'Instalasi & Pindahan Unit',
                    'label' => 'Standar Pabrikan',
                    'durasi' => '2-3 Jam',
                    'garansi' => 'Garansi 30 Hari',
                    'deskripsi' => 'Instalasi rapi waterpass tanpa miring, proses vakum pipa tembaga wajib standar pabrikan.',
                    'fitur' => ['Wajib proses vakum mesin bebas sisa udara & uap', 'Perapian pipa pembungkus duct tape rapi', 'Pump down freon aman saat proses pembongkaran'],
                    'keterangan_harga' => 'Tarif Pasang Mulai',
                    'harga_mulai' => 250000,
                    'satuan' => 'unit',
                    'catatan_harga' => 'Bongkar: Rp100rb',
                    'perlu_survei' => false,
                    'gambar' => 'images/layanan-bongkar-pasang.jpg',
                ],
                [
                    'id' => 6,
                    'nama' => 'Survei & Kontrak AC Komersial (VRF / Central)',
                    'kategori' => 'komersial',
                    'jenis_ac' => 'Gedung / Instansi / Pabrik',
                    'label' => 'Proyek & Kontrak',
                    'durasi' => 'Jadwal Survei Bebas',
                    'garansi' => 'Berbadan Hukum CV',
                    'deskripsi' => 'Penanganan berkala untuk gedung instansi pemerintah, rumah sakit daerah, perbankan, dan pabrik.',
                    'fitur' => ['Faktur pajak PPN resmi & legalitas SPK CV', 'Laporan audit performa suhu berkala', 'Prioritas respons teknisi siaga 24 jam'],
                    'keterangan_harga' => 'Survei Lapangan',
                    'harga_mulai' => null,
                    'satuan' => 'proposal RAB',
                    'catatan_harga' => 'B2B / Instansi',
                    'perlu_survei' => true,
                    'gambar' => 'images/layanan-komersial.jpg',
                ],
            ],
            'simulasiEstimasi' => [
                'judul' => 'Simulasi Cuci 3 Unit Rumah',
                'rincian' => [
                    ['nama' => 'Cuci AC Split x 3 Unit', 'biaya' => 225000],
                    ['nama' => 'Transport', 'biaya' => 0],
                ],
                'total' => 225000,
            ],
        ]);
    }
}
