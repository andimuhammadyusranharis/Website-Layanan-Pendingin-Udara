<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UlasanRequest;
use App\Models\Ulasan;
use App\Services\UlasanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UlasanController extends Controller
{
    /**
     * Constructor dijalankan otomatis setiap controller ini dibuat. Laravel membuat object
     * UlasanService dan menyuntikkannya, jadi tidak perlu `new UlasanService()` di tiap method.
     */
    public function __construct(private readonly UlasanService $ulasanService) {}

    public function index(Request $request): View
    {
        $filter = [
            'cari' => trim((string) $request->query('cari', '')),
            'status' => (string) $request->query('status', ''),
        ];

        return view('admin.ulasan.index', [
            'jumlahUlasan' => $this->ulasanService->jumlah(),
            'ulasan' => $this->ulasanService->daftar($filter['cari'], $filter['status']),
            'filter' => $filter,
            'pilihanStatus' => [
                UlasanService::STATUS_TAMPIL => 'Ditampilkan',
                UlasanService::STATUS_TERSEMBUNYI => 'Disembunyikan',
            ],
        ]);
    }

    /**
     * Laravel mencari Ulasan dari angka di alamat (route model binding). Kalau tidak ada, hasilnya 404.
     */
    public function ubahTampilan(UlasanRequest $request, Ulasan $ulasan): RedirectResponse
    {
        $tampil = $request->boolean('tampil');

        $this->ulasanService->aturTampil($ulasan, $tampil);

        return redirect()
            ->route('admin.ulasan.index')
            ->with('status', 'Ulasan dari '.$ulasan->nama_pelanggan.($tampil ? ' sekarang ditampilkan.' : ' sekarang disembunyikan.'));
    }
}
