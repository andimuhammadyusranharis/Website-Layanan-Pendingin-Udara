<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PesananController extends Controller
{
    /**
     * Daftar semua pesanan dari online, toko, dan telepon/WA. Datanya masih dummy sampai tabel pesanan tersedia.
     */
    public function index(Request $request): View
    {
        $filter = [
            'cari' => trim((string) $request->query('cari', '')),
            'status' => (string) $request->query('status', ''),
            'sumber' => (string) $request->query('sumber', ''),
            'tanggal' => (string) $request->query('tanggal', ''),
        ];

        $pesanan = collect(self::pesananDummy())
            ->when($filter['cari'] !== '', fn ($data) => $data->filter(
                fn (array $item): bool => Str::contains($item['pelanggan'].' '.$item['kode'], $filter['cari'], ignoreCase: true),
            ))
            ->when($filter['status'] !== '', fn ($data) => $data->where('status', $filter['status']))
            ->when($filter['sumber'] !== '', fn ($data) => $data->where('sumber', $filter['sumber']))
            ->when($filter['tanggal'] !== '', fn ($data) => $data->filter(
                fn (array $item): bool => self::cocokRentangTanggal($item['tanggal'], $filter['tanggal']),
            ));

        return view('admin.pesanan.index', [
            'pesanan' => $this->paginasiDummy($pesanan->values(), $request),
            'filter' => $filter,
            'pilihanStatus' => [
                'menunggu' => 'Menunggu',
                'dijadwalkan' => 'Dijadwalkan',
                'dikerjakan' => 'Dikerjakan',
                'dikirim' => 'Dikirim',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
            ],
            'pilihanSumber' => ['online' => 'Online', 'toko' => 'Toko', 'telepon' => 'Telepon/WA'],
            'pilihanTanggal' => ['hari_ini' => 'Hari ini', '7_hari' => '7 hari terakhir', 'bulan_ini' => 'Bulan ini'],
        ]);
    }

    /**
     * Cek apakah tanggal masuk ke rentang filter. Dipakai juga oleh halaman pembayaran.
     */
    public static function cocokRentangTanggal(string $tanggal, string $rentang): bool
    {
        $waktu = Carbon::parse($tanggal);

        return match ($rentang) {
            'hari_ini' => $waktu->isToday(),
            '7_hari' => $waktu->greaterThanOrEqualTo(today()->subDays(6)),
            'bulan_ini' => $waktu->isSameMonth(today()),
            default => true,
        };
    }

    /**
     * Pesanan dummy, terbaru lebih dulu. Tanggalnya relatif terhadap hari ini supaya filter tanggal tetap berguna.
     *
     * @return list<array{id: int, kode: string, pelanggan: string, ringkasan: string, tanggal: string, sumber: string, status: string}>
     */
    public static function pesananDummy(): array
    {
        $data = [
            ['Raihan Nur Faiz', 'Cuci AC Split 2 PK (3 unit)', 0, 'online', 'menunggu'],
            ['Andi Sulastri', 'Pipa tembaga & gas freon', 0, 'toko', 'dikirim'],
            ['Muh. Fadli', 'Isi freon Split 1 PK', 1, 'telepon', 'dijadwalkan'],
            ['Nurul Hikmah', 'Perbaikan AC Cassette', 1, 'online', 'dikerjakan'],
            ['Rahmat Hidayat', 'Cuci AC Split 1 PK', 2, 'toko', 'selesai'],
            ['Sri Wahyuni', 'Pasang AC baru 1 PK', 3, 'telepon', 'dibatalkan'],
            ['Hj. Nurhaedah', 'Cuci AC Split 1 PK (2 unit)', 4, 'online', 'selesai'],
            ['Ahmad Syarif', 'Ganti kapasitor', 5, 'telepon', 'selesai'],
            ['Drs. Ridwan M.', 'Cuci AC Cassette 3 PK', 6, 'online', 'selesai'],
            ['Wahyu Pratama', 'Isi freon R32 dan tambah pipa', 8, 'online', 'selesai'],
            ['Ibu Sitti Aminah', 'Bongkar pasang AC 1 PK', 10, 'telepon', 'selesai'],
            ['Muhammad Arifin', 'Remote AC universal', 12, 'toko', 'selesai'],
            ['Kartini Usman', 'Konsultasi dan survei', 15, 'online', 'dibatalkan'],
            ['Syamsul Bahri', 'Cuci AC Split 1/2 PK', 20, 'toko', 'selesai'],
        ];

        return collect($data)->map(fn (array $baris, int $indeks): array => [
            'id' => 45 - $indeks,
            'kode' => sprintf('ORD-%04d', 45 - $indeks),
            'pelanggan' => $baris[0],
            'ringkasan' => $baris[1],
            'tanggal' => today()->subDays($baris[2])->toDateString(),
            'sumber' => $baris[3],
            'status' => $baris[4],
        ])->all();
    }
}
