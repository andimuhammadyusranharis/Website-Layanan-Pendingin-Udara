<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Autentikasi\DaftarRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DaftarController extends Controller
{
    /**
     * Registrasi publik hanya untuk pelanggan. Akun admin dibuat lewat seeder, teknisi lewat form terpisah.
     */
    public function create(): View
    {
        return view('autentikasi.daftar');
    }

    public function store(DaftarRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request): User {
            $user = User::create($request->safe()->only(['name', 'email', 'password']));
            $user->pelanggan()->create();

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('beranda')->with('status', 'Akun berhasil dibuat. Lengkapi nomor WhatsApp dan alamat di profil sebelum memesan.');
    }
}
