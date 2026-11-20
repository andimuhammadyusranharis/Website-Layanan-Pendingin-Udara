<?php

namespace App\Services;

use App\Models\Ulasan;
use Illuminate\Database\Eloquent\Collection;

/**
 * Logika bisnis ulasan. Controller cukup memanggil class ini, sehingga query dan
 * perhitungan rating tidak ditulis di controller maupun di view.
 */
class UlasanService
{
    public const STATUS_TAMPIL = 'tampil';

    public const STATUS_TERSEMBUNYI = 'tersembunyi';

    /**
     * Rating dari angka ini ke atas dihitung sebagai pelanggan puas.
     */
    public const BATAS_PUAS = 4;

    /**
     * Constructor dijalankan otomatis oleh service container Laravel saat class ini dibutuhkan.
     * Object Ulasan ikut disuntikkan dan dipakai sebagai pintu masuk query.
     */
    public function __construct(private readonly Ulasan $ulasan) {}

    /**
     * Semua ulasan untuk halaman admin, terbaru lebih dulu.
     *
     * @return Collection<int, Ulasan>
     */
    public function daftar(string $cari = '', string $status = ''): Collection
    {
        return $this->ulasan->newQuery()
            ->when($cari !== '', fn ($query) => $query->where('nama_pelanggan', 'like', '%'.$cari.'%'))
            ->when($status !== '', fn ($query) => $query->where('tampil', $status === self::STATUS_TAMPIL))
            ->latest()
            ->orderByDesc('id_ulasan')
            ->get();
    }

    /**
     * Ulasan yang boleh dilihat pelanggan.
     *
     * @return Collection<int, Ulasan>
     */
    public function daftarTampil(?int $batas = null): Collection
    {
        return $this->ulasan->newQuery()
            ->where('tampil', true)
            ->latest()
            ->orderByDesc('id_ulasan')
            ->when($batas !== null, fn ($query) => $query->limit($batas))
            ->get();
    }

    /**
     * Rata-rata, jumlah, persen puas, dan sebaran rating dari ulasan yang tampil.
     * Bentuk datanya sama dengan yang dipakai view Ulasan dan beranda.
     *
     * @return array{rata_rata: float, jumlah: int, persen_puas: int, sebaran: array<int, int>}
     */
    public function ringkasan(): array
    {
        $jumlahPerRating = $this->ulasan->newQuery()
            ->where('tampil', true)
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $sebaran = [];
        foreach ([5, 4, 3, 2, 1] as $bintang) {
            $sebaran[$bintang] = (int) ($jumlahPerRating[$bintang] ?? 0);
        }

        $jumlah = array_sum($sebaran);

        if ($jumlah === 0) {
            return ['rata_rata' => 0.0, 'jumlah' => 0, 'persen_puas' => 0, 'sebaran' => $sebaran];
        }

        $totalBintang = 0;
        $jumlahPuas = 0;
        foreach ($sebaran as $bintang => $banyak) {
            $totalBintang += $bintang * $banyak;

            if ($bintang >= self::BATAS_PUAS) {
                $jumlahPuas += $banyak;
            }
        }

        return [
            'rata_rata' => round($totalBintang / $jumlah, 1),
            'jumlah' => $jumlah,
            'persen_puas' => (int) round($jumlahPuas / $jumlah * 100),
            'sebaran' => $sebaran,
        ];
    }

    public function cari(int $id): Ulasan
    {
        return $this->ulasan->newQuery()->findOrFail($id);
    }

    /**
     * Tampilkan atau sembunyikan ulasan dari halaman pelanggan.
     */
    public function aturTampil(Ulasan $ulasan, bool $tampil): Ulasan
    {
        $ulasan->update(['tampil' => $tampil]);

        return $ulasan;
    }

    public function jumlah(): int
    {
        return $this->ulasan->newQuery()->count();
    }
}
