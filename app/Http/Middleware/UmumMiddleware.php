<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UmumMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->is_jarkom == 1 || $user->is_umum == 1)) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}
