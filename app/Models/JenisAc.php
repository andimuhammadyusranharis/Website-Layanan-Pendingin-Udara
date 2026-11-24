<?php

namespace App\Models;

use Database\Factories\JenisAcFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Jenis AC, misalnya Split 1/2 - 1 PK atau Cassette.
 */
class JenisAc extends Model
{
    /** @use HasFactory<JenisAcFactory> */
    use HasFactory;

    protected $table = 'jenis_ac';

    protected $primaryKey = 'id_jenis_ac';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'keterangan',
    ];

    /**
     * Semua tarif yang memakai jenis AC ini.
     *
     * @return HasMany<TarifLayanan, $this>
     */
    public function tarif(): HasMany
    {
        return $this->hasMany(TarifLayanan::class, 'id_jenis_ac', 'id_jenis_ac');
    }
}
