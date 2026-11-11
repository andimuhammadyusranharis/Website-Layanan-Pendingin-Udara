<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as AkunGoogle;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Alihkan ke halaman izin Google. Tidak tersedia selama kredensial Google belum diisi di .env.
     */
    public function alihkan(): SymfonyRedirectResponse
    {
        abort_unless(self::tersedia(), 404);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Terima hasil dari Google: masukkan akun yang sudah tertaut, tautkan akun lama yang emailnya sama,
     * atau buat akun pelanggan baru.
     */
    public function callback(Request $request): RedirectResponse
    {
        abort_unless(self::tersedia(), 404);

        try {
            $akunGoogle = Socialite::driver('google')->user();
        } catch (Throwable) {
            return $this->gagal('Masuk dengan Google dibatalkan atau gagal. Silakan coba lagi.');
        }

        $email = Str::lower((string) $akunGoogle->getEmail());

        if ($email === '' || ! ($akunGoogle->getRaw()['email_verified'] ?? false)) {
            return $this->gagal('Email akun Google Anda belum terverifikasi, jadi tidak bisa dipakai untuk masuk.');
        }

        $user = User::firstWhere('google_id', $akunGoogle->getId()) ?? User::firstWhere('email', $email);
        $akunBaru = $user === null;

        if ($akunBaru) {
            $user = $this->buatPelanggan($akunGoogle, $email);
        } elseif ($user->google_id === null) {
            $user->forceFill([
                'google_id' => $akunGoogle->getId(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        }

        if (! $user->bolehMasuk()) {
            return $this->gagal($user->teknisi?->status === Teknisi::STATUS_NONAKTIF
                ? 'Akun teknisi Anda sedang dinonaktifkan. Hubungi admin.'
                : 'Akun teknisi Anda masih menunggu persetujuan admin.');
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        $tujuan = redirect()->intended(MasukController::tujuanSetelahMasuk($user));

        return $akunBaru
            ? $tujuan->with('status', 'Akun berhasil dibuat. Lengkapi nomor WhatsApp dan alamat di profil sebelum memesan.')
            : $tujuan;
    }

    /**
     * Tombol dan route Google hanya aktif bila kredensialnya sudah diisi.
     */
    public static function tersedia(): bool
    {
        return filled(config('services.google.client_id')) && filled(config('services.google.client_secret'));
    }

    /**
     * Akun dari Google selalu berperan pelanggan dan tidak punya kata sandi.
     */
    private function buatPelanggan(AkunGoogle $akunGoogle, string $email): User
    {
        $user = DB::transaction(function () use ($akunGoogle, $email): User {
            $user = new User([
                'name' => Str::limit(Str::squish((string) ($akunGoogle->getName() ?: Str::before($email, '@'))), 100, ''),
                'email' => $email,
            ]);
            $user->forceFill([
                'google_id' => $akunGoogle->getId(),
                'email_verified_at' => now(),
            ])->save();
            $user->pelanggan()->create();

            return $user;
        });

        event(new Registered($user));

        return $user;
    }

    private function gagal(string $pesan): RedirectResponse
    {
        return redirect()->route('login')->withErrors(['google' => $pesan]);
    }
}
