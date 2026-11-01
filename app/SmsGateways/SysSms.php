<?php

namespace App\SmsGateways;

use App\Models\Smsgateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SysSms
{
    public static function send_message(
        Smsgateway $gateway,
        string|array $numbers,
        string $contents,
        string $sender_name = null
    ): bool {
        $params = $gateway->params;

        $callerId = trim((string) $sender_name);

        if ($callerId === '') {
            return false;
        }

        $numbers = is_array($numbers) ? $numbers : explode(',', $numbers);

        $validatedNumbers = collect($numbers)
            ->map(function ($number) {
                // Remove spaces, dashes, brackets, etc.
                $number = preg_replace('/[^0-9+]/', '', trim($number));

                // Convert local format -> international format
                if (str_starts_with($number, '01')) {
                    $number = '88' . $number;
                }

                $number = ltrim($number, '+');

                return $number;
            })
            ->filter(function ($number) {
                return preg_match('/^8801[3-9]\d{8}$/', $number);
            })
            ->unique()
            ->values()
            ->all();

        // No valid numbers
        if (empty($validatedNumbers)) {
            return false;
        }

        $endpoint = rtrim($params['api_url'], '/') . '/sendtext';

        $payload = [
            'apikey'         => $params['apikey'],
            'secretkey'      => $params['secretkey'],
            'callerID'       => $callerId,
            'toUser'         => implode(',', $validatedNumbers),
            'messageContent' => $contents,
        ];

        try {
            $response = Http::timeout(30)
                ->acceptJson()
                ->withoutVerifying()
                ->post($endpoint, $payload);

            if (! $response->successful()) {
                Log::error('SysSms: HTTP error', [
                    'status'   => $response->status(),
                    'endpoint' => $endpoint,
                    'error'    => $response->body(),
                ]);

                return false;
            }

            $body = $response->json();

            // Status "0" means accepted
            if (isset($body['Status']) && (string) $body['Status'] === '0') {
                return true;
            }

            Log::error('SysSms: HTTP error', [
                'status'   => $body['Status'],
                'endpoint' => $endpoint,
                'error'    => $response->body(),
            ]);

        } catch (\Throwable $e) {
            Log::error('SysSms: Exception', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        return false;
    }

    /**
     * Check balance or connection status for SysSms / REVE SMS gateway
     *
     * @param mixed $gateway
     * @return array
     */
    public static function check_balance($gateway): array
    {
        $params = is_array($gateway->params) ? $gateway->params : json_decode($gateway->params, true);
        $apiUrl = $params['api_url'] ?? $params['apiurl'] ?? '';
        $apiKey = $params['apikey'] ?? $params['api_key'] ?? '';
        $secretKey = $params['secretkey'] ?? $params['secret_key'] ?? '';
        $clientId = $params['client_id'] ?? $params['clientid'] ?? $params['client'] ?? '';

        if (empty($apiKey) || empty($secretKey)) {
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Config Error',
                'message'   => 'ApiKey or SecretKey is missing.',
            ];
        }

        // 1. Try ReveSMS web balance check if client ID or portal available
        if (!empty($clientId)) {
            try {
                $host = parse_url($apiUrl, PHP_URL_HOST) ?: 'smpp.revesms.com';
                $balanceUrl = "https://{$host}/sms/smsConfiguration/smsClientBalance.jsp";
                $res = Http::timeout(8)->withoutVerifying()->get($balanceUrl, ['client' => $clientId]);
                if ($res->successful()) {
                    $content = trim($res->body());
                    if (is_numeric($content)) {
                        $val = (float) $content;
                        return [
                            'success'   => true,
                            'balance'   => $val,
                            'currency'  => 'BDT',
                            'formatted' => '৳' . number_format($val, 2),
                            'message'   => 'Balance retrieved successfully from REVE portal.',
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // proceed to endpoint check
            }
        }

        // 2. Query endpoint status via getstatus / sendtext handshake
        try {
            $parsedUrl = preg_replace('#/sendtext.*#i', '', $apiUrl);
            $endpoint = rtrim($parsedUrl, '/') . '/getstatus';

            $res = Http::timeout(10)->withoutVerifying()->get($endpoint, [
                'apikey'    => $apiKey,
                'secretkey' => $secretKey,
                'messageid' => '0',
            ]);

            $data = $res->json() ?? [];
            $status = (string) ($data['Status'] ?? '');

            // "114" with REJECTD means authenticated & alive, waiting for valid messageid
            if (in_array($status, ['0', '4', '114', 'REJECTD'])) {
                return [
                    'success'   => true,
                    'balance'   => null,
                    'currency'  => 'BDT',
                    'formatted' => 'Connected',
                    'message'   => 'Gateway API online & authenticated successfully.',
                    'raw'       => $data,
                ];
            }

            if ($status === '109') {
                return [
                    'success'   => false,
                    'balance'   => null,
                    'formatted' => 'Auth Error',
                    'message'   => 'Invalid API key or Secret key.',
                    'raw'       => $data,
                ];
            }

            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Offline',
                'message'   => $data['StatusDescription'] ?? $data['Text'] ?? ('Status code: ' . $status),
                'raw'       => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('SysSms::check_balance Exception', ['error' => $e->getMessage()]);
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Error',
                'message'   => $e->getMessage(),
            ];
        }
    }
}
