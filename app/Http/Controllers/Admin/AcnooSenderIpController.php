<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\SenderIp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AcnooSenderIpController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:senderips-read')->only(['index']);
        $this->middleware('permission:senderips-update')->only(['store', 'status', 'destroy', 'deleteAll']);
    }

    /**
     * Display a listing of all sender/whitelisted IPs.
     */
    public function index(Request $request)
    {
        $query = SenderIp::with('user:id,name,username,email,phone');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status') && in_array($request->status, ['0', '1'], true)) {
            $query->where('status', (int) $request->status);
        }

        $senderips = $query->latest()->paginate(20)->withQueryString();
        $users = User::where('status', 'active')->orderBy('name')->select('id', 'name', 'phone', 'email')->get();

        return view('admin.senderips.index', compact('senderips', 'users'));
    }

    /**
     * Store a newly created whitelisted IP.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'ip_address' => 'required|string|max:50',
            'note'       => 'nullable|string|max:255',
        ]);

        $ip = trim($request->ip_address);

        $isValidIp = filter_var($ip, FILTER_VALIDATE_IP);
        $isValidCidr = false;

        if (str_contains($ip, '/')) {
            $parts = explode('/', $ip, 2);
            if (count($parts) === 2 && filter_var($parts[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $mask = (int) $parts[1];
                if ($mask >= 0 && $mask <= 32) {
                    $isValidCidr = true;
                }
            }
        }

        if (!$isValidIp && !$isValidCidr && $ip !== '*') {
            return back()->withInput()->with('error', __('Please enter a valid IPv4, IPv6 address or CIDR subnet (e.g. 192.168.1.5 or 103.181.28.0/24).'));
        }

        $exists = SenderIp::where('user_id', $request->user_id)
            ->where('ip_address', $ip)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', __('This IP address is already configured for this user.'));
        }

        SenderIp::create([
            'user_id'    => $request->user_id,
            'ip_address' => $ip,
            'note'       => $request->note,
            'browser'    => 'Admin Console',
            'status'     => 1,
        ]);

        return back()->with('message', __('Whitelisted IP added successfully.'));
    }

    /**
     * Toggle status.
     */
    public function status(Request $request, $id)
    {
        $senderIp = SenderIp::findOrFail($id);
        $senderIp->status = !$senderIp->status;
        $senderIp->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status'  => $senderIp->status,
                'message' => __('Status updated successfully.')
            ]);
        }

        return back()->with('message', __('Status updated successfully.'));
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Request $request, $id)
    {
        $senderIp = SenderIp::findOrFail($id);
        $senderIp->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Whitelisted IP removed successfully.')
            ]);
        }

        return back()->with('message', __('Whitelisted IP removed successfully.'));
    }

    /**
     * Bulk delete.
     */
    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        if (!empty($ids) && is_array($ids)) {
            SenderIp::whereIn('id', $ids)->delete();
            return response()->json([
                'success' => true,
                'message' => __('Selected IPs removed successfully.')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('No items selected.')
        ], 400);
    }
}
