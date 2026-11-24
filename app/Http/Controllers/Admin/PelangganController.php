<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PelangganService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelangganController extends Controller
{
    public function __construct(private readonly PelangganService $pelangganService) {}

    public function index(Request $request): View
    {
        $cari = trim((string) $request->query('cari', ''));

        return view('admin.pelanggan.index', [
            'pelanggan' => $this->pelangganService->daftar($cari),
            'jumlah' => $this->pelangganService->jumlah(),
            'filter' => ['cari' => $cari],
        ]);
    }

    public function show(int $pelanggan): View
    {
        return view('admin.pelanggan.show', [
            'user' => $this->pelangganService->cari($pelanggan),
        ]);
    }
}
