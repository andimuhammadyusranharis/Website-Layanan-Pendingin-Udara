<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pelanggan>
 */
class PelangganFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'alamat' => null,
            'patokan' => null,
        ];
    }

    public function denganAlamat(): static
    {
        return $this->state(fn (array $attributes) => [
            'alamat' => fake()->streetAddress().', Pangkajene',
            'patokan' => 'Dekat '.fake()->randomElement(['masjid', 'sekolah', 'pasar', 'kantor desa']),
        ]);
    }
}
