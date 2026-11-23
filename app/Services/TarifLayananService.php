<?php

namespace App\Services;

use App\Models\JenisAc;
use App\Models\Layanan;
use App\Models\TarifLayanan;
use Illuminate\Database\Eloquent\Collection;

/**
 * Logika bisnis tarif per layanan dan jenis AC.
 */
class TarifLayananService
{
    public function __construct(
        private readonly TarifLayanan $tarifLayanan,
        private readonly Layanan $layanan,
        private readonly JenisAc $jenisAc,
    ) {}

    /**
     * Semua tarif lengkap dengan layanan dan jenis AC-nya.
     *
     * @return Collection<int, TarifLayanan>
     */
    public function semua(): Collection
    {
        return $this->tarifLayanan->newQuery()
            ->with(['layanan', 'jenisAc'])
            ->get();
    }

    /**
     * Matriks tarif untuk ditampilkan di admin. Indeks pertama adalah id layanan,
     * indeks kedua adalah id jenis AC. Nilai null berarti tidak tersedia.
     *
     * @return array<int, array<int, int|null>>
     */
    public function matriks(): array
    {
        $matriks = [];

        foreach ($this->semua() as $baris) {
            $matriks[$baris->id_layanan][$baris->id_jenis_ac] = $baris->tarif;
        }

        return $matriks;
    }

    /**
     * Tarif milik satu layanan, diurutkan menurut nama jenis AC.
     *
     * @return Collection<int, TarifLayanan>
     */
    public function untukLayanan(Layanan $layanan): Collection
    {
        return $this->tarifLayanan->newQuery()
            ->with('jenisAc')
            ->where('id_layanan', $layanan->getKey())
            ->get()
            ->sortBy(fn (TarifLayanan $baris): string => (string) $baris->jenisAc?->nama)
            ->values();
    }

    /**
     * Tabel tarif satu layanan untuk semua jenis AC yang tersedia.
     * Jenis AC tanpa tarif tetap tampil dengan nilai null agar pelanggan tahu kombinasinya tidak ada.
     *
     * @return array<int, array{id: int, nama: string, keterangan: string|null, tarif: int|null}>
     */
    public function tabelLayanan(Layanan $layanan): array
    {
        $tarif = $this->matriks()[$layanan->getKey()] ?? [];

        return $this->jenisAc->newQuery()
            ->orderBy('nama')
            ->get()
            ->map(fn (JenisAc $jenis): array => [
                'id' => $jenis->getKey(),
                'nama' => $jenis->nama,
                'keterangan' => $jenis->keterangan,
                'tarif' => $tarif[$jenis->getKey()] ?? null,
            ])
            ->all();
    }

    /**
     * Cari tarif untuk satu kombinasi layanan dan jenis AC.
     */
    public function cari(int $idLayanan, int $idJenisAc): ?TarifLayanan
    {
        return $this->tarifLayanan->newQuery()
            ->where('id_layanan', $idLayanan)
            ->where('id_jenis_ac', $idJenisAc)
            ->first();
    }

    /**
     * Simpan matriks tarif dari form admin. Kombinasi yang sudah tidak ada di database
     * diabaikan, dan baris yang dikosongkan berarti layanan tidak tersedia untuk jenis AC itu.
     *
     * @param  array<int|string, array<int|string, int|null>>  $matriks
     */
    public function simpanMatriks(array $matriks): void
    {
        $idLayanan = $this->layanan->newQuery()->pluck('id_layanan')->all();
        $idJenisAc = $this->jenisAc->newQuery()->pluck('id_jenis_ac')->all();

        foreach ($matriks as $idLayananRow => $baris) {
            foreach ($baris as $idJenisAcKolom => $nilai) {
                $idLayananRow = (int) $idLayananRow;
                $idJenisAcKolom = (int) $idJenisAcKolom;

                if (! in_array($idLayananRow, $idLayanan, true) || ! in_array($idJenisAcKolom, $idJenisAc, true)) {
                    continue;
                }

                $this->tarifLayanan->newQuery()->updateOrCreate(
                    [
                        'id_layanan' => $idLayananRow,
                        'id_jenis_ac' => $idJenisAcKolom,
                    ],
                    ['tarif' => $nilai === null || $nilai === '' ? null : (int) $nilai],
                );
            }
        }
    }
}
