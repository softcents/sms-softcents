<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = array_keys(languages());

        if ($request->has('lang') && in_array($request->lang, $allowed)) {
            session(['lang' => $request->lang]);
        }

        $lang = session('lang') ?? 'en';
        if (!in_array($lang, $allowed)) {
            $lang = 'en';
            session(['lang' => 'en']);
        }

        app()->setLocale($lang);

        return $next($request);
    }
}
