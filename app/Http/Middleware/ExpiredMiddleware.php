<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpiredMiddleware
{
    /**
     * Handle an incoming request.
     * Pure Tariff Model: As long as the user's account is active, they can send SMS.
     * Balances are validated and deducted per SMS charge in respective controllers.
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->status !== 'active') {
            return $request->wantsJson()
                ? response()->json(__('Your account is not active, please contact support.'), 403)
                : back()->with('error', __('Your account is not active, please contact support.'));
        }

        return $next($request);
    }
}
