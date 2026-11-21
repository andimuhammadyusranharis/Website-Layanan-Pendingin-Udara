<?php

namespace App\Models;

use Database\Factories\UlasanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    /** @use HasFactory<UlasanFactory> */
    use HasFactory;

    protected $table = 'ulasans';

    protected $primaryKey = 'id_ulasan';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama_pelanggan',
        'rating',
        'komentar',
        'tampil',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'tampil' => 'boolean',
        ];
    }
}
