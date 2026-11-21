<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasans';

    protected $primaryKey = 'id_ulasan';

    protected $fillable = [
        'nama_pelanggan',
        'rating',
        'komentar',
        'tampil',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'tampil' => 'boolean',
        ];
    }
}