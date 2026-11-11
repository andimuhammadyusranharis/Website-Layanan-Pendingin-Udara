<?php

namespace App\Models;

use Database\Factories\TeknisiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teknisi extends Model
{
    /** @use HasFactory<TeknisiFactory> */
    use HasFactory;

    public const JENIS_PEMILIK = 'pemilik';

    public const JENIS_MITRA = 'mitra';

    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $table = 'teknisi';

    protected $primaryKey = 'id_teknisi';

    /**
     * Status dan tanggal persetujuan hanya diubah oleh admin, jadi tidak diisi massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'domisili',
        'sertifikasi',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'disetujui_pada' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
