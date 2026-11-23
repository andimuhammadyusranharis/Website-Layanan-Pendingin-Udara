<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teknisi;
use App\Services\TeknisiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeknisiController extends Controller
{
    public function __construct(private readonly TeknisiService $teknisiService) {}

    public function index(Request $request): View
    {
        $filter = [
            'cari' => trim((string) $request->query('cari', '')),
            'status' => (string) $request->query('status', ''),
        ];

        return view('admin.teknisi.index', [
            'teknisi' => $this->teknisiService->daftar($filter['cari'], $filter['status']),
            'filter' => $filter,
            'pilihanStatus' => TeknisiService::pilihanStatus(),
        ]);
    }

    public function setujui(Teknisi $teknisi): RedirectResponse
    {
        $this->teknisiService->setujui($teknisi);

        return redirect()
            ->route('admin.teknisi.index')
            ->with('status', 'Akun teknisi '.$teknisi->user->name.' disetujui.');
    }

    public function tolak(Teknisi $teknisi): RedirectResponse
    {
        $this->teknisiService->tolak($teknisi);

        return redirect()
            ->route('admin.teknisi.index')
            ->with('status', 'Akun teknisi '.$teknisi->user->name.' ditolak.');
    }
}
