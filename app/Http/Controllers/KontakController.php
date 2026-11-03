<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class KontakController extends Controller
{
    /**
     * Tampilkan profil usaha dan kontak. Datanya masih dummy sampai tabel pengaturan tersedia.
     * Alamat dan jam buka diambil dari config/usaha.php agar sama dengan header dan footer.
     */
    public function index(): View
    {
        return view('pengguna.kontak', [
            'profil' => [
                'nama_pemilik' => 'Pak Abdul Haris',
                'jabatan' => 'Pemilik dan teknisi',
                'sejak' => 2006,
                'foto' => 'images/kontak-pemilik.jpg',
                'keunggulan' => ['Pengalaman 20 tahun memperbaiki AC', 'Melayani seluruh Kabupaten Pangkep'],
                'kutipan' => null,
            ],
            'kontak' => [
                'foto_toko' => 'images/kontak-toko.jpg',
                'jam_operasional' => config('usaha.jam_buka'),
                'catatan_jam' => null,
                'alamat' => config('usaha.alamat'),
                'patokan' => null,
                'telepon' => config('usaha.whatsapp.nomor'),
                'tautan_whatsapp' => config('usaha.whatsapp.tautan'),
                'catatan_telepon' => 'Untuk tanya keluhan AC, jadwal, dan harga.',
            ],
        ]);
    }
}
