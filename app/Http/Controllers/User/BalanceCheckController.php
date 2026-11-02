<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IpWhitelistService;

class BalanceCheckController extends Controller
{
    public function index(Request $request)
    {
        $user = User::where(['client_id' => $request->clientId ?: request('clientId'), 'client_secret' => $request->clientSecret ?: request('clientSecret')])->first();
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => __('User not found. Please provide us your valid client id and client secret.')
            ], 404);
        }

        // Validate client IP against whitelist & account permissions
        $clientIp = IpWhitelistService::getClientIp($request);
        $ipCheck = IpWhitelistService::check($user, $clientIp);
        if (!$ipCheck['allowed']) {
            return response()->json([
                'status' => $ipCheck['code'],
                'error' => $ipCheck['status'],
                'message' => $ipCheck['message'],
                'client_ip' => $ipCheck['client_ip'],
            ], $ipCheck['code']);
        }

        return response()->json([
            'status' => 200,
            'balance' => $user->balance
        ]);
    }
}
