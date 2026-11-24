<?php

namespace App\Services;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Logika bisnis akun pelanggan: memperbarui profil dan mengganti kata sandi.
 */
class AkunService
{
    /**
     * Nama dan nomor telepon disimpan di tabel users, alamat dan patokan di tabel pelanggan.
     * Keduanya dalam satu transaksi supaya tidak ada data yang tersimpan setengah.
     *
     * @param  array{name: string, no_telepon: string, alamat?: ?string, patokan?: ?string}  $data
     */
    public function perbaruiProfil(User $user, array $data): User
    {
        DB::transaction(function () use ($user, $data): void {
            $user->update([
                'name' => $data['name'],
                'no_telepon' => User::rapikanNomorTelepon($data['no_telepon']),
            ]);

            Pelanggan::query()->updateOrCreate(
                ['id_user' => $user->id],
                [
                    'alamat' => $data['alamat'] ?? null,
                    'patokan' => $data['patokan'] ?? null,
                ],
            );
        });

        return $user;
    }

    /**
     * Cast `hashed` di model User sudah mengenkripsi kata sandi,
     * jadi tidak perlu Hash::make lagi di sini.
     */
    public function gantiPassword(User $user, string $passwordBaru): void
    {
        $user->update(['password' => $passwordBaru]);
    }
}
