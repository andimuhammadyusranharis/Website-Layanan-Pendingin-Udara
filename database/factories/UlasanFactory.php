<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UlasanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_pelanggan' => fake()->name(),
            'rating' => fake()->numberBetween(1, 5),
            'komentar' => fake()->paragraph(),
            'tampil' => true,
        ];
    }
}