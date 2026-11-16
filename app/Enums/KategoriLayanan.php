<?php

namespace App\Enums;

/**
 * Kategori layanan. Dipakai sebagai pilihan di form admin dan sebagai chip filter di halaman publik.
 */
enum KategoriLayanan: string
{
    case CuciAc = 'cuci-ac';
    case PerbaikanFreon = 'perbaikan-freon';
    case PasangBongkar = 'pasang-bongkar';
    case Komersial = 'komersial';

    public function label(): string
    {
        return match ($this) {
            self::CuciAc => 'Cuci AC',
            self::PerbaikanFreon => 'Perbaikan dan freon',
            self::PasangBongkar => 'Pasang dan bongkar',
            self::Komersial => 'AC sentral dan gedung',
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
