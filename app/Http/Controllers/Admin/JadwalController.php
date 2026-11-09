<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JadwalController extends Controller
{
    /**
     * Jadwal kunjungan teknisi. Datanya masih dummy sampai tabel jadwal tersedia.
     */
    public function index(Request $request): View
    {
        $pilihanTanggal = ['hari_ini' => 'Hari ini', 'besok' => 'Besok', '7_hari' => '7 hari ke depan'];

        $filter = [
            'cari' => trim((string) $request->query('cari', '')),
            'tanggal' => array_key_exists((string) $request->query('tanggal'), $pilihanTanggal) ? (string) $request->query('tanggal') : 'hari_ini',
            'teknisi' => (string) $request->query('teknisi', ''),
            'status' => (string) $request->query('status', ''),
        ];

        $jadwal = collect(self::jadwalDummy())
            ->filter(fn (array $item): bool => match ($filter['tanggal']) {
                'besok' => Carbon::parse($item['tanggal'])->isTomorrow(),
                '7_hari' => Carbon::parse($item['tanggal'])->between(today(), today()->addDays(6)),
                default => Carbon::parse($item['tanggal'])->isToday(),
            })
            ->when($filter['cari'] !== '', fn ($data) => $data->filter(
                fn (array $item): bool => Str::contains($item['pelanggan'].' '.$item['alamat'], $filter['cari'], ignoreCase: true),
            ))
            ->when($filter['teknisi'] !== '', fn ($data) => $data->where('teknisi', $filter['teknisi']))
            ->when($filter['status'] !== '', fn ($data) => $data->where('status', $filter['status']))
            ->sortBy(['tanggal', 'mulai']);

        return view('admin.jadwal.index', [
            'jadwal' => $this->paginasiDummy($jadwal->values(), $request),
            'filter' => $filter,
            'pilihanTanggal' => $pilihanTanggal,
            'pilihanTeknisi' => ['Pak Haris' => 'Pak Haris', 'Pak Syamsul' => 'Pak Syamsul'],
            'pilihanStatus' => ['dijadwalkan' => 'Dijadwalkan', 'dikerjakan' => 'Dikerjakan', 'selesai' => 'Selesai'],
        ]);
    }

    /**
     * Jadwal dummy hari ini dan besok.
     *
     * @return list<array{id: int, id_pesanan: int, tanggal: string, mulai: string, selesai: string, pelanggan: string, alamat: string, layanan: string, teknisi: string, status: string}>
     */
    public static function jadwalDummy(): array
    {
        $data = [
            [0, '07:30', '08:30', 'Raihan Nur Faiz', 'Jl. Sultan Hasanuddin No. 12, Pangkajene', 'Cuci AC Split 2 PK (3 unit)', 'Pak Haris', 'selesai'],
            [0, '09:00', '10:00', 'Nurul Hikmah', 'Perum Bonto Perak Blok C3, Pangkajene', 'Perbaikan AC Cassette', 'Pak Haris', 'dijadwalkan'],
            [0, '09:00', '10:30', 'Muh. Fadli', 'Jl. Poros Makassar-Pare Km 3, Bungoro', 'Isi freon Split 1 PK', 'Pak Haris', 'dijadwalkan'],
            [0, '10:00', '11:00', 'Andi Sulastri', 'Jl. Kemakmuran No. 5, Minasatene', 'Pasang AC baru 1 PK', 'Pak Syamsul', 'dikerjakan'],
            [0, '13:00', '14:00', 'Rahmat Hidayat', 'BTN Pangkep Permai Blok A7, Pangkajene', 'Cuci AC Split 1 PK', 'Pak Syamsul', 'dijadwalkan'],
            [0, '15:00', '16:00', 'Sri Wahyuni', 'Jl. Andi Mauraga No. 20, Labakkang', 'Konsultasi dan survei', 'Pak Haris', 'dijadwalkan'],
            [1, '08:00', '09:00', 'Hj. Nurhaedah', 'Kel. Pangkajene', 'Cuci AC Split 1 PK (2 unit)', 'Pak Haris', 'dijadwalkan'],
            [1, '10:00', '12:00', 'Ibu Sitti Aminah', 'Kec. Segeri', 'Bongkar pasang AC 1 PK', 'Pak Syamsul', 'dijadwalkan'],
        ];

        return collect($data)->map(fn (array $baris, int $indeks): array => [
            'id' => $indeks + 1,
            'id_pesanan' => 45 - $indeks,
            'tanggal' => today()->addDays($baris[0])->toDateString(),
            'mulai' => $baris[1],
            'selesai' => $baris[2],
            'pelanggan' => $baris[3],
            'alamat' => $baris[4],
            'layanan' => $baris[5],
            'teknisi' => $baris[6],
            'status' => $baris[7],
        ])->all();
    }
}
