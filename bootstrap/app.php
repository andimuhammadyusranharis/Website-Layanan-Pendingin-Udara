<?php

use App\Http\Controllers\Autentikasi\MasukController;
use App\Http\Middleware\PastikanPeran;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'peran' => PastikanPeran::class,
        ]);

        // Pengguna yang sudah masuk lalu membuka halaman masuk atau daftar diarahkan ke halaman sesuai perannya.
        $middleware->redirectUsersTo(fn (Request $request): string => MasukController::tujuanSetelahMasuk($request->user()));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
