<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Batasi route untuk peran tertentu, misalnya `peran:admin` atau `peran:admin,teknisi`.
 * Dipasang setelah middleware `auth`.
 */
class PastikanPeran
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$peran): Response
    {
        $user = $request->user();

        abort_if($user === null || ! in_array($user->role, $peran, true), Response::HTTP_FORBIDDEN, 'Halaman ini tidak tersedia untuk akun Anda.');

        return $next($request);
    }
}
