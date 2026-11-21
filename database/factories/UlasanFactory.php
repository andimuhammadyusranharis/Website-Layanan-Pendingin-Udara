<?php

namespace Database\Factories;

use App\Models\Ulasan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ulasan>
 */
class UlasanFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_pelanggan' => fake()->name(),
            'rating' => fake()->numberBetween(1, 5),
            'komentar' => fake()->sentence(12),
            'tampil' => true,
        ];
    }

    public function tersembunyi(): static
    {
        return $this->state(fn (array $attributes) => [
            'tampil' => false,
        ]);
    }
}
