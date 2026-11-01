<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request for Admin / Super Admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')) {
            return $next($request);
        }

        if (auth()->check()) {
            if (auth()->user()->role === 'sp') {
                return redirect()->route('sp.dashboard.index');
            } elseif (auth()->user()->role === 'ssp') {
                return redirect()->route('ssp.dashboard.index');
            } elseif (auth()->user()->role === 'user') {
                return redirect()->route('users.dashboard.index');
            }
        }

        return redirect('/');
    }
}
