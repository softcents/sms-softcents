<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Smsgateway;
use App\Models\User;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'all');
        $search = $request->get('search');

        $query = User::with('parent:id,name,username,role')
            ->whereIn('role', ['user', 'sp', 'ssp']);

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        // Platform wholesale costs
        $gwNonMasking = (float) Smsgateway::where('status', 1)->avg('non_masking_rate') ?: 0.24;
        $gwMasking = (float) Smsgateway::where('status', 1)->avg('masking_rate') ?: 0.45;

        // Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalSp = User::where('role', 'sp')->count();
        $totalSsp = User::where('role', 'ssp')->count();

        return view('admin.tariffs.index', compact(
            'users',
            'role',
            'search',
            'gwNonMasking',
            'gwMasking',
            'totalUsers',
            'totalSp',
            'totalSsp'
        ));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'non_masking_rate' => 'required|numeric|min:0',
            'masking_rate' => 'required|numeric|min:0',
        ]);

        $user->update([
            'non_masking_rate' => (float) $request->non_masking_rate,
            'masking_rate' => (float) $request->masking_rate,
        ]);

        $message = __('Tariff updated for :name — Non-Masking: ৳:nm | Masking: ৳:m', [
            'name' => $user->name,
            'nm' => number_format($user->non_masking_rate, 4),
            'm' => number_format($user->masking_rate, 4),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'user_id' => $user->id,
                'non_masking_rate' => (float) $user->non_masking_rate,
                'masking_rate' => (float) $user->masking_rate,
                'formatted_non_masking' => '৳' . number_format($user->non_masking_rate, 2),
                'formatted_masking' => '৳' . number_format($user->masking_rate, 2),
            ]);
        }

        return back()->with('success', $message);
    }
}
