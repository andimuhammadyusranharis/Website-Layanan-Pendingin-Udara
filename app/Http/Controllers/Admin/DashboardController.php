<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard operasional admin. Datanya masih dummy sampai tabel pesanan,
     * jadwal, pembayaran, produk, dan teknisi tersedia.
     */
    public function index(): View
    {
        $stokKritis = [
            ['id' => 7, 'nama' => 'Kapasitor 30uF 450V Daikin/Multi', 'stok' => 2, 'batas' => 10],
            ['id' => 9, 'nama' => 'Gas Freon R32 Tabung Kecil', 'stok' => 1, 'batas' => 8],
        ];

        $jadwalHariIni = [
            ['id' => 41, 'waktu' => '08:00', 'pelanggan' => 'Hj. Nurhaedah', 'layanan' => 'Cuci AC Split 1 PK', 'teknisi' => 'Pak Haris', 'status' => 'selesai'],
            ['id' => 43, 'waktu' => '09:30', 'pelanggan' => 'Raihan Nur Faiz', 'layanan' => 'Cuci AC Split 1 PK', 'teknisi' => 'Pak Haris', 'status' => 'dikerjakan'],
            ['id' => 46, 'waktu' => '11:00', 'pelanggan' => 'Ahmad Syarif', 'layanan' => 'Isi Freon R32', 'teknisi' => 'Pak Haris', 'status' => 'dijadwalkan'],
            ['id' => 47, 'waktu' => '13:30', 'pelanggan' => 'Drs. Ridwan M.', 'layanan' => 'Cuci AC Cassette 3 PK', 'teknisi' => 'Pak Haris', 'status' => 'dijadwalkan'],
            ['id' => 48, 'waktu' => '15:00', 'pelanggan' => 'Ibu Sitti Aminah', 'layanan' => 'Bongkar Pasang AC 1 PK', 'teknisi' => null, 'status' => 'menunggu'],
        ];

        $verifikasiPembayaran = [
            ['id' => 12, 'id_pesanan' => 41, 'kode_pesanan' => 'ORD-0041', 'metode' => 'Transfer BRI', 'jumlah' => 250000, 'keterangan' => 'Cuci AC'],
            ['id' => 13, 'id_pesanan' => 42, 'kode_pesanan' => 'ORD-0042', 'metode' => 'QRIS Toko', 'jumlah' => 150000, 'keterangan' => 'Sparepart'],
        ];

        return view('admin.dashboard', [
            'ringkasan' => [
                'pesanan_menunggu' => 4,
                'jadwal_hari_ini' => count($jadwalHariIni),
                'verifikasi_bayar' => 3,
                'stok_kritis' => count($stokKritis),
            ],
            'jadwalHariIni' => $jadwalHariIni,
            'verifikasiPembayaran' => $verifikasiPembayaran,
            'pesananBaru' => [
                ['id' => 45, 'kode' => 'ORD-0045', 'ringkasan' => 'Cuci AC Split 2 PK (3 unit)', 'status' => 'menunggu_teknisi'],
                ['id' => 44, 'kode' => 'ORD-0044', 'ringkasan' => 'Pipa tembaga dan gas freon', 'status' => 'siap_kirim'],
            ],
            'stokKritis' => $stokKritis,
            'pendaftarTeknisi' => [
                ['id' => 5, 'nama' => 'Ahmad Rizky', 'umur' => 26, 'domisili' => 'Minasatene', 'sertifikasi' => 'Sertifikasi BNSP Pendingin'],
            ],
        ]);
    }
}
