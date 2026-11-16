<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KategoriLayanan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LayananRequest;
use App\Models\Layanan;
use App\Services\LayananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Constructor dijalankan otomatis setiap controller ini dibuat. Laravel membuat object
     * LayananService dan menyuntikkannya, jadi tidak perlu `new LayananService()` di tiap method.
     */
    public function __construct(private readonly LayananService $layananService) {}

    /**
     * Kelola layanan, tarif per jenis AC, dan jenis AC. Layanan sudah dari database,
     * sedangkan tarif dan jenis AC masih dummy sampai tabelnya tersedia.
     */
    public function index(Request $request): View
    {
        $tab = in_array($request->query('tab'), ['tarif', 'jenis_ac'], true) ? (string) $request->query('tab') : 'layanan';

        $filter = [
            'cari' => trim((string) $request->query('cari', '')),
            'status' => (string) $request->query('status', ''),
        ];

        $jenisAc = collect(self::jenisAcDummy());

        return view('admin.layanan.index', [
            'tab' => $tab,
            'jumlahLayanan' => $this->layananService->jumlah(),
            'layanan' => $this->layananService->daftar($filter['cari'], $filter['status']),
            'jenisAc' => $jenisAc->filter(
                fn (array $item): bool => $filter['cari'] === '' || Str::contains($item['nama'], $filter['cari'], ignoreCase: true),
            )->values()->all(),
            'semuaJenisAc' => $jenisAc->all(),
            'tarif' => self::tarifDummy(),
            'filter' => $filter,
            'pilihanStatus' => [
                LayananService::STATUS_AKTIF => 'Aktif',
                LayananService::STATUS_NONAKTIF => 'Nonaktif',
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.layanan.create', [
            'opsiKategori' => KategoriLayanan::opsi(),
            'layanan' => new Layanan([
                'keterangan_harga' => 'Mulai dari',
                'bagi_hasil' => 40,
                'urutan' => 0,
                'aktif' => true,
            ]),
        ]);
    }

    public function store(LayananRequest $request): RedirectResponse
    {
        $layanan = $this->layananService->simpan(
            $request->safe()->except(['gambar', 'hapus_gambar']),
            $request->file('gambar'),
        );

        return redirect()
            ->route('admin.layanan.index')
            ->with('status', 'Layanan '.$layanan->nama.' berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan): View
    {
        return view('admin.layanan.edit', [
            'opsiKategori' => KategoriLayanan::opsi(),
            'layanan' => $layanan,
        ]);
    }

    public function update(LayananRequest $request, Layanan $layanan): RedirectResponse
    {
        $this->layananService->perbarui(
            $layanan,
            $request->safe()->except(['gambar', 'hapus_gambar']),
            $request->file('gambar'),
            $request->boolean('hapus_gambar'),
        );

        return redirect()
            ->route('admin.layanan.index')
            ->with('status', 'Layanan '.$layanan->nama.' berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan): RedirectResponse
    {
        $this->layananService->hapus($layanan);

        return redirect()
            ->route('admin.layanan.index')
            ->with('status', 'Layanan '.$layanan->nama.' berhasil dihapus.');
    }

    /**
     * @return list<array{id: int, nama: string, keterangan: string}>
     */
    private static function jenisAcDummy(): array
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
    private static function tarifDummy(): array
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
