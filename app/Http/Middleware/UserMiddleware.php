<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request for Customer / End User.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->role === 'user' || in_array(auth()->user()->role, ['admin', 'superadmin', 'sp', 'ssp']))) {
            return $next($request);
        }

        return redirect('/');
    }
}
