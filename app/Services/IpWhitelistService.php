<?php

namespace App\Services;

use App\Models\User;
use App\Models\SenderIp;
use Illuminate\Http\Request;

class IpWhitelistService
{
    /**
     * Resolve the real client IP address considering proxies/Cloudflare
     */
    public static function getClientIp(Request $request): string
    {
        $headers = [
            'CF-Connecting-IP',
            'X-Forwarded-For',
            'X-Real-IP',
        ];

        foreach ($headers as $header) {
            $value = $request->header($header);
            if (!empty($value)) {
                // X-Forwarded-For may contain comma-separated IPs; pick the client (first) IP
                $parts = explode(',', $value);
                $ip = trim($parts[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $request->ip() ?: '127.0.0.1';
    }

    /**
     * Check if client IP is permitted for the given user
     *
     * @param User $user
     * @param string $clientIp
     * @return array [allowed: bool, code: int, message: string, client_ip: string]
     */
    public static function check(User $user, string $clientIp): array
    {
        // 1. Account status check
        if ($user->status !== 'active') {
            return [
                'allowed' => false,
                'code' => 403,
                'status' => 'ACCOUNT_SUSPENDED',
                'message' => __('Your account is currently inactive or suspended. Please contact support.'),
                'client_ip' => $clientIp,
            ];
        }

        // 2. Allow API permission check
        if (!$user->allow_api) {
            return [
                'allowed' => false,
                'code' => 403,
                'status' => 'API_DISABLED',
                'message' => __('API access is disabled for your account. Please enable API access from settings or contact support.'),
                'client_ip' => $clientIp,
            ];
        }

        // 3. Whitelist check: fetch user active whitelisted IPs
        $whitelistedIps = SenderIp::where('user_id', $user->id)
            ->where('status', 1)
            ->pluck('ip_address')
            ->map(fn($ip) => trim($ip))
            ->toArray();

        // If user has not set up any whitelist, allow access (unrestricted mode)
        if (empty($whitelistedIps)) {
            return [
                'allowed' => true,
                'code' => 200,
                'status' => 'NO_RESTRICTION',
                'message' => __('Access permitted (no IP whitelist restrictions configured).'),
                'client_ip' => $clientIp,
            ];
        }

        // Check if client IP matches any whitelisted IP or CIDR range
        foreach ($whitelistedIps as $allowedIp) {
            if (self::matches($clientIp, $allowedIp)) {
                return [
                    'allowed' => true,
                    'code' => 200,
                    'status' => 'WHITELISTED',
                    'message' => __('Access permitted from whitelisted IP.'),
                    'client_ip' => $clientIp,
                ];
            }
        }

        return [
            'allowed' => false,
            'code' => 403,
            'status' => 'IP_NOT_WHITELISTED',
            'message' => __("Access Denied: Your IP address (:ip) is not whitelisted. Please add this IP to your API whitelist in your dashboard or contact support.", ['ip' => $clientIp]),
            'client_ip' => $clientIp,
            'configured_count' => count($whitelistedIps),
        ];
    }

    /**
     * Compare incoming IP against an allowed IP or CIDR range
     */
    public static function matches(string $clientIp, string $allowedRule): bool
    {
        $allowedRule = trim($allowedRule);

        // Exact match or wildcard
        if ($allowedRule === '*' || $allowedRule === '0.0.0.0/0' || $allowedRule === $clientIp) {
            return true;
        }

        // CIDR subnet notation check e.g. 192.168.1.0/24
        if (str_contains($allowedRule, '/')) {
            return self::cidrMatch($clientIp, $allowedRule);
        }

        return false;
    }

    /**
     * Check if IPv4 address matches CIDR range
     */
    protected static function cidrMatch(string $ip, string $cidr): bool
    {
        [$subnet, $mask] = explode('/', $cidr, 2);
        $mask = (int) $mask;

        if ($mask < 0 || $mask > 32) {
            return false;
        }

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);

        if ($ipLong === false || $subnetLong === false) {
            return false;
        }

        $bitMask = ~((1 << (32 - $mask)) - 1);
        return ($ipLong & $bitMask) === ($subnetLong & $bitMask);
    }
}
