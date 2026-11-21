<?php

namespace App\Services;

use App\Models\Ulasan;
use Illuminate\Database\Eloquent\Collection;

class UlasanService
{
    public function __construct(
        private readonly Ulasan $ulasan
    ) {}

    public function getUlasanTampil(): Collection
    {
        return $this->ulasan->newQuery()
            ->where('tampil', true)
            ->latest()
            ->get();
    }

    public function getRingkasanRating(): array
    {
        $ulasan = $this->ulasan->newQuery()
            ->where('tampil', true);

        $total = (clone $ulasan)->count();

        return [
            'total' => $total,
            'rata_rata' => $total > 0
                ? round((clone $ulasan)->avg('rating'), 1)
                : 0,
            'distribusi' => collect(range(5, 1))
                ->mapWithKeys(fn ($rating) => [
                    $rating => (clone $ulasan)->where('rating', $rating)->count(),
                ])
                ->all(),
        ];
    }

    public function getSemuaUlasan(): Collection
    {
        return $this->ulasan->newQuery()
            ->latest()
            ->get();
    }

    public function ubahTampilan(int $id): bool
    {
        $ulasan = $this->ulasan->newQuery()->findOrFail($id);
        $ulasan->tampil = ! $ulasan->tampil;

        return $ulasan->save();
    }
}