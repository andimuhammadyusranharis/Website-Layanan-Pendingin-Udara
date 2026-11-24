<?php

namespace App\Services;

use App\Models\Layanan;
use App\Models\TarifLayanan;

/**
 * Perhitungan estimasi biaya untuk pelanggan: tarif per unit dikali jumlah unit.
 */
class EstimasiService
{
    public function __construct(
        private readonly TarifLayananService $tarifLayananService,
        private readonly LayananService $layananService,
        private readonly JenisAcService $jenisAcService,
        private readonly Layanan $layanan,
    ) {}

    /**
     * Siapkan pilihan form, hasil estimasi, serta pesan galat berdasarkan query input.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function dataHalaman(array $input): array
    {
        $layananAktif = $this->layananService->daftarAktif();
        $jenisAcKoleksi = $this->jenisAcService->daftar();
        $terpilihLayanan = (int) ($input['layanan'] ?? 0);
        $terpilihJenis = (int) ($input['jenis_ac'] ?? 0);
        $jumlah = (int) ($input['jumlah'] ?? 1);
        $estimasi = null;
        $galat = null;

        if ($terpilihLayanan > 0 && $terpilihJenis > 0) {
            if ($jumlah < 1 || $jumlah > 1000) {
                $galat = 'Jumlah unit harus antara 1 sampai 1000.';
            } else {
                $layananTerpilih = $layananAktif->firstWhere('id_layanan', $terpilihLayanan);
                $jenisAcTerpilih = $jenisAcKoleksi->firstWhere('id_jenis_ac', $terpilihJenis);

                if ($layananTerpilih === null || $jenisAcTerpilih === null) {
                    $galat = 'Layanan atau jenis AC tidak tersedia.';
                } else {
                    $hasil = $this->hitung($layananTerpilih->id_layanan, $jenisAcTerpilih->id_jenis_ac, $jumlah);

                    if ($hasil === null) {
                        $galat = 'Layanan ini tidak tersedia untuk jenis AC tersebut.';
                    } elseif ($hasil['status'] === 'survei') {
                        $galat = 'Layanan ini memerlukan survei. Silakan hubungi kami untuk konsultasi.';
                    } else {
                        $estimasi = $hasil + [
                            'layanan' => $layananTerpilih->nama,
                            'jenis_ac' => $jenisAcTerpilih->nama,
                            'jumlah' => $jumlah,
                        ];
                        unset($estimasi['status']);
                    }
                }
            }
        }

        return [
            'layanan' => $layananAktif->map(fn ($item): array => ['id' => $item->id_layanan, 'nama' => $item->nama])->all(),
            'jenisAc' => $jenisAcKoleksi->map(fn ($item): array => ['id' => $item->id_jenis_ac, 'nama' => $item->nama])->all(),
            'estimasi' => $estimasi,
            'galat' => $galat,
            'terpilihLayanan' => $terpilihLayanan,
            'terpilihJenis' => $terpilihJenis,
            'jumlah' => $jumlah,
        ];
    }

    /**
     * @return array{status: 'tarif', tarif: int, total: int}|array{status: 'survei'}|null
     */
    public function hitung(int $idLayanan, int $idJenisAc, int $jumlah): ?array
    {
        $layanan = $this->layanan->newQuery()->find($idLayanan);

        if ($layanan?->perlu_survei) {
            return ['status' => 'survei'];
        }

        $baris = $this->tarifLayananService->cari($idLayanan, $idJenisAc);

        if ($baris === null || $baris->tarif === null) {
            return null;
        }

        return [
            'status' => 'tarif',
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
