<?php

namespace App\Services;

use App\Models\Teknisi;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Logika bisnis admin untuk daftar dan persetujuan akun teknisi.
 */
class TeknisiService
{
    public const PER_HALAMAN = 10;

    public function __construct(private readonly Teknisi $teknisi) {}

    /**
     * @return LengthAwarePaginator<int, Teknisi>
     */
    public function daftar(string $cari = '', string $status = ''): LengthAwarePaginator
    {
        return $this->teknisi->newQuery()
            ->with('user')
            ->when($cari !== '', function ($query) use ($cari) {
                $query->whereHas('user', function ($query) use ($cari) {
                    $query->where('name', 'like', '%'.$cari.'%')
                        ->orWhere('email', 'like', '%'.$cari.'%')
                        ->orWhere('no_telepon', 'like', '%'.$cari.'%');
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest('id_teknisi')
            ->paginate(self::PER_HALAMAN)
            ->withQueryString();
    }

    /**
     * Status yang boleh dipakai sebagai filter.
     *
     * @return array<string, string>
     */
    public static function pilihanStatus(): array
    {
        return [
            Teknisi::STATUS_MENUNGGU => 'Menunggu',
            Teknisi::STATUS_AKTIF => 'Aktif',
            Teknisi::STATUS_NONAKTIF => 'Nonaktif',
        ];
    }

    /**
     * Hanya akun berstatus menunggu yang boleh diproses. Mengembalikan false bila akun
     * sudah aktif atau nonaktif, dan tidak ada data yang diubah.
     */
    public function setujui(Teknisi $teknisi): bool
    {
        return $this->prosesMenunggu($teknisi, [
            'status' => Teknisi::STATUS_AKTIF,
            'disetujui_pada' => now(),
        ]);
    }

    public function tolak(Teknisi $teknisi): bool
    {
        return $this->prosesMenunggu($teknisi, [
            'status' => Teknisi::STATUS_NONAKTIF,
            'disetujui_pada' => null,
        ]);
    }

    /**
     * Syarat status ada di dalam query update, jadi pengecekan dan perubahan satu langkah.
     * Query update tidak melewati $fillable, sehingga kolom status tetap tidak bisa diisi dari form.
     *
     * @param  array<string, mixed>  $nilai
     */
    private function prosesMenunggu(Teknisi $teknisi, array $nilai): bool
    {
        return $this->teknisi->newQuery()
            ->whereKey($teknisi->getKey())
            ->where('status', Teknisi::STATUS_MENUNGGU)
            ->update($nilai) > 0;
    }
}
