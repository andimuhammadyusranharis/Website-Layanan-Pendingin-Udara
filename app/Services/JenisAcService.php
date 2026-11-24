<?php

namespace App\Services;

use App\Models\JenisAc;
use Illuminate\Database\Eloquent\Collection;

/**
 * Logika bisnis jenis AC. Controller cukup memanggil class ini, sehingga akses
 * database tidak tersebar di lapisan HTTP.
 */
class JenisAcService
{
    /**
     * Constructor dijalankan otomatis oleh service container Laravel saat class ini dibutuhkan.
     * Object JenisAc ikut disuntikkan dan dipakai sebagai pintu masuk query.
     */
    public function __construct(private readonly JenisAc $jenisAc) {}

    /**
     * @return Collection<int, JenisAc>
     */
    public function daftar(string $cari = ''): Collection
    {
        return $this->jenisAc->newQuery()
            ->when($cari !== '', fn ($query) => $query->where('nama', 'like', '%'.$cari.'%'))
            ->orderBy('nama')
            ->get();
    }

    public function jumlah(): int
    {
        return $this->jenisAc->newQuery()->count();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function simpan(array $data): JenisAc
    {
        return $this->jenisAc->newQuery()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function perbarui(JenisAc $jenisAc, array $data): JenisAc
    {
        $jenisAc->update($data);

        return $jenisAc;
    }

    /**
     * Hapus jenis AC sekaligus tarif yang memakainya, supaya tidak ada tarif yatim.
     */
    public function hapus(JenisAc $jenisAc): void
    {
        $jenisAc->tarif()->delete();

        $jenisAc->delete();
    }
}
