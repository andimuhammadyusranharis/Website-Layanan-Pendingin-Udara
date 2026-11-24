<?php

namespace App\Models;

use Database\Factories\TarifLayananFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Tarif per unit untuk satu layanan pada satu jenis AC.
 */
class TarifLayanan extends Model
{
    /** @use HasFactory<TarifLayananFactory> */
    use HasFactory;

    protected $table = 'tarif_layanan';

    protected $primaryKey = 'id_tarif';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id_layanan',
        'id_jenis_ac',
        'tarif',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'id_layanan' => 'integer',
            'id_jenis_ac' => 'integer',
            'tarif' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Layanan, $this>
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }

    /**
     * @return BelongsTo<JenisAc, $this>
     */
    public function jenisAc(): BelongsTo
    {
        return $this->belongsTo(JenisAc::class, 'id_jenis_ac', 'id_jenis_ac');
    }
}
