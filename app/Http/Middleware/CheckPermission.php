<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $permissions = session('permissions', []);

        if (!in_array($permission, $permissions)) {
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
