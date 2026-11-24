<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Logika bisnis untuk admin melihat data pelanggan (hanya baca).
 */
class PelangganService
{
    public const PER_HALAMAN = 10;

    public function __construct(private readonly User $user) {}

    /**
     * Pelanggan dengan pencarian nama, email, atau nomor telepon.
     *
     * @return LengthAwarePaginator<int, User>
     */
    public function daftar(string $cari = ''): LengthAwarePaginator
    {
        return $this->user->newQuery()
            ->where('role', User::PERAN_PELANGGAN)
            ->with('pelanggan')
            ->when($cari !== '', function ($query) use ($cari) {
                $query->where(function ($query) use ($cari) {
                    $query->where('name', 'like', '%'.$cari.'%')
                        ->orWhere('email', 'like', '%'.$cari.'%')
                        ->orWhere('no_telepon', 'like', '%'.$cari.'%');
                });
            })
            ->latest('id')
            ->paginate(self::PER_HALAMAN)
            ->withQueryString();
    }

    /**
     * Akun dengan peran lain (admin, teknisi) dianggap tidak ada, jadi hasilnya 404.
     */
    public function cari(int $id): User
    {
        return $this->user->newQuery()
            ->where('role', User::PERAN_PELANGGAN)
            ->with('pelanggan')
            ->findOrFail($id);
    }

    public function jumlah(): int
    {
        return $this->user->newQuery()
            ->where('role', User::PERAN_PELANGGAN)
            ->count();
    }
}
