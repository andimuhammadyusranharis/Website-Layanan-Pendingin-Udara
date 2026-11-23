<?php

namespace App\Models;

use App\Enums\KategoriProduk;
use Database\Factories\ProdukFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    /** @use HasFactory<ProdukFactory> */
    use HasFactory;

    protected $table = 'produk';

    protected $primaryKey = 'id_produk';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'slug',
        'kategori',
        'deskripsi',
        'gambar',
        'merek',
        'model',
        'spesifikasi',
        'harga',
        'stok',
        'satuan',
        'aktif',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'kategori' => KategoriProduk::class,
            'spesifikasi' => 'array',
            'harga' => 'integer',
            'stok' => 'integer',
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
}
