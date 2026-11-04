<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    public function index(Request $request)
    {
        $ssp = auth()->user();
        $search = $request->get('search');

        $query = User::where('parent_id', $ssp->id)
            ->where('role', 'user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20)->withQueryString();
        $totalCustomers = User::where('parent_id', $ssp->id)->where('role', 'user')->count();

        // SSP buying rates from Reseller (SP)
        $myNonMasking = (float) ($ssp->non_masking_rate ?: 0.32);
        $myMasking = (float) ($ssp->masking_rate ?: 0.62);

        return view('ssp.tariffs.index', compact(
            'ssp',
            'customers',
            'search',
            'totalCustomers',
            'myNonMasking',
            'myMasking'
        ));
    }

    public function update(Request $request, User $user)
    {
        $ssp = auth()->user();

        if ($user->parent_id !== $ssp->id || $user->role !== 'user') {
            abort(403);
        }

        $request->validate([
            'non_masking_rate' => 'required|numeric|min:0',
            'masking_rate' => 'required|numeric|min:0',
        ]);

        $user->update([
            'non_masking_rate' => (float) $request->non_masking_rate,
            'masking_rate' => (float) $request->masking_rate,
        ]);

        $message = __('Customer tariff updated for :name — Non-Masking: ৳:nm | Masking: ৳:m', [
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
