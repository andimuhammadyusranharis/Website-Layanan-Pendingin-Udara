<?php

namespace Database\Factories;

use App\Enums\KategoriLayanan;
use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Layanan>
 */
class LayananFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(3, true),
            'slug' => fn (array $atribut): string => Str::slug($atribut['nama']),
            'ringkasan' => fake()->sentence(6),
            'deskripsi' => fake()->paragraph(),
            'kategori' => fake()->randomElement(KategoriLayanan::cases()),
            'jenis_ac' => 'Split dinding',
            'durasi' => '45 sampai 60 menit',
            'fitur' => [fake()->sentence(4), fake()->sentence(4)],
            'satuan' => 'unit',
            'tarif_mulai' => fake()->numberBetween(5, 40) * 10000,
            'keterangan_harga' => 'Mulai dari',
            'catatan_harga' => null,
            'perlu_survei' => false,
            'gambar' => null,
            'urutan' => 0,
            'bagi_hasil' => fake()->randomElement([40, 45, 50]),
            'aktif' => true,
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'aktif' => false,
        ]);
    }

    public function perluSurvei(): static
    {
        return $this->state(fn (array $attributes) => [
            'perlu_survei' => true,
            'tarif_mulai' => null,
            'keterangan_harga' => 'Biaya',
        ]);
    }
}
