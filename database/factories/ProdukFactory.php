<?php

namespace Database\Factories;

use App\Enums\KategoriProduk;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Bawaannya produk komponen, jadi merek, model, dan spesifikasi dibiarkan kosong.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(3, true),
            'slug' => fn (array $atribut): string => Str::slug($atribut['nama']),
            'kategori' => fake()->randomElement([
                KategoriProduk::Kelistrikan,
                KategoriProduk::Freon,
                KategoriProduk::Pipa,
                KategoriProduk::Aksesoris,
            ]),
            'deskripsi' => fake()->sentence(12),
            'gambar' => null,
            'merek' => null,
            'model' => null,
            'spesifikasi' => null,
            'harga' => fake()->numberBetween(5, 50) * 5000,
            'stok' => fake()->numberBetween(5, 30),
            'satuan' => null,
            'aktif' => true,
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'aktif' => false,
        ]);
    }

    public function habis(): static
    {
        return $this->state(fn (array $attributes) => [
            'stok' => 0,
        ]);
    }

    public function unitAc(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => KategoriProduk::UnitAc,
            'merek' => fake()->randomElement(['Daikin', 'Panasonic', 'Sharp', 'Gree']),
            'model' => strtoupper(fake()->bothify('??-###??')),
            'spesifikasi' => ['Daya '.fake()->numberBetween(300, 1100).' watt', 'Freon R32'],
            'harga' => fake()->numberBetween(30, 60) * 100000,
        ]);
    }
}
