<?php

namespace App\Http\Controllers;

use App\Services\EstimasiService;
use App\Services\JenisAcService;
use App\Services\LayananService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EstimasiController extends Controller
{
    public function __construct(
        private readonly LayananService $layananService,
        private readonly JenisAcService $jenisAcService,
        private readonly EstimasiService $estimasiService,
    ) {}

    public function index(Request $request): View
    {
        $layananAktif = $this->layananService->daftarAktif();
        $layanan = $layananAktif->map(
            fn ($item): array => ['id' => $item->id_layanan, 'nama' => $item->nama]
        )->all();

        $jenisAcKoleksi = $this->jenisAcService->daftar();
        $jenisAc = $jenisAcKoleksi->map(
            fn ($item): array => ['id' => $item->id_jenis_ac, 'nama' => $item->nama]
        )->all();

        $terpilihLayanan = (int) $request->query('layanan', 0);
        $terpilihJenis = (int) $request->query('jenis_ac', 0);
        $jumlah = (int) $request->query('jumlah', 1);

        $estimasi = null;
        $galat = null;

        $layananTerpilih = $layananAktif->firstWhere('id_layanan', $terpilihLayanan);
        $jenisAcTerpilih = $jenisAcKoleksi->firstWhere('id_jenis_ac', $terpilihJenis);

        if ($terpilihLayanan > 0 && $terpilihJenis > 0) {
            if ($jumlah < 1 || $jumlah > 1000) {
                $galat = 'Jumlah unit harus antara 1 sampai 1000.';
            } elseif ($layananTerpilih === null || $jenisAcTerpilih === null) {
                $galat = 'Layanan atau jenis AC tidak tersedia.';
            } else {
                $hasil = $this->estimasiService->hitung(
                    $layananTerpilih->id_layanan,
                    $jenisAcTerpilih->id_jenis_ac,
                    $jumlah,
                );

                if ($hasil === null) {
                    $galat = 'Layanan ini tidak tersedia untuk jenis AC tersebut.';
                } else {
                    $estimasi = $hasil + [
                        'layanan' => $layananTerpilih->nama,
                        'jenis_ac' => $jenisAcTerpilih->nama,
                        'jumlah' => $jumlah,
                    ];
                }
            }
        }

        return view('pengguna.estimasi', compact(
            'layanan',
            'jenisAc',
            'estimasi',
            'galat',
            'terpilihLayanan',
            'terpilihJenis',
            'jumlah',
        ));
    }
}
