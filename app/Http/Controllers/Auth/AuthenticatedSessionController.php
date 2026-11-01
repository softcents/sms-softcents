<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $remember = $request->filled('remember') ? 1 : 0;
        $redirect_url = url('/');
        $user = auth()->user();

        if ($user->role == 'admin' || $user->role == 'superadmin') {
            $role = Role::where('name', $user->role)->first();
            if ($role && $role->permissions->count() > 0) {
                $first_role = $role->permissions->pluck('name')->all()[0];
                $page = explode('-', $first_role);
                $redirect_url = route('admin.'.$page[0].'.index');
            } else {
                $redirect_url = route('admin.dashboard.index');
            }
        } elseif ($user->role == 'sp') {
            $redirect_url = route('sp.dashboard.index');
        } elseif ($user->role == 'ssp') {
            $redirect_url = route('ssp.dashboard.index');
        } elseif ($user->role == 'user') {
            $redirect_url = route('users.dashboard.index');
        } else {
            $redirect_url = route('users.dashboard.index');
        }

        return response()->json([
            'message' => __('Logged In Successfully'),
            'remember' => $remember,
            'redirect' => $redirect_url,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
