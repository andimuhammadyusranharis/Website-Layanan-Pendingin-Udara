<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    /**
     * Verifikasi bukti bayar, riwayat pembayaran, dan setoran tunai teknisi. Datanya masih dummy
     * sampai tabel pembayaran tersedia.
     */
    public function index(Request $request): View
    {
        $tab = in_array($request->query('tab'), ['semua', 'setoran'], true) ? (string) $request->query('tab') : 'verifikasi';

        $filter = [
            'cari' => trim((string) $request->query('cari', '')),
            'metode' => (string) $request->query('metode', ''),
            'tanggal' => (string) $request->query('tanggal', ''),
        ];

        $semuaPembayaran = collect(self::pembayaranDummy());
        $setoranTunai = collect(self::setoranDummy());

        $data = ($tab === 'setoran' ? $setoranTunai : $semuaPembayaran)
            ->when($tab === 'verifikasi', fn ($data) => $data->where('status', 'menunggu'))
            ->when($filter['cari'] !== '', fn ($data) => $data->filter(
                fn (array $item): bool => Str::contains($item['pelanggan'].' '.$item['kode_pesanan'].' '.($item['teknisi'] ?? ''), $filter['cari'], ignoreCase: true),
            ))
            ->when($filter['metode'] !== '' && $tab !== 'setoran', fn ($data) => $data->where('metode', $filter['metode']))
            ->when($filter['tanggal'] !== '', fn ($data) => $data->filter(
                fn (array $item): bool => PesananController::cocokRentangTanggal($item['waktu'], $filter['tanggal']),
            ));

        return view('admin.pembayaran.index', [
            'tab' => $tab,
            'jumlahPerluVerifikasi' => $semuaPembayaran->where('status', 'menunggu')->count(),
            'pembayaran' => $this->paginasiDummy($data->values(), $request),
            'filter' => $filter,
            'pilihanMetode' => ['transfer_bri' => 'Transfer BRI', 'qris_toko' => 'QRIS Toko', 'tunai' => 'Tunai'],
            'pilihanTanggal' => ['hari_ini' => 'Hari ini', '7_hari' => '7 hari terakhir', 'bulan_ini' => 'Bulan ini'],
        ]);
    }

    /**
     * Pembayaran dummy, terbaru lebih dulu. Status: menunggu, disetujui, ditolak.
     *
     * @return list<array{id: int, id_pesanan: int, kode_pesanan: string, pelanggan: string, layanan: string, jumlah: int, metode: string, bukti: ?string, waktu: string, status: string}>
     */
    public static function pembayaranDummy(): array
    {
        $data = [
            [41, 'Rahmat Hidayat', 'Cuci AC Split 1 PK', 250000, 'transfer_bri', '08:12', 0, 'menunggu'],
            [42, 'Nurul Hikmah', 'Perbaikan AC Cassette', 150000, 'qris_toko', '07:40', 0, 'menunggu'],
            [45, 'Raihan Nur Faiz', 'Cuci AC Split 2 PK (3 unit)', 225000, 'transfer_bri', '19:05', 1, 'menunggu'],
            [39, 'Hj. Nurhaedah', 'Cuci AC Split 1 PK (2 unit)', 150000, 'tunai', '14:20', 2, 'disetujui'],
            [38, 'Ahmad Syarif', 'Ganti kapasitor', 185000, 'transfer_bri', '10:02', 3, 'disetujui'],
            [37, 'Drs. Ridwan M.', 'Cuci AC Cassette 3 PK', 300000, 'qris_toko', '16:45', 4, 'ditolak'],
            [36, 'Wahyu Pratama', 'Isi freon R32 dan tambah pipa', 390000, 'transfer_bri', '09:30', 6, 'disetujui'],
        ];

        return collect($data)->map(fn (array $baris, int $indeks): array => [
            'id' => 20 - $indeks,
            'id_pesanan' => $baris[0],
            'kode_pesanan' => sprintf('ORD-%04d', $baris[0]),
            'pelanggan' => $baris[1],
            'layanan' => $baris[2],
            'jumlah' => $baris[3],
            'metode' => $baris[4],
            'bukti' => $baris[4] === 'tunai' ? null : 'bukti-bayar/contoh.jpg',
            'waktu' => today()->subDays($baris[6])->setTimeFromTimeString($baris[5])->toDateTimeString(),
            'status' => $baris[7],
        ])->all();
    }

    /**
     * Uang tunai yang diterima teknisi di lokasi dan harus disetor ke kantor.
     *
     * @return list<array{id: int, id_pesanan: int, kode_pesanan: string, pelanggan: string, teknisi: string, jumlah: int, waktu: string, status: string}>
     */
    public static function setoranDummy(): array
    {
        return [
            ['id' => 3, 'id_pesanan' => 43, 'kode_pesanan' => 'ORD-0043', 'pelanggan' => 'Muh. Fadli', 'teknisi' => 'Pak Haris', 'jumlah' => 150000, 'waktu' => today()->setTime(11, 15)->toDateTimeString(), 'status' => 'belum_disetor'],
            ['id' => 2, 'id_pesanan' => 39, 'kode_pesanan' => 'ORD-0039', 'pelanggan' => 'Hj. Nurhaedah', 'teknisi' => 'Pak Haris', 'jumlah' => 150000, 'waktu' => today()->subDays(2)->setTime(14, 20)->toDateTimeString(), 'status' => 'disetor'],
            ['id' => 1, 'id_pesanan' => 34, 'kode_pesanan' => 'ORD-0034', 'pelanggan' => 'Syamsul Bahri', 'teknisi' => 'Pak Syamsul', 'jumlah' => 75000, 'waktu' => today()->subDays(5)->setTime(9, 40)->toDateTimeString(), 'status' => 'disetor'],
        ];
    }
}
