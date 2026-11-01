<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SPMiddleware
{
    /**
     * Handle an incoming request for Service Provider (Reseller).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->role === 'sp' || in_array(auth()->user()->role, ['admin', 'superadmin']))) {
            return $next($request);
        }

        if (auth()->check()) {
            if (auth()->user()->role === 'ssp') {
                return redirect()->route('ssp.dashboard.index');
            } elseif (auth()->user()->role === 'user') {
                return redirect()->route('users.dashboard.index');
            }
        }

        return redirect('/login');
    }
}
