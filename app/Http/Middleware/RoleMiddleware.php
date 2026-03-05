<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== $role) {
            // Redirect ke dashboard sesuai role yang dimiliki
            if (auth()->user()->isGuru()) {
                return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            return redirect()->route('murid.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
