<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ImpersonationService
{
    /**
     * Check if actor can impersonate target.
     */
    public static function canImpersonate(User $actor, User $target): bool
    {
        // Prevent impersonating self
        if ($actor->id === $target->id) {
            return false;
        }

        // Super Admin or Admin
        if (in_array($actor->role, ['superadmin', 'admin']) || $actor->can('users-read')) {
            return true;
        }

        // Reseller (SP) can impersonate downline SSPs and customers
        if ($actor->role === 'sp') {
            if ($target->role === 'ssp' && $target->parent_id === $actor->id) {
                return true;
            }
            if ($target->role === 'user') {
                if ($target->parent_id === $actor->id) {
                    return true;
                }
                // Check if target is under an SSP belonging to this SP
                $sspIds = User::where('parent_id', $actor->id)->where('role', 'ssp')->pluck('id')->toArray();
                if (in_array($target->parent_id, $sspIds)) {
                    return true;
                }
            }
            return false;
        }

        // Sub-Reseller (SSP) can impersonate direct customers
        if ($actor->role === 'ssp') {
            return $target->role === 'user' && $target->parent_id === $actor->id;
        }

        return false;
    }

    /**
     * Perform loginAs impersonation with history stack.
     */
    public static function loginAs(User $target): RedirectResponse
    {
        $actor = auth()->user();

        if (!$actor || !self::canImpersonate($actor, $target)) {
            abort(403, __('You are not authorized to log into this account.'));
        }

        // Push current user onto impersonator stack
        $stack = session()->get('impersonator_stack', []);
        $stack[] = [
            'id' => $actor->id,
            'name' => $actor->name,
            'role' => $actor->role,
            'username' => $actor->username,
        ];
        session()->put('impersonator_stack', $stack);
        session()->put('impersonated_by', $actor->id);

        auth()->login($target);

        // Determine destination based on target role
        $roleTitle = match($target->role) {
            'sp' => __('Reseller (SP)'),
            'ssp' => __('Sub-Reseller (SSP)'),
            default => __('Customer'),
        };

        $redirect = match($target->role) {
            'sp' => redirect()->route('sp.dashboard.index'),
            'ssp' => redirect()->route('ssp.dashboard.index'),
            default => redirect()->route('users.dashboard.index'),
        };

        return $redirect->with('success', __('Successfully logged in as :role: :name', ['role' => $roleTitle, 'name' => $target->name]));
    }

    /**
     * Return back to the previous impersonator account.
     */
    public static function returnToPrevious(): RedirectResponse
    {
        $stack = session()->get('impersonator_stack', []);

        if (empty($stack)) {
            // Fallback for legacy single session key
            $impersonatedBy = session()->pull('impersonated_by');
            if (!$impersonatedBy) {
                return redirect('/');
            }
            $prevUser = User::find($impersonatedBy);
            if (!$prevUser) {
                return redirect('/');
            }
            auth()->login($prevUser);
            return self::redirectForUser($prevUser);
        }

        // Pop last impersonator from stack
        $last = array_pop($stack);
        session()->put('impersonator_stack', $stack);

        if (empty($stack)) {
            session()->forget(['impersonator_stack', 'impersonated_by']);
        } else {
            $currentTop = end($stack);
            session()->put('impersonated_by', $currentTop['id']);
        }

        $prevUser = User::find($last['id']);
        if (!$prevUser) {
            return redirect('/');
        }

        auth()->login($prevUser);

        return self::redirectForUser($prevUser);
    }

    /**
     * Redirect helper based on user role when returning.
     */
    private static function redirectForUser(User $user): RedirectResponse
    {
        return match($user->role) {
            'superadmin', 'admin' => redirect()->route('admin.users.index', ['users' => 'user'])->with('success', __('Switched back to Admin Panel')),
            'sp' => redirect()->route('sp.dashboard.index')->with('success', __('Switched back to Reseller (SP) Panel')),
            'ssp' => redirect()->route('ssp.dashboard.index')->with('success', __('Switched back to Sub-Reseller (SSP) Panel')),
            default => redirect()->route('users.dashboard.index')->with('success', __('Switched back')),
        };
    }

    /**
     * Get details for the impersonation banner.
     */
    public static function getBannerInfo(): ?array
    {
        if (!session()->has('impersonated_by') && !session()->has('impersonator_stack')) {
            return null;
        }

        $stack = session()->get('impersonator_stack', []);
        $last = !empty($stack) ? end($stack) : null;
        $impersonatorId = $last ? $last['id'] : session('impersonated_by');
        $impersonator = User::find($impersonatorId);

        if (!$impersonator) {
            return null;
        }

        $roleLabel = match($impersonator->role) {
            'superadmin' => 'Super Admin',
            'admin' => 'Admin',
            'sp' => 'Reseller (SP)',
            'ssp' => 'Sub-Reseller (SSP)',
            default => 'User',
        };

        $currentRoleLabel = match(auth()->user()?->role) {
            'sp' => 'Reseller (SP)',
            'ssp' => 'Sub-Reseller (SSP)',
            default => 'Customer',
        };

        return [
            'impersonator' => $impersonator,
            'impersonator_role_label' => $roleLabel,
            'current_role_label' => $currentRoleLabel,
            'current_user' => auth()->user(),
            'switch_back_url' => route('users.return-to-admin'),
        ];
    }
}
