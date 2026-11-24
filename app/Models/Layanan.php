<?php

namespace App\Models;

use App\Enums\KategoriLayanan;
use Database\Factories\LayananFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Layanan extends Model
{
    /** @use HasFactory<LayananFactory> */
    use HasFactory;

    protected $table = 'layanan';

    protected $primaryKey = 'id_layanan';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'slug',
        'ringkasan',
        'deskripsi',
        'kategori',
        'jenis_ac',
        'durasi',
        'fitur',
        'satuan',
        'tarif_mulai',
        'keterangan_harga',
        'catatan_harga',
        'perlu_survei',
        'gambar',
        'urutan',
        'bagi_hasil',
        'aktif',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'kategori' => KategoriLayanan::class,
            'fitur' => 'array',
            'tarif_mulai' => 'integer',
            'bagi_hasil' => 'integer',
            'urutan' => 'integer',
            'perlu_survei' => 'boolean',
            'aktif' => 'boolean',
        ];
    }

    /**
     * Path foto relatif terhadap folder public (lewat storage:link), atau null bila belum ada foto.
     *
     * @return Attribute<string|null, never>
     */
    protected function urlGambar(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->gambar ? 'storage/'.$this->gambar : null);
    }

    /**
     * Mutator: spasi ganda dan spasi di tepi dirapikan sebelum nama disimpan,
     * di mana pun Layanan dibuat (form admin, seeder, atau tinker).
     *
     * @return Attribute<string, string>
     */
    protected function nama(): Attribute
    {
        return Attribute::set(fn (string $nilai): string => Str::squish($nilai));
    }

    /**
     * Daftar tarif per jenis AC untuk layanan ini.
     *
     * @return HasMany<TarifLayanan, $this>
     */
    public function tarif(): HasMany
    {
        return $this->hasMany(TarifLayanan::class, 'id_layanan', 'id_layanan');
    }
}
