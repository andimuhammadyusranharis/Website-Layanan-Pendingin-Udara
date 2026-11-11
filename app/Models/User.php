<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const PERAN_PELANGGAN = 'pelanggan';

    public const PERAN_TEKNISI = 'teknisi';

    public const PERAN_ADMIN = 'admin';

    /**
     * Label peran untuk tampilan.
     *
     * @var array<string, string>
     */
    public const LABEL_PERAN = [
        self::PERAN_PELANGGAN => 'Pelanggan',
        self::PERAN_TEKNISI => 'Teknisi',
        self::PERAN_ADMIN => 'Admin',
    ];

    /**
     * The attributes that are mass assignable. Kolom role sengaja tidak diisi massal
     * supaya peran tidak bisa diubah lewat input form.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'no_telepon',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasOne<Pelanggan, $this>
     */
    public function pelanggan(): HasOne
    {
        return $this->hasOne(Pelanggan::class, 'id_user');
    }

    /**
     * @return HasOne<Teknisi, $this>
     */
    public function teknisi(): HasOne
    {
        return $this->hasOne(Teknisi::class, 'id_user');
    }

    /**
     * Seragamkan nomor WhatsApp ke format 08xxxxxxxxxx. "+62 812-3456-7890" dan "0812 3456 7890"
     * menjadi "081234567890", supaya login dengan nomor tidak bergantung pada cara menulisnya.
     */
    public static function rapikanNomorTelepon(string $nomor): string
    {
        $angka = preg_replace('/\D+/', '', $nomor) ?? '';

        if (str_starts_with($angka, '62')) {
            return '0'.substr($angka, 2);
        }

        return $angka;
    }

    public function punyaPeran(string $peran): bool
    {
        return $this->role === $peran;
    }

    /**
     * Teknisi mitra baru tidak boleh masuk sebelum disetujui admin. Peran lain selalu boleh.
     */
    public function bolehMasuk(): bool
    {
        if (! $this->punyaPeran(self::PERAN_TEKNISI)) {
            return true;
        }

        return $this->teknisi?->status === Teknisi::STATUS_AKTIF;
    }
}
