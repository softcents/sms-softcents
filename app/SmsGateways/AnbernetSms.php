<?php

namespace App\SmsGateways;

use App\Models\Smsgateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnbernetSms
{
    /**
     * Send method called by SendSmsJob or individual queue items
     *
     * @param string $number
     * @param string $message
     * @param string $senderid
     * @param string $apiurl
     * @param array $config
     * @return array
     */
    public function send($number, $message, $senderid, $apiurl, array $config = []): array
    {
        $normalizedNumber = self::normalizeNumber($number);
        if (!$normalizedNumber) {
            return [
                'status'  => 'error',
                'message' => "Invalid destination number: {$number}",
            ];
        }

        $apiUrl = !empty($apiurl) ? $apiurl : ($config['api_url'] ?? $config['apiurl'] ?? 'https://rapi.anbernet.com:9978');
        $endpoint = rtrim($apiUrl, '/') . '/api/v1/sendsms';

        $account   = trim((string) ($config['account'] ?? $config['username'] ?? ''));
        $password  = trim((string) ($config['password'] ?? ''));
        $token     = trim((string) ($config['token'] ?? ''));
        $apiKey    = trim((string) ($config['api_key'] ?? $config['apikey'] ?? $config['key'] ?? ''));
        $transtype = trim((string) ($config['transtype'] ?? 'T')) ?: 'T';
        $senderId  = trim((string) ($senderid ?: ($config['senderid'] ?? $config['sender_id'] ?? '')));

        $payload = [
            'account'    => $account,
            'senderid'   => $senderId,
            'receivers'  => [$normalizedNumber],
            'msgdata'    => $message,
            'flashon'    => 0,
            'transtype'  => $transtype,
            'campaignId' => null,
        ];

        if (!empty($password)) {
            $payload['password'] = $password;
        }
        if (!empty($token)) {
            $payload['token'] = $token;
        }
        if (!empty($apiKey)) {
            $payload['api_key'] = $apiKey;
        }

        try {
            $response = Http::timeout(30)
                ->acceptJson()
                ->asJson()
                ->withoutVerifying()
                ->post($endpoint, $payload);

            $status = $response->status();
            $body = $response->json();

            if (!$response->successful()) {
                Log::error('AnbernetSms::send HTTP error', [
                    'status'   => $status,
                    'endpoint' => $endpoint,
                    'number'   => $normalizedNumber,
                    'response' => $response->body(),
                ]);

                return [
                    'status'   => 'failed',
                    'code'     => $status,
                    'message'  => $body['message'] ?? $body['detail'] ?? $response->body(),
                    'raw'      => $body,
                ];
            }

            $messageId = null;
            if (!empty($body['messageids'][0]['messageid'])) {
                $messageId = (string) $body['messageids'][0]['messageid'];
            } elseif (!empty($body['messageid'])) {
                $messageId = (string) $body['messageid'];
            }

            $isSuccess = ($body['status'] ?? '') === 'success'
                || (isset($body['success_count']) && (int) $body['success_count'] > 0)
                || !empty($messageId);

            return [
                'status'     => $isSuccess ? 'success' : ($body['status'] ?? 'failed'),
                'message_id' => $messageId,
                'code'       => '9000',
                'message'    => $body['smstype'] ?? 'SMS Accepted',
                'data'       => [
                    'trans_id'            => $messageId,
                    'infozillion_response' => [
                        'status_code' => '9000',
                        'description' => $body['smstype'] ?? 'Queued',
                    ],
                ],
                'raw'        => $body,
            ];
        } catch (\Throwable $e) {
            Log::error('AnbernetSms::send Exception', [
                'error'  => $e->getMessage(),
                'number' => $normalizedNumber,
            ]);

            return [
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Method called by sendMessage helper for synchronous / bulk sending
     *
     * @param Smsgateway $gateway
     * @param string|array $numbers
     * @param string $contents
     * @param string|null $sender_name
     * @return bool
     */
    public static function send_message(
        Smsgateway $gateway,
        string|array $numbers,
        string $contents,
        string $sender_name = null
    ): bool {
        $params = is_array($gateway->params) ? $gateway->params : (json_decode($gateway->params, true) ?: []);

        $callerId = trim((string) ($sender_name ?: ($params['senderid'] ?? $params['sender_id'] ?? '')));
        if ($callerId === '') {
            Log::error('AnbernetSms::send_message - Missing sender ID');
            return false;
        }

        $numbers = is_array($numbers) ? $numbers : explode(',', (string) $numbers);

        $validatedNumbers = collect($numbers)
            ->map(fn($num) => self::normalizeNumber($num))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($validatedNumbers)) {
            Log::warning('AnbernetSms::send_message - No valid numbers provided');
            return false;
        }

        $apiUrl    = $params['api_url'] ?? $params['apiurl'] ?? 'https://rapi.anbernet.com:9978';
        $endpoint  = rtrim($apiUrl, '/') . '/api/v1/sendsms';

        $account   = trim((string) ($params['account'] ?? $params['username'] ?? ''));
        $password  = trim((string) ($params['password'] ?? ''));
        $token     = trim((string) ($params['token'] ?? ''));
        $apiKey    = trim((string) ($params['api_key'] ?? $params['apikey'] ?? $params['key'] ?? ''));
        $transtype = trim((string) ($params['transtype'] ?? 'T')) ?: 'T';

        if (empty($account)) {
            Log::error('AnbernetSms::send_message - Missing account parameter');
            return false;
        }

        $payload = [
            'account'    => $account,
            'senderid'   => $callerId,
            'receivers'  => $validatedNumbers,
            'msgdata'    => $contents,
            'flashon'    => 0,
            'transtype'  => $transtype,
            'campaignId' => null,
        ];

        if (!empty($password)) {
            $payload['password'] = $password;
        }
        if (!empty($token)) {
            $payload['token'] = $token;
        }
        if (!empty($apiKey)) {
            $payload['api_key'] = $apiKey;
        }

        try {
            $response = Http::timeout(35)
                ->acceptJson()
                ->asJson()
                ->withoutVerifying()
                ->post($endpoint, $payload);

            if (!$response->successful()) {
                Log::error('AnbernetSms::send_message HTTP error', [
                    'status'   => $response->status(),
                    'endpoint' => $endpoint,
                    'error'    => $response->body(),
                ]);
                return false;
            }

            $body = $response->json();

            $isSuccess = ($body['status'] ?? '') === 'success'
                || (isset($body['success_count']) && (int) $body['success_count'] > 0)
                || !empty($body['messageid']);

            if ($isSuccess) {
                return true;
            }

            Log::error('AnbernetSms::send_message API rejected', [
                'endpoint' => $endpoint,
                'response' => $body,
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('AnbernetSms::send_message Exception', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Check balance from Anbernet SMS gateway (/api/v1/balance)
     *
     * @param Smsgateway|mixed $gateway
     * @return array
     */
    public static function check_balance($gateway): array
    {
        $params = is_array($gateway->params) ? $gateway->params : (json_decode($gateway->params, true) ?: []);

        $apiUrl   = $params['api_url'] ?? $params['apiurl'] ?? 'https://rapi.anbernet.com:9978';
        $account  = trim((string) ($params['account'] ?? $params['username'] ?? ''));
        $password = trim((string) ($params['password'] ?? ''));
        $token    = trim((string) ($params['token'] ?? ''));
        $apiKey   = trim((string) ($params['api_key'] ?? $params['apikey'] ?? $params['key'] ?? ''));

        if (empty($account)) {
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Config Error',
                'message'   => 'Account username is required.',
            ];
        }

        if (empty($apiKey) && (empty($password) || empty($token))) {
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Config Error',
                'message'   => 'Provide either API Key OR Password + Token.',
            ];
        }

        $endpoint = rtrim($apiUrl, '/') . '/api/v1/balance';

        $queryOrBody = ['account' => $account];
        if (!empty($password)) {
            $queryOrBody['password'] = $password;
        }
        if (!empty($token)) {
            $queryOrBody['token'] = $token;
        }
        if (!empty($apiKey)) {
            $queryOrBody['api_key'] = $apiKey;
        }

        try {
            // First attempt: POST with JSON body (supported by both rapi and wapi)
            $res = Http::timeout(10)
                ->acceptJson()
                ->asJson()
                ->withoutVerifying()
                ->post($endpoint, $queryOrBody);

            // If POST returned 404 or method not allowed, fallback to GET query
            if ($res->status() === 405 || $res->status() === 404) {
                $res = Http::timeout(10)
                    ->acceptJson()
                    ->withoutVerifying()
                    ->get($endpoint, $queryOrBody);
            }

            if ($res->successful()) {
                $data = $res->json();

                // Response format: { "Balance Status": "demo:1250.50000000" }
                if (isset($data['Balance Status'])) {
                    $parts = explode(':', (string) $data['Balance Status']);
                    $balanceVal = (float) end($parts);
                    return [
                        'success'   => true,
                        'balance'   => $balanceVal,
                        'currency'  => 'BDT',
                        'formatted' => '৳' . number_format($balanceVal, 2),
                        'message'   => 'Balance retrieved successfully from Anbernet.',
                        'raw'       => $data,
                    ];
                }

                // If numeric or balance key directly returned
                if (isset($data['balance']) && is_numeric($data['balance'])) {
                    $balanceVal = (float) $data['balance'];
                    return [
                        'success'   => true,
                        'balance'   => $balanceVal,
                        'currency'  => 'BDT',
                        'formatted' => '৳' . number_format($balanceVal, 2),
                        'message'   => 'Balance retrieved successfully.',
                        'raw'       => $data,
                    ];
                }

                // Error messages returned with HTTP 200 (e.g. {"message": "Wrong credentials"})
                $msg = $data['message'] ?? $data['detail'] ?? null;
                if (!empty($msg)) {
                    $isAuthError = stripos($msg, 'wrong credentials') !== false || stripos($msg, 'unauthorized') !== false;
                    $isIpBan = stripos($msg, 'too many failed attempts') !== false || stripos($msg, 'banned') !== false;

                    return [
                        'success'   => false,
                        'balance'   => null,
                        'formatted' => $isIpBan ? 'IP Banned' : ($isAuthError ? 'Auth Error' : 'Error'),
                        'message'   => $msg,
                        'raw'       => $data,
                    ];
                }

                return [
                    'success'   => true,
                    'balance'   => null,
                    'currency'  => 'BDT',
                    'formatted' => 'Connected',
                    'message'   => 'Gateway authenticated successfully.',
                    'raw'       => $data,
                ];
            }

            $status = $res->status();
            $body = $res->json() ?? [];
            $errMsg = $body['message'] ?? $body['detail'] ?? $res->body();

            if ($status === 401) {
                return [
                    'success'   => false,
                    'balance'   => null,
                    'formatted' => 'Auth Error',
                    'message'   => 'Invalid credentials. ' . $errMsg,
                    'raw'       => $body,
                ];
            }

            if ($status === 403) {
                return [
                    'success'   => false,
                    'balance'   => null,
                    'formatted' => 'IP Banned',
                    'message'   => 'Server IP temporarily banned after repeated failed attempts: ' . $errMsg,
                    'raw'       => $body,
                ];
            }

            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => "HTTP {$status}",
                'message'   => "Balance check failed ({$status}): {$errMsg}",
                'raw'       => $body,
            ];
        } catch (\Throwable $e) {
            Log::error('AnbernetSms::check_balance Exception', ['error' => $e->getMessage()]);
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Error',
                'message'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Check DLR delivery report for a specific messageId (/api/v1/dlrcheck)
     *
     * @param Smsgateway|mixed $gateway
     * @param string $messageId
     * @return array
     */
    public static function check_dlr($gateway, string $messageId): array
    {
        $params = is_array($gateway->params) ? $gateway->params : (json_decode($gateway->params, true) ?: []);

        $apiUrl   = $params['api_url'] ?? $params['apiurl'] ?? 'https://rapi.anbernet.com:9978';
        $account  = trim((string) ($params['account'] ?? $params['username'] ?? ''));
        $password = trim((string) ($params['password'] ?? ''));
        $token    = trim((string) ($params['token'] ?? ''));
        $apiKey   = trim((string) ($params['api_key'] ?? $params['apikey'] ?? ''));

        $endpoint = rtrim($apiUrl, '/') . '/api/v1/dlrcheck';

        $payload = [
            'account'   => $account,
            'messageid' => $messageId,
        ];
        if (!empty($password)) $payload['password'] = $password;
        if (!empty($token)) $payload['token'] = $token;
        if (!empty($apiKey)) $payload['api_key'] = $apiKey;

        try {
            $res = Http::timeout(10)->acceptJson()->asJson()->withoutVerifying()->post($endpoint, $payload);
            return $res->json() ?? [];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Helper to format Bangladeshi phone number to international MSISDN 8801XXXXXXXXX
     *
     * @param string $number
     * @return string|null
     */
    private static function normalizeNumber(string $number): ?string
    {
        $number = preg_replace('/[^0-9+]/', '', trim($number));

        if (str_starts_with($number, '+8801')) {
            $number = substr($number, 1);
        } elseif (str_starts_with($number, '01')) {
            $number = '88' . $number;
        }

        $number = ltrim($number, '+');

        if (preg_match('/^8801[3-9]\d{8}$/', $number)) {
            return $number;
        }

        return null;
    }
}
