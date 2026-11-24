<?php

namespace Database\Seeders;

use App\Models\JenisAc;
use Illuminate\Database\Seeder;

class JenisAcSeeder extends Seeder
{
    public function run(): void
    {
        JenisAc::updateOrCreate(
            ['nama' => 'Split 1/2 - 1 PK'],
            ['keterangan' => 'AC dinding rumah tangga'],
        );

        JenisAc::updateOrCreate(
            ['nama' => 'Split 1,5 - 2 PK'],
            ['keterangan' => 'AC dinding ruangan besar'],
        );

        JenisAc::updateOrCreate(
            ['nama' => 'Cassette'],
            ['keterangan' => 'AC plafon kantor dan toko'],
        );

        JenisAc::updateOrCreate(
            ['nama' => 'Standing floor'],
            ['keterangan' => 'AC berdiri untuk aula'],
        );
    }
}
