<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UlasanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UlasanController extends Controller
{
    public function __construct(
        private readonly UlasanService $ulasanService
    ) {}

    public function index(): View
    {
        return view('admin.ulasan.index', [
            'ulasan' => $this->ulasanService->getSemuaUlasan(),
        ]);
    }

    public function ubahTampilan(int $id): RedirectResponse
    {
        $this->ulasanService->ubahTampilan($id);

        return redirect()
            ->route('admin.ulasan.index')
            ->with('status', 'Status tampilan ulasan berhasil diperbarui.');
    }
}
