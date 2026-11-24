<?php

namespace App\Http\Controllers\Akun;

use App\Http\Controllers\Controller;
use App\Http\Requests\Akun\GantiPasswordRequest;
use App\Http\Requests\Akun\ProfilRequest;
use App\Services\AkunService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function __construct(private readonly AkunService $akunService) {}

    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('akun.profil', [
            'user' => $user,
            'pelanggan' => $user->pelanggan,
            'bisaGantiPassword' => $user->password !== null,
        ]);
    }

    public function update(ProfilRequest $request): RedirectResponse
    {
        $this->akunService->perbaruiProfil($request->user(), $request->validated());

        return redirect()
            ->route('akun.profil')
            ->with('status', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(GantiPasswordRequest $request): RedirectResponse
    {
        $this->akunService->gantiPassword($request->user(), $request->validated('password'));

        return redirect()
            ->route('akun.profil')
            ->with('status', 'Kata sandi berhasil diganti.');
    }
}
