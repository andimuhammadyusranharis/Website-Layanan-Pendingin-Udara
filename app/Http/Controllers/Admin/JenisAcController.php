<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JenisAcRequest;
use App\Models\JenisAc;
use App\Services\JenisAcService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JenisAcController extends Controller
{
    /**
     * Constructor dijalankan otomatis setiap controller ini dibuat. Service container
     * menyuntikkan JenisAcService, jadi tidak perlu `new JenisAcService()` di tiap method.
     */
    public function __construct(private readonly JenisAcService $jenisAcService) {}

    /**
     * Daftar jenis AC sudah ditampilkan di tab admin layanan, jadi halaman ini dialihkan ke tab tersebut.
     */
    public function index(): RedirectResponse
    {
        return redirect()
            ->route('admin.layanan.index', ['tab' => 'jenis_ac']);
    }

    public function create(): View
    {
        return view('admin.layanan.jenis-ac.create');
    }

    public function store(JenisAcRequest $request): RedirectResponse
    {
        $jenisAc = $this->jenisAcService->simpan(
            $request->safe()->only(['nama', 'keterangan']),
        );

        return redirect()
            ->route('admin.layanan.index', ['tab' => 'jenis_ac'])
            ->with('status', 'Jenis AC '.$jenisAc->nama.' berhasil ditambahkan.');
    }

    public function edit(JenisAc $jenisAc): View
    {
        return view('admin.layanan.jenis-ac.edit', compact('jenisAc'));
    }

    public function update(JenisAcRequest $request, JenisAc $jenisAc): RedirectResponse
    {
        $this->jenisAcService->perbarui(
            $jenisAc,
            $request->safe()->only(['nama', 'keterangan']),
        );

        return redirect()
            ->route('admin.layanan.index', ['tab' => 'jenis_ac'])
            ->with('status', 'Jenis AC '.$jenisAc->nama.' berhasil diperbarui.');
    }

    public function destroy(JenisAc $jenisAc): RedirectResponse
    {
        $nama = $jenisAc->nama;

        $this->jenisAcService->hapus($jenisAc);

        return redirect()
            ->route('admin.layanan.index', ['tab' => 'jenis_ac'])
            ->with('status', 'Jenis AC '.$nama.' berhasil dihapus.');
    }
}
