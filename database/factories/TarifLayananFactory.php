<?php

namespace Database\Factories;

use App\Models\JenisAc;
use App\Models\Layanan;
use App\Models\TarifLayanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarifLayananFactory extends Factory
{
    protected $model = TarifLayanan::class;

    public function definition(): array
    {
        return [
            'id_layanan' => Layanan::factory(),
            'id_jenis_ac' => JenisAc::factory(),
            'tarif' => fake()->numberBetween(50000, 1000000),
        ];
    }
}
