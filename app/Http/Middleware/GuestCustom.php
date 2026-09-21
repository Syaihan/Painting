<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestCustom
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('operator_id')) {
            return redirect('/dashboard');
        }
        return $next($request);
    }
}
