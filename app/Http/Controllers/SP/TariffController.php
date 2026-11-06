<?php

namespace App\Http\Controllers\SP;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    public function index(Request $request)
    {
        $sp = auth()->user();
        $role = $request->get('role', 'all');
        $search = $request->get('search');

        $sspIds = User::where('parent_id', $sp->id)->where('role', 'ssp')->pluck('id')->toArray();
        $allowedParentIds = array_merge([$sp->id], $sspIds);

        $query = User::whereIn('parent_id', $allowedParentIds)
            ->whereIn('role', ['ssp', 'user']);

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

        $clients = $query->latest()->paginate(20)->withQueryString();

        $totalSsp = User::where('parent_id', $sp->id)->where('role', 'ssp')->count();
        $totalCustomers = User::whereIn('parent_id', $allowedParentIds)->where('role', 'user')->count();

        // SP's buying rates from Softcents admin
        $myNonMasking = (float) ($sp->non_masking_rate ?: 0.28);
        $myMasking = (float) ($sp->masking_rate ?: 0.55);

        return view('sp.tariffs.index', compact(
            'sp',
            'clients',
            'role',
            'search',
            'totalSsp',
            'totalCustomers',
            'myNonMasking',
            'myMasking'
        ));
    }

    public function update(Request $request, User $user)
    {
        $sp = auth()->user();

        $sspIds = User::where('parent_id', $sp->id)->where('role', 'ssp')->pluck('id')->toArray();
        $allowedParentIds = array_merge([$sp->id], $sspIds);

        if (!in_array($user->parent_id, $allowedParentIds) || !in_array($user->role, ['ssp', 'user'])) {
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

    public function bulkUpdate(Request $request)
    {
        $sp = auth()->user();

        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer',
            'non_masking_rate' => 'required|numeric|min:0',
            'masking_rate' => 'required|numeric|min:0',
        ]);

        $sspIds = User::where('parent_id', $sp->id)->where('role', 'ssp')->pluck('id')->toArray();
        $allowedParentIds = array_merge([$sp->id], $sspIds);

        $validUsers = User::whereIn('id', $request->user_ids)
            ->whereIn('parent_id', $allowedParentIds)
            ->whereIn('role', ['ssp', 'user'])
            ->get();

        if ($validUsers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => __('No eligible users found to update.'),
            ], 422);
        }

        $nmRate = (float) $request->non_masking_rate;
        $mRate = (float) $request->masking_rate;

        User::whereIn('id', $validUsers->pluck('id'))->update([
            'non_masking_rate' => $nmRate,
            'masking_rate' => $mRate,
        ]);

        $count = $validUsers->count();
        $message = __('Tariff updated for :count accounts — Non-Masking: ৳:nm | Masking: ৳:m', [
            'count' => $count,
            'nm' => number_format($nmRate, 4),
            'm' => number_format($mRate, 4),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count,
                'updated_ids' => $validUsers->pluck('id'),
                'non_masking_rate' => $nmRate,
                'masking_rate' => $mRate,
                'formatted_non_masking' => '৳' . number_format($nmRate, 2),
                'formatted_masking' => '৳' . number_format($mRate, 2),
            ]);
        }

        return back()->with('success', $message);
    }
}
