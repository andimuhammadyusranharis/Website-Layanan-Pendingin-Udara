<?php

namespace Database\Factories;

use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teknisi>
 */
class TeknisiFactory extends Factory
{
    /**
     * Bawaannya teknisi mitra yang baru mendaftar dan belum disetujui.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory()->teknisi(),
            'jenis' => Teknisi::JENIS_MITRA,
            'status' => Teknisi::STATUS_MENUNGGU,
            'domisili' => fake()->randomElement(['Pangkajene', 'Minasatene', 'Bungoro', 'Labakkang', 'Segeri']),
            'sertifikasi' => null,
            'disetujui_pada' => null,
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Teknisi::STATUS_AKTIF,
            'disetujui_pada' => now(),
        ]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => ['status' => Teknisi::STATUS_NONAKTIF]);
    }

    public function pemilik(): static
    {
        return $this->aktif()->state(fn (array $attributes) => ['jenis' => Teknisi::JENIS_PEMILIK]);
    }
}
