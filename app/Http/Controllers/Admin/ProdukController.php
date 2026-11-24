<?php

namespace App\Http\Controllers\Admin;

use App\Enums\KategoriProduk;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProdukRequest;
use App\Models\Produk;
use App\Services\ProdukService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    /**
     * Constructor dijalankan otomatis setiap controller ini dibuat. Laravel membuat object
     * ProdukService dan menyuntikkannya, jadi tidak perlu `new ProdukService()` di tiap method.
     */
    public function __construct(private readonly ProdukService $produkService) {}

    public function index(Request $request): View
    {
        $filter = [
            'cari' => trim((string) $request->query('cari', '')),
            'status' => (string) $request->query('status', ''),
        ];

        return view('admin.produk.index', [
            'jumlahProduk' => $this->produkService->jumlah(),
            'produk' => $this->produkService->daftar($filter['cari'], $filter['status']),
            'filter' => $filter,
            'pilihanStatus' => [
                ProdukService::STATUS_AKTIF => 'Aktif',
                ProdukService::STATUS_NONAKTIF => 'Nonaktif',
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.produk.create', [
            'opsiKategori' => KategoriProduk::opsi(),
            'produk' => new Produk(['stok' => 0, 'aktif' => true]),
        ]);
    }

    public function store(ProdukRequest $request): RedirectResponse
    {
        $produk = $this->produkService->simpan(
            $request->safe()->except(['gambar', 'hapus_gambar']),
            $request->file('gambar'),
        );

        return redirect()
            ->route('admin.produk.index')
            ->with('status', 'Produk '.$produk->nama.' berhasil ditambahkan.');
    }

    public function edit(Produk $produk): View
    {
        return view('admin.produk.edit', [
            'opsiKategori' => KategoriProduk::opsi(),
            'produk' => $produk,
        ]);
    }

    public function update(ProdukRequest $request, Produk $produk): RedirectResponse
    {
        $this->produkService->perbarui(
            $produk,
            $request->safe()->except(['gambar', 'hapus_gambar']),
            $request->file('gambar'),
            $request->boolean('hapus_gambar'),
        );

        return redirect()
            ->route('admin.produk.index')
            ->with('status', 'Produk '.$produk->nama.' berhasil diperbarui.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $this->produkService->hapus($produk);

        return redirect()
            ->route('admin.produk.index')
            ->with('status', 'Produk '.$produk->nama.' berhasil dihapus.');
    }
}
