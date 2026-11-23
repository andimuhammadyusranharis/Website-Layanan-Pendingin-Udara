<?php

namespace App\Http\Controllers;

use App\Services\HalamanStatisService;
use Illuminate\View\View;

class HalamanStatisController extends Controller
{
    /**
     * Constructor dijalankan otomatis setiap controller ini dibuat. Laravel membuat object
     * HalamanStatisService dan menyuntikkannya, jadi tidak perlu `new HalamanStatisService()`.
     */
    public function __construct(private readonly HalamanStatisService $halamanStatisService) {}

    public function kebijakanPrivasi(): View
    {
        return view('pengguna.halaman-statis', [
            'halaman' => $this->halamanStatisService->kebijakanPrivasi(),
        ]);
    }

    public function syarat(): View
    {
        return view('pengguna.halaman-statis', [
            'halaman' => $this->halamanStatisService->syarat(),
        ]);
    }
}
