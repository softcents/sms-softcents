<?php

namespace App\SmsGateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PanelSms
{
    public function send($number, $message, $senderid, $apiurl, $apikey, $clientid)
    {
        $payload = [
            "SenderId"      => $senderid,
            "Is_Unicode"    => true,
            "Is_Flash"      => false,
            "DataCoding"    => "8",
            "SchedTime"     => "",
            "GroupId"       => "",
            "Message"       => $message,
            "MobileNumbers" => $number,
            "ApiKey"        => $apikey,
            "ClientId"      => $clientid,
        ];

        $response = Http::post($apiurl, $payload);

        return $response->json();
    }

    /**
     * Check account balance from PanelSMS / QSMS endpoint
     *
     * @param mixed $gateway
     * @return array
     */
    public static function check_balance($gateway): array
    {
        $params = is_array($gateway->params) ? $gateway->params : json_decode($gateway->params, true);
        $apiUrl = $params['apiurl'] ?? $params['api_url'] ?? 'http://202.72.233.114/api/v2/SendSMS';
        $apiKey = $params['key'] ?? $params['apikey'] ?? $params['ApiKey'] ?? '';
        $clientId = $params['clientid'] ?? $params['client_id'] ?? $params['ClientId'] ?? '';

        if (empty($apiKey) || empty($clientId)) {
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Config Error',
                'message'   => 'ApiKey or ClientId is missing.',
            ];
        }

        // Base URL up to /api/v2 or hostname
        $baseUrl = preg_replace('#/api/v2.*#i', '', $apiUrl) ?: 'http://202.72.233.114';
        $endpoint = rtrim($baseUrl, '/') . '/api/v2/Balance';

        try {
            $response = Http::timeout(10)
                ->withoutVerifying()
                ->get($endpoint, [
                    'ApiKey'   => $apiKey,
                    'ClientId' => $clientId,
                ]);

            $data = $response->json();

            if ($response->successful() && isset($data['ErrorCode']) && $data['ErrorCode'] == 0) {
                $creditsStr = (string) ($data['Data'][0]['Credits'] ?? '0');
                // Extract numeric value from e.g. "৳13643.505380"
                preg_match('/[\d.]+/', $creditsStr, $matches);
                $balance = isset($matches[0]) ? (float) $matches[0] : 0.0;

                return [
                    'success'   => true,
                    'balance'   => $balance,
                    'currency'  => 'BDT',
                    'formatted' => '৳' . number_format($balance, 2),
                    'raw_credits' => $creditsStr,
                    'message'   => 'Balance retrieved successfully.',
                    'raw'       => $data,
                ];
            }

            $errorMsg = $data['ErrorDescription'] ?? 'Failed to retrieve balance (HTTP ' . $response->status() . ')';
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Error',
                'message'   => $errorMsg,
                'raw'       => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('PanelSms::check_balance Exception', ['error' => $e->getMessage()]);
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Error',
                'message'   => $e->getMessage(),
            ];
        }
    }
}
