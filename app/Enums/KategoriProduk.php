<?php

namespace App\Enums;

/**
 * Kategori produk toko. Dipakai sebagai pilihan di form admin dan sebagai tombol saring di halaman Toko.
 */
enum KategoriProduk: string
{
    case UnitAc = 'unit-ac';
    case Kelistrikan = 'kelistrikan';
    case Freon = 'freon';
    case Pipa = 'pipa';
    case Aksesoris = 'aksesoris';

    public function label(): string
    {
        return match ($this) {
            self::UnitAc => 'Unit AC baru',
            self::Kelistrikan => 'Kapasitor dan kelistrikan',
            self::Freon => 'Freon',
            self::Pipa => 'Pipa tembaga',
            self::Aksesoris => 'Remote dan aksesoris',
        };
    }

    /**
     * Pilihan untuk dropdown: nilai tersimpan sebagai kunci, label sebagai teks.
     *
     * @return array<string, string>
     */
    public static function opsi(): array
    {
        $opsi = [];

        foreach (self::cases() as $kategori) {
            $opsi[$kategori->value] = $kategori->label();
        }

        return $opsi;
    }
}
