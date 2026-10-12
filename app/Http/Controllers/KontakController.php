<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class KontakController extends Controller
{
    /**
     * Tampilkan profil usaha dan kontak. Datanya masih dummy sampai tabel pengaturan tersedia.
     */
    public function index(): View
    {
        return view('pengguna.kontak', [
            'profil' => [
                'nama_pemilik' => 'Pak Abdul Haris',
                'jabatan' => 'Founder & Kepala Workshop Pangkep',
                'sejak' => 2006,
                'foto' => 'images/kontak-pemilik.jpg',
                'keunggulan' => ['Pengalaman 20+ Tahun', 'Teknisi Berlisensi BNSP', 'Spesialis Pendingin Pangkep'],
                'kutipan' => 'Bagi kami di CV. Tiga Putra Teknik, pendingin udara bukan sekadar dingin, melainkan kesehatan udara dan kejujuran teknis. Kami pantang mengisi freon bila ada kebocoran yang belum tuntas dibenahi.',
            ],
            'kontak' => [
                'foto_toko' => 'images/kontak-toko.jpg',
                'jam_operasional' => 'Senin - Sabtu: 08.00 - 17.00 WITA',
                'catatan_jam' => 'Minggu & Hari Libur: Melayani dengan Janji Temu',
                'alamat' => 'Jl. Poros Makassar - Pangkep Km. 48, Kec. Bungoro, Kab. Pangkajene dan Kepulauan, Sulawesi Selatan',
                'patokan' => 'Dekat gapura poros utama Makassar - Parepare, akses jalan lebar dengan parkir luas.',
                'telepon' => config('usaha.whatsapp.nomor'),
                'tautan_whatsapp' => config('usaha.whatsapp.tautan'),
                'catatan_telepon' => 'Konsultasi keluhan AC & respon cepat oleh admin.',
            ],
        ]);
    }
}
