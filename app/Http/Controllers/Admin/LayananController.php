<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Kelola layanan, tarif per jenis AC, dan jenis AC. Datanya masih dummy sampai tabel layanan tersedia.
     */
    public function index(Request $request): View
    {
        $tab = in_array($request->query('tab'), ['tarif', 'jenis_ac'], true) ? (string) $request->query('tab') : 'layanan';

        $filter = [
            'cari' => trim((string) $request->query('cari', '')),
            'status' => (string) $request->query('status', ''),
        ];

        $semuaLayanan = collect(self::layananDummy());
        $jenisAc = collect(self::jenisAcDummy());

        $layanan = $semuaLayanan
            ->when($filter['cari'] !== '', fn ($data) => $data->filter(
                fn (array $item): bool => Str::contains($item['nama'], $filter['cari'], ignoreCase: true),
            ))
            ->when($filter['status'] !== '', fn ($data) => $data->where('aktif', $filter['status'] === 'aktif'));

        return view('admin.layanan.index', [
            'tab' => $tab,
            'jumlahLayanan' => $semuaLayanan->count(),
            'layanan' => $layanan->values()->all(),
            'jenisAc' => $jenisAc->filter(
                fn (array $item): bool => $filter['cari'] === '' || Str::contains($item['nama'], $filter['cari'], ignoreCase: true),
            )->values()->all(),
            'semuaJenisAc' => $jenisAc->all(),
            'tarif' => self::tarifDummy(),
            'filter' => $filter,
            'pilihanStatus' => ['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'],
        ]);
    }

    /**
     * @return list<array{id: int, nama: string, deskripsi: string, tarif_mulai: int, bagi_hasil: int, aktif: bool}>
     */
    public static function layananDummy(): array
    {
        return [
            ['id' => 1, 'nama' => 'Cuci AC', 'deskripsi' => 'Bersihkan unit indoor dan outdoor', 'tarif_mulai' => 75000, 'bagi_hasil' => 50, 'aktif' => true],
            ['id' => 2, 'nama' => 'Perbaikan', 'deskripsi' => 'Cek dan perbaiki kerusakan AC', 'tarif_mulai' => 100000, 'bagi_hasil' => 40, 'aktif' => true],
            ['id' => 3, 'nama' => 'Pasang baru', 'deskripsi' => 'Pasang unit AC baru beserta pipa', 'tarif_mulai' => 350000, 'bagi_hasil' => 40, 'aktif' => true],
            ['id' => 4, 'nama' => 'Bongkar pasang', 'deskripsi' => 'Pindahkan unit AC ke lokasi lain', 'tarif_mulai' => 250000, 'bagi_hasil' => 40, 'aktif' => true],
            ['id' => 5, 'nama' => 'Isi freon', 'deskripsi' => 'Tambah atau isi ulang freon', 'tarif_mulai' => 150000, 'bagi_hasil' => 40, 'aktif' => true],
            ['id' => 6, 'nama' => 'Konsultasi dan survei', 'deskripsi' => 'Untuk AC central, multi split, VRF, dan ducted', 'tarif_mulai' => 50000, 'bagi_hasil' => 50, 'aktif' => true],
            ['id' => 7, 'nama' => 'Paket cuci + isi freon', 'deskripsi' => 'Promo musim kemarau', 'tarif_mulai' => 200000, 'bagi_hasil' => 45, 'aktif' => false],
        ];
    }

    /**
     * @return list<array{id: int, nama: string, keterangan: string}>
     */
    public static function jenisAcDummy(): array
    {
        return [
            ['id' => 1, 'nama' => 'Split 1/2 - 1 PK', 'keterangan' => 'AC dinding rumah tangga'],
            ['id' => 2, 'nama' => 'Split 1,5 - 2 PK', 'keterangan' => 'AC dinding ruangan besar'],
            ['id' => 3, 'nama' => 'Cassette', 'keterangan' => 'AC plafon kantor dan toko'],
            ['id' => 4, 'nama' => 'Standing floor', 'keterangan' => 'AC berdiri untuk aula'],
        ];
    }

    /**
     * Tarif per layanan (kunci) dan jenis AC (kunci dalam). Null berarti layanan itu tidak tersedia untuk jenis AC tersebut.
     *
     * @return array<int, array<int, ?int>>
     */
    public static function tarifDummy(): array
    {
        return [
            1 => [1 => 75000, 2 => 100000, 3 => 200000, 4 => 250000],
            2 => [1 => 100000, 2 => 125000, 3 => 200000, 4 => 250000],
            3 => [1 => 350000, 2 => 450000, 3 => 750000, 4 => 850000],
            4 => [1 => 250000, 2 => 350000, 3 => 600000, 4 => 700000],
            5 => [1 => 150000, 2 => 200000, 3 => 350000, 4 => 400000],
            6 => [1 => null, 2 => null, 3 => 50000, 4 => 50000],
            7 => [1 => 200000, 2 => 275000, 3 => null, 4 => null],
        ];
    }
}
