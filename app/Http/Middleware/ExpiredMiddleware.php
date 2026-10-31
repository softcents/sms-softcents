<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpiredMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->will_expire ||  auth()->user()->will_expire < now()) {
            return $request->wantsJson()
                ? response()->json(__('Your plan has been expired, please subscribe a new plan.'), 406)
                : back()->with('error', __('Your plan has been expired, please subscribe a new plan.'));
        }

        return $next($request);
    }
}
