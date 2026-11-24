<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KategoriLayanan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LayananRequest;
use App\Models\JenisAc;
use App\Models\Layanan;
use App\Services\LayananService;
use App\Services\TarifLayananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Constructor dijalankan otomatis setiap controller ini dibuat. Laravel membuat object
     * LayananService dan menyuntikkannya, jadi tidak perlu `new LayananService()` di tiap method.
     */
    public function __construct(
        private readonly LayananService $layananService,
        private readonly TarifLayananService $tarifLayananService,
        private readonly JenisAc $jenisAcModel,
    ) {}

    /**
     * Kelola layanan, tarif per jenis AC, dan jenis AC. Layanan sudah dari database,
     * sedangkan tarif dan jenis AC masih dummy sampai tabelnya tersedia.
     */
    public function index(Request $request): View
    {
        $tab = $request->string('tab')->toString();
        if (! in_array($tab, ['layanan', 'tarif', 'jenis_ac'], true)) {
            $tab = 'layanan';
        }

        $filter = [
            'cari' => $request->string('cari')->trim()->toString(),
            'status' => $request->string('status')->trim()->toString(),
        ];

        $layanan = $this->layananService->daftar($filter['cari'], $filter['status']);
        $jenisAc = $this->jenisAcModel->newQuery()
            ->when($filter['cari'] !== '', fn ($query) => $query->where('nama', 'like', '%'.$filter['cari'].'%'))
            ->orderBy('nama')
            ->get();
        $semuaJenisAc = $this->jenisAcModel->newQuery()->orderBy('nama')->get();
        $tarif = $this->tarifLayananService->matriks();

        return view('admin.layanan.index', [
            'tab' => $tab,
            'jumlahLayanan' => $this->layananService->jumlah(),
            'layanan' => $layanan,
            'jenisAc' => $jenisAc,
            'semuaJenisAc' => $semuaJenisAc,
            'tarif' => $tarif,
            'filter' => $filter,
            'pilihanStatus' => $this->layananService->pilihanStatus(),
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
}
