<?php

namespace App\Http\Controllers;

use App\Enums\KategoriProduk;
use App\Models\Produk;
use App\Services\ProdukService;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TokoController extends Controller
{
    public function __construct(private readonly ProdukService $produkService) {}

    /**
     * Tampilkan katalog toko dari database, sama dengan yang dikelola admin.
     * Unit AC dan komponen dipisah karena bentuk kartunya berbeda.
     */
    public function index(): View
    {
        $produk = $this->produkService->daftarAktif();
        [$unitAc, $komponen] = $produk->partition(fn (Produk $item): bool => $item->kategori === KategoriProduk::UnitAc);

        return view('pengguna.toko', [
            'kategoriProduk' => $this->kategoriTersedia($produk),
            'merekPopuler' => $unitAc->pluck('merek')->filter()->unique()->sort()->values()->all(),
            'unitAc' => $unitAc->map(fn (Produk $item): array => $this->untukTampilan($item))->values()->all(),
            'produk' => $komponen->map(fn (Produk $item): array => $this->untukTampilan($item))->values()->all(),
        ]);
    }

    /**
     * Halaman detail satu produk aktif. Slug yang salah atau produk nonaktif menghasilkan 404.
     */
    public function show(string $slug): View
    {
        return view('pengguna.toko-detail', [
            'produk' => $this->untukTampilan($this->produkService->cariAktif($slug)),
        ]);
    }

    /**
     * Tombol saring hanya memuat kategori yang punya produk aktif.
     *
     * @param  Collection<int, Produk>  $produk
     * @return list<array{slug: string, nama: string}>
     */
    private function kategoriTersedia(Collection $produk): array
    {
        return collect(KategoriProduk::cases())
            ->filter(fn (KategoriProduk $kategori): bool => $produk->contains(fn (Produk $item): bool => $item->kategori === $kategori))
            ->map(fn (KategoriProduk $kategori): array => ['slug' => $kategori->value, 'nama' => $kategori->label()])
            ->values()
            ->all();
    }

    /**
     * Ubah model menjadi array dengan kunci yang dipakai view Toko. Merek dan model diubah menjadi
     * teks kosong bila tidak ada, karena view memakainya untuk Str::slug dan teks pencarian.
     *
     * @return array{id: int, slug: string, slug_kategori: string, kategori: string, nama: string, deskripsi: string, merek: string, model: string, spesifikasi: list<string>, harga: int, satuan: ?string, stok: int, gambar: ?string}
     */
    private function untukTampilan(Produk $produk): array
    {
        return [
            'id' => $produk->id_produk,
            'slug' => $produk->slug,
            'slug_kategori' => $produk->kategori->value,
            'kategori' => $produk->kategori->label(),
            'nama' => $produk->nama,
            'deskripsi' => $produk->deskripsi,
            'merek' => (string) $produk->merek,
            'model' => (string) $produk->model,
            'spesifikasi' => $produk->spesifikasi ?? [],
            'harga' => $produk->harga,
            'satuan' => $produk->satuan,
            'stok' => $produk->stok,
            'gambar' => $produk->url_gambar,
        ];
    }
}
