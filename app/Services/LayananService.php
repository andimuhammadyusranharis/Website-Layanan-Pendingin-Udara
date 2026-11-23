<?php

namespace App\Services;

use App\Models\Layanan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Logika bisnis layanan. Controller cukup memanggil class ini, sehingga akses database
 * dan penyimpanan foto tidak tersebar.
 */
class LayananService
{
    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const DISK_GAMBAR = 'public';

    public const FOLDER_GAMBAR = 'layanan';

    /**
     * Constructor dijalankan otomatis oleh service container Laravel saat class ini dibutuhkan.
     * Object Layanan ikut disuntikkan dan dipakai sebagai pintu masuk query.
     */
    public function __construct(private readonly Layanan $layanan) {}

    /**
     * @return Collection<int, Layanan>
     */
    public function daftar(string $cari = '', string $status = ''): Collection
    {
        return $this->layanan->newQuery()
            ->when($cari !== '', fn ($query) => $query->where('nama', 'like', '%'.$cari.'%'))
            ->when($status !== '', fn ($query) => $query->where('aktif', $status === self::STATUS_AKTIF))
            ->orderBy('urutan')
            ->orderBy('id_layanan')
            ->get();
    }

    /**
     * Layanan yang boleh dilihat pelanggan.
     *
     * @return Collection<int, Layanan>
     */
    public function daftarAktif(): Collection
    {
        return $this->daftar(status: self::STATUS_AKTIF);
    }

    /**
     * Cari layanan aktif berdasarkan slug. Layanan nonaktif dianggap tidak ada, jadi hasilnya 404.
     */
    public function cariAktif(string $slug): Layanan
    {
        return $this->layanan->newQuery()
            ->where('aktif', true)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Layanan aktif lain untuk rekomendasi di halaman detail. Layanan sekategori didahulukan.
     *
     * @return Collection<int, Layanan>
     */
    public function terkait(Layanan $layanan, int $batas = 3): Collection
    {
        return $this->daftarAktif()
            ->reject(fn (Layanan $item): bool => $item->is($layanan))
            ->sortBy(fn (Layanan $item): int => $item->kategori === $layanan->kategori ? 0 : 1)
            ->values()
            ->take($batas);
    }

    public function jumlah(): int
    {
        return $this->layanan->newQuery()->count();
    }

    /**
     * Pilihan status untuk filter di halaman admin.
     *
     * @return array<string, string>
     */
    public function pilihanStatus(): array
    {
        return [
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_NONAKTIF => 'Nonaktif',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function simpan(array $data, ?UploadedFile $gambar = null): Layanan
    {
        $data['slug'] = $this->buatSlug((string) $data['nama']);

        if ($gambar !== null) {
            $data['gambar'] = $this->simpanGambar($gambar);
        }

        return $this->layanan->newQuery()->create($data);
    }

    /**
     * Slug dibuat sekali saat layanan dibuat dan tidak ikut berubah bila nama diganti,
     * supaya tautan yang sudah dibagikan tetap bisa dibuka.
     *
     * @param  array<string, mixed>  $data
     */
    public function perbarui(Layanan $layanan, array $data, ?UploadedFile $gambar = null, bool $hapusGambar = false): Layanan
    {
        if ($gambar !== null) {
            $this->hapusBerkas($layanan->gambar);
            $data['gambar'] = $this->simpanGambar($gambar);
        } elseif ($hapusGambar) {
            $this->hapusBerkas($layanan->gambar);
            $data['gambar'] = null;
        }

        $layanan->update($data);

        return $layanan;
    }

    public function hapus(Layanan $layanan): void
    {
        $this->hapusBerkas($layanan->gambar);

        $layanan->delete();
    }

    private function buatSlug(string $nama): string
    {
        $dasar = Str::slug($nama) ?: 'layanan';
        $slug = $dasar;
        $nomor = 2;

        while ($this->layanan->newQuery()->where('slug', $slug)->exists()) {
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
