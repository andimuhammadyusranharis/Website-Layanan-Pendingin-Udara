<?php

namespace App\Services;

use App\Models\TarifLayanan;

/**
 * Perhitungan estimasi biaya untuk pelanggan: tarif per unit dikali jumlah unit.
 */
class EstimasiService
{
    public function __construct(private readonly TarifLayananService $tarifLayananService) {}

    /**
     * Hitung estimasi biaya. Hasil null berarti layanan itu tidak tersedia untuk jenis AC tersebut.
     *
     * @return array{tarif: int, total: int}|null
     */
    public function hitung(int $idLayanan, int $idJenisAc, int $jumlah): ?array
    {
        $baris = $this->tarifLayananService->cari($idLayanan, $idJenisAc);

        if ($baris === null || $baris->tarif === null) {
            return null;
        }

        return [
            'tarif' => $baris->tarif,
            'total' => $baris->tarif * $jumlah,
        ];
    }

    /**
     * Contoh estimasi untuk halaman daftar layanan, diambil dari tarif yang benar-benar tersimpan.
     *
     * @return array{judul: string, rincian: list<array{nama: string, biaya: int}>, total: int}
     */
    public function contoh(int $jumlahUnit = 3): array
    {
        $baris = $this->tarifLayananService->semua()
            ->filter(fn (TarifLayanan $item): bool => $item->tarif !== null && $item->layanan?->aktif === true)
            ->sortBy(fn (TarifLayanan $item): int => $item->layanan->urutan)
            ->first();

        if ($baris === null) {
            return [
                'judul' => 'Tarif belum tersedia',
                'rincian' => [],
                'total' => 0,
            ];
        }

        $total = $baris->tarif * $jumlahUnit;

        return [
            'judul' => $baris->layanan->nama.' '.$jumlahUnit.' unit '.$baris->jenisAc->nama,
            'rincian' => [
                ['nama' => $jumlahUnit.' unit '.$baris->jenisAc->nama, 'biaya' => $total],
            ],
            'total' => $total,
        ];
    }
}
