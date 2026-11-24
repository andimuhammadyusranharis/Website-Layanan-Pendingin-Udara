<?php

namespace App\Http\Controllers;

use App\Enums\KategoriLayanan;
use App\Models\Layanan;
use App\Services\EstimasiService;
use App\Services\LayananService;
use App\Services\TarifLayananService;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function __construct(
        private readonly LayananService $layananService,
        private readonly TarifLayananService $tarifLayananService,
        private readonly EstimasiService $estimasiService,
    ) {}

    /**
     * Tampilkan daftar layanan aktif dari database, sama dengan yang dikelola admin.
     * Tombol saring hanya memuat kategori yang punya layanan aktif. Simulasi estimasi masih dummy.
     */
    public function index(): View
    {
        $layanan = $this->layananService->daftarAktif();

        return view('pengguna.layanan', [
            'kategoriLayanan' => $this->kategoriTersedia($layanan),
            'layanan' => $layanan
                ->map(fn (Layanan $item): array => $this->untukTampilan($item))
                ->all(),
            'simulasiEstimasi' => $this->estimasiService->contoh(),
        ]);
    }

    /**
     * Halaman detail satu layanan aktif. Slug yang salah atau layanan nonaktif menghasilkan 404.
     */
    public function show(string $slug): View
    {
        $layanan = $this->layananService->cariAktif($slug);

        return view('pengguna.layanan-detail', [
            'layanan' => $this->untukTampilan($layanan),
            'tarifLayanan' => $this->tarifLayananService->tabelLayanan($layanan),
            'layananTerkait' => $this->layananService->terkait($layanan)
                ->map(fn (Layanan $item): array => $this->untukTampilan($item))
                ->all(),
        ]);
    }

    /**
     * @param  Collection<int, Layanan>  $layanan
     * @return list<array{slug: string, nama: string}>
     */
    private function kategoriTersedia(Collection $layanan): array
    {
        return collect(KategoriLayanan::cases())
            ->filter(fn (KategoriLayanan $kategori): bool => $layanan->contains(fn (Layanan $item): bool => $item->kategori === $kategori))
            ->map(fn (KategoriLayanan $kategori): array => ['slug' => $kategori->value, 'nama' => $kategori->label()])
            ->values()
            ->all();
    }

    /**
     * @return array{id: int, slug: string, nama: string, kategori: string, kategori_label: ?string, deskripsi_lengkap: string, jenis_ac: ?string, durasi: ?string, deskripsi: string, fitur: list<string>, keterangan_harga: string, harga_mulai: ?int, satuan: ?string, catatan_harga: ?string, perlu_survei: bool, gambar: ?string}
     */
    private function untukTampilan(Layanan $layanan): array
    {
        return [
            'id' => $layanan->id_layanan,
            'slug' => $layanan->slug,
            'nama' => $layanan->nama,
            'kategori' => $layanan->kategori?->value ?? '',
            'kategori_label' => $layanan->kategori?->label(),
            'deskripsi_lengkap' => $layanan->deskripsi,
            'jenis_ac' => $layanan->jenis_ac,
            'durasi' => $layanan->durasi,
            'deskripsi' => $layanan->ringkasan,
            'fitur' => $layanan->fitur ?? [],
            'keterangan_harga' => $layanan->keterangan_harga,
            'harga_mulai' => $layanan->tarif_mulai,
            'satuan' => $layanan->satuan,
            'catatan_harga' => $layanan->catatan_harga,
            'perlu_survei' => $layanan->perlu_survei || $layanan->tarif_mulai === null,
            'gambar' => $layanan->url_gambar,
        ];
    }
}
