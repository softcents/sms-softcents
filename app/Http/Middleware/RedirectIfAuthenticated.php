<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                $user = auth()->user();
                if ($user->role == 'admin' || $user->role == 'superadmin') {

                    $role = Role::where('name', $user->role)->first();
                    $first_role = $role->permissions->pluck('name')->all()[0]; // get auth user first page permission.
                    $page = explode('-', $first_role);
                    if ($page[0] == 'reports') {
                        $first_role = $role->permissions->pluck('name')->all()[1];
                        $page = explode('-', $first_role);
                        return redirect(route('admin.'.$page[0].'.index'));
                    }

                } elseif ($user->role == 'user') {
                    return redirect(route('users.dashboard.index'));
                }

            }
        }

        return $next($request);
    }
}
