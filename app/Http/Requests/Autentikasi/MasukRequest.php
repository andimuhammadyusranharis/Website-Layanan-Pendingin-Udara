<?php

namespace App\Http\Requests\Autentikasi;

use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MasukRequest extends FormRequest
{
    /**
     * Batas percobaan masuk yang gagal sebelum dikunci sementara.
     */
    private const BATAS_PERCOBAAN = 5;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'identitas' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'ingat' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'identitas.required' => 'Isi nomor WhatsApp atau email.',
            'password.required' => 'Isi kata sandi.',
        ];
    }

    /**
     * Cocokkan nomor WhatsApp atau email dengan kata sandi, lalu pastikan akun teknisi
     * sudah disetujui. Peran ditentukan dari akunnya, bukan dipilih di form.
     *
     * @throws ValidationException
     */
    public function autentikasi(): void
    {
        $this->pastikanTidakTerkunci();

        if (! Auth::attempt([...$this->kolomIdentitas(), 'password' => $this->string('password')->toString()], $this->boolean('ingat'))) {
            RateLimiter::hit($this->kunciPembatas());

            throw ValidationException::withMessages([
                'identitas' => 'Nomor WhatsApp, email, atau kata sandi salah.',
            ]);
        }

        RateLimiter::clear($this->kunciPembatas());

        /** @var User $user */
        $user = Auth::user();

        $pesanTolak = match (true) {
            ! $user->bolehMasuk() && $user->teknisi?->status === Teknisi::STATUS_NONAKTIF => 'Akun teknisi Anda sedang dinonaktifkan. Hubungi admin.',
            ! $user->bolehMasuk() => 'Akun teknisi Anda masih menunggu persetujuan admin.',
            default => null,
        };

        if ($pesanTolak !== null) {
            Auth::logout();

            throw ValidationException::withMessages(['identitas' => $pesanTolak]);
        }
    }

    /**
     * Isian berisi "@" dianggap email, selain itu dianggap nomor WhatsApp.
     *
     * @return array{email: string}|array{no_telepon: string}
     */
    private function kolomIdentitas(): array
    {
        $identitas = $this->string('identitas')->trim()->toString();

        if (Str::contains($identitas, '@')) {
            return ['email' => Str::lower($identitas)];
        }

        return ['no_telepon' => User::rapikanNomorTelepon($identitas)];
    }

    /**
     * @throws ValidationException
     */
    private function pastikanTidakTerkunci(): void
    {
        if (! RateLimiter::tooManyAttempts($this->kunciPembatas(), self::BATAS_PERCOBAAN)) {
            return;
        }

        event(new Lockout($this));

        $detik = RateLimiter::availableIn($this->kunciPembatas());

        throw ValidationException::withMessages([
            'identitas' => 'Terlalu banyak percobaan masuk. Coba lagi dalam '.max(1, (int) ceil($detik / 60)).' menit.',
        ]);
    }

    private function kunciPembatas(): string
    {
        return Str::transliterate(Str::lower($this->string('identitas')->trim()->toString()).'|'.$this->ip());
    }
}
