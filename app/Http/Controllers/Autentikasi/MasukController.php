<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Autentikasi\MasukRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class MasukController extends Controller
{
    /**
     * Satu form masuk untuk ketiga peran; peran dikenali dari akunnya.
     */
    public function create(): View
    {
        return view('autentikasi.masuk');
    }

    public function store(MasukRequest $request): RedirectResponse
    {
        $request->autentikasi();

        $request->session()->regenerate();

        return redirect()->intended(self::tujuanSetelahMasuk($request->user()));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda');
    }

    /**
     * Halaman awal tiap peran. Selama halaman teknisi dan akun pelanggan belum dibuat, keduanya diarahkan ke beranda.
     */
    public static function tujuanSetelahMasuk(?User $user): string
    {
        $namaRoute = match ($user?->role) {
            User::PERAN_ADMIN => 'admin.dashboard',
            User::PERAN_TEKNISI => 'teknisi.beranda',
            default => 'beranda',
        };

        return route(Route::has($namaRoute) ? $namaRoute : 'beranda');
    }
}
