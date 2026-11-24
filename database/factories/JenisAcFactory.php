<?php

namespace Database\Factories;

use App\Models\JenisAc;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisAcFactory extends Factory
{
    protected $model = JenisAc::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(2, true),
            'keterangan' => fake()->sentence(),
        ];
    }
}
