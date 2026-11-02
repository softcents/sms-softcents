<?php

namespace App\Http\Controllers\User;

use App\Models\SenderIp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IpWhitelistService;

class UserSenderIpController extends Controller
{
    /**
     * Display a listing of whitelisted IPs for the authenticated user.
     */
    public function index(Request $request)
    {
        $currentIp = IpWhitelistService::getClientIp($request);
        $query = SenderIp::where('user_id', auth()->id());

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        $senderips = $query->latest()->paginate(15);

        return view('users.senderips.index', compact('senderips', 'currentIp'));
    }

    /**
     * Store a new whitelisted IP address or CIDR range.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|string|max:50',
            'note'       => 'nullable|string|max:255',
        ]);

        $ip = trim($request->ip_address);

        // Validation for exact IP or CIDR notation
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

        $exists = SenderIp::where('user_id', auth()->id())
            ->where('ip_address', $ip)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', __('This IP address is already in your whitelist.'));
        }

        SenderIp::create([
            'user_id'    => auth()->id(),
            'ip_address' => $ip,
            'note'       => $request->note,
            'browser'    => $request->userAgent(),
            'status'     => 1,
        ]);

        return back()->with('message', __('Whitelisted IP added successfully.'));
    }

    /**
     * Toggle the status of a whitelisted IP.
     */
    public function status(Request $request, $id)
    {
        $senderIp = SenderIp::where('user_id', auth()->id())->findOrFail($id);
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
     * Remove the specified whitelisted IP.
     */
    public function destroy(Request $request, $id)
    {
        $senderIp = SenderIp::where('user_id', auth()->id())->findOrFail($id);
        $senderIp->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Whitelisted IP removed successfully.')
            ]);
        }

        return back()->with('message', __('Whitelisted IP removed successfully.'));
    }
}
