<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TarifLayananRequest;
use App\Services\JenisAcService;
use App\Services\LayananService;
use App\Services\TarifLayananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TarifLayananController extends Controller
{
    /**
     * Constructor dijalankan otomatis setiap controller ini dibuat.
     */
    public function __construct(
        private readonly LayananService $layananService,
        private readonly JenisAcService $jenisAcService,
        private readonly TarifLayananService $tarifLayananService,
    ) {}

    public function edit(): View
    {
        return view('admin.layanan.tarif.edit', [
            'layanan' => $this->layananService->daftar(),
            'jenisAc' => $this->jenisAcService->daftar(),
            'tarif' => $this->tarifLayananService->matriks(),
        ]);
    }

    public function update(TarifLayananRequest $request): RedirectResponse
    {
        $this->tarifLayananService->simpanMatriks(
            $request->validated('tarif', []),
        );

        return redirect()
            ->route('admin.layanan.index', ['tab' => 'tarif'])
            ->with('status', 'Tarif berhasil diperbarui.');
    }
}
