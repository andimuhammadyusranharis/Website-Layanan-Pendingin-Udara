<?php

namespace App\Http\Controllers;

use App\Services\EstimasiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EstimasiController extends Controller
{
    public function __construct(private readonly EstimasiService $estimasiService) {}

    public function index(Request $request): View
    {
        return view('pengguna.estimasi', $this->estimasiService->dataHalaman($request->query()));
    }
}
