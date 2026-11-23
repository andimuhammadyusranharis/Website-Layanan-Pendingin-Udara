<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Logika bisnis produk toko. Controller admin dan halaman Toko cukup memanggil class ini,
 * sehingga akses database dan penyimpanan foto produk tidak tersebar.
 */
class ProdukService
{
    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const DISK_GAMBAR = 'public';

    public const FOLDER_GAMBAR = 'produk';

    /**
     * Constructor dijalankan otomatis oleh service container Laravel saat class ini dibutuhkan.
     * Object Produk ikut disuntikkan dan dipakai sebagai pintu masuk query.
     */
    public function __construct(private readonly Produk $produk) {}

    /**
     * Pencarian mencocokkan nama, merek, atau model, supaya admin bisa mencari "Daikin".
     *
     * @return Collection<int, Produk>
     */
    public function daftar(string $cari = '', string $status = ''): Collection
    {
        return $this->produk->newQuery()
            ->when($cari !== '', fn ($query) => $query->where(fn ($kelompok) => $kelompok
                ->where('nama', 'like', '%'.$cari.'%')
                ->orWhere('merek', 'like', '%'.$cari.'%')
                ->orWhere('model', 'like', '%'.$cari.'%')))
            ->when($status !== '', fn ($query) => $query->where('aktif', $status === self::STATUS_AKTIF))
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get();
    }

    /**
     * Produk yang boleh dilihat pelanggan di halaman Toko.
     *
     * @return Collection<int, Produk>
     */
    public function daftarAktif(): Collection
    {
        return $this->daftar(status: self::STATUS_AKTIF);
    }

    /**
     * Cari produk aktif berdasarkan slug. Produk nonaktif dianggap tidak ada, jadi hasilnya 404.
     */
    public function cariAktif(string $slug): Produk
    {
        return $this->produk->newQuery()
            ->where('aktif', true)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function jumlah(): int
    {
        return $this->produk->newQuery()->count();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function simpan(array $data, ?UploadedFile $gambar = null): Produk
    {
        $data['slug'] = $this->buatSlug((string) $data['nama']);

        if ($gambar !== null) {
            $data['gambar'] = $this->simpanGambar($gambar);
        }

        return $this->produk->newQuery()->create($data);
    }

    /**
     * Slug dibuat sekali saat produk dibuat dan tidak ikut berubah bila nama diganti,
     * supaya tautan yang sudah dibagikan tetap bisa dibuka.
     *
     * @param  array<string, mixed>  $data
     */
    public function perbarui(Produk $produk, array $data, ?UploadedFile $gambar = null, bool $hapusGambar = false): Produk
    {
        if ($gambar !== null) {
            $this->hapusBerkas($produk->gambar);
            $data['gambar'] = $this->simpanGambar($gambar);
        } elseif ($hapusGambar) {
            $this->hapusBerkas($produk->gambar);
            $data['gambar'] = null;
        }

        $produk->update($data);

        return $produk;
    }

    public function hapus(Produk $produk): void
    {
        $this->hapusBerkas($produk->gambar);

        $produk->delete();
    }

    private function buatSlug(string $nama): string
    {
        $dasar = Str::slug($nama) ?: 'produk';
        $slug = $dasar;
        $nomor = 2;

        while ($this->produk->newQuery()->where('slug', $slug)->exists()) {
            $slug = $dasar.'-'.$nomor++;
        }

        return $slug;
    }

    private function simpanGambar(UploadedFile $gambar): string
    {
        return (string) $gambar->store(self::FOLDER_GAMBAR, self::DISK_GAMBAR);
    }

    private function hapusBerkas(?string $path): void
    {
        if ($path !== null && $path !== '') {
            Storage::disk(self::DISK_GAMBAR)->delete($path);
        }
    }
}
