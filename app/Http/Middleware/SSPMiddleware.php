<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SSPMiddleware
{
    /**
     * Handle an incoming request for Sub Service Provider (Sub Reseller).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->role === 'ssp' || in_array(auth()->user()->role, ['admin', 'superadmin', 'sp']))) {
            return $next($request);
        }

        if (auth()->check()) {
            if (auth()->user()->role === 'user') {
                return redirect()->route('users.dashboard.index');
            }
        }

        return redirect('/login');
    }
}
