<?php

namespace Database\Seeders;

use App\Models\JenisAc;
use App\Models\Layanan;
use App\Models\TarifLayanan;
use Illuminate\Database\Seeder;

class TarifLayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanan = Layanan::query()->pluck('id_layanan', 'nama');
        $jenisAc = JenisAc::query()->pluck('id_jenis_ac', 'nama');

        $matrix = [
            'Cuci AC' => [
                'Split 1/2 - 1 PK' => 75000,
                'Split 1,5 - 2 PK' => 100000,
                'Cassette' => 200000,
                'Standing floor' => 250000,
            ],
            'Perbaikan' => [
                'Split 1/2 - 1 PK' => 100000,
                'Split 1,5 - 2 PK' => 125000,
                'Cassette' => 200000,
                'Standing floor' => 250000,
            ],
            'Pasang baru' => [
                'Split 1/2 - 1 PK' => 350000,
                'Split 1,5 - 2 PK' => 450000,
                'Cassette' => 750000,
                'Standing floor' => 850000,
            ],
            'Bongkar pasang' => [
                'Split 1/2 - 1 PK' => 250000,
                'Split 1,5 - 2 PK' => 350000,
                'Cassette' => 600000,
                'Standing floor' => 700000,
            ],
            'Isi freon' => [
                'Split 1/2 - 1 PK' => 150000,
                'Split 1,5 - 2 PK' => 200000,
                'Cassette' => 350000,
                'Standing floor' => 400000,
            ],
            'Konsultasi dan survei' => [
                'Split 1/2 - 1 PK' => null,
                'Split 1,5 - 2 PK' => null,
                'Cassette' => 50000,
                'Standing floor' => 50000,
            ],
            'Paket cuci + isi freon' => [
                'Split 1/2 - 1 PK' => 200000,
                'Split 1,5 - 2 PK' => 275000,
                'Cassette' => null,
                'Standing floor' => null,
            ],
        ];

        foreach ($matrix as $namaLayanan => $baris) {
            $idLayanan = $layanan[$namaLayanan] ?? null;

            if ($idLayanan === null) {
                continue;
            }

            foreach ($baris as $namaJenis => $tarif) {
                $idJenis = $jenisAc[$namaJenis] ?? null;

                if ($idJenis === null) {
                    continue;
                }

                TarifLayanan::updateOrCreate(
                    ['id_layanan' => $idLayanan, 'id_jenis_ac' => $idJenis],
                    ['tarif' => $tarif],
                );
            }
        }
    }
}
