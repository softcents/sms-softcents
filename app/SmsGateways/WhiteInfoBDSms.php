<?php

namespace App\SmsGateways;

use App\Models\Smsgateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhiteInfoBDSms
{
    /**
     * Send method called by SendSmsJob or direct calls
     */
    public function send($number, $message, $senderid, $apiurl, $apikey)
    {
        // Normalize receiver number to 8801XXXXXXXXX
        $cleanNumber = preg_replace('/[^0-9]/', '', (string) $number);
        if (str_starts_with($cleanNumber, '01') && strlen($cleanNumber) === 11) {
            $number = '88' . $cleanNumber;
        } elseif (preg_match('/^1[3-9]\d{8}$/', $cleanNumber)) {
            $number = '880' . $cleanNumber;
        } else {
            $number = $cleanNumber;
        }

        // Clean sender ID
        $cleanSender = preg_replace('/[^0-9]/', '', (string) $senderid);
        $senderid = !empty($cleanSender) ? $cleanSender : (string) $senderid;

        $payload = [
            'sender_id' => $senderid,
            'numbers'   => $number,
            'mobile'    => $number,
            'body'      => $message,
            'message'   => $message,
            'masking'   => $senderid,
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apikey,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ])
                ->post($apiurl, $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('WhiteInfoBDSms::send HTTP error', [
                'status'   => $response->status(),
                'response' => $response->body(),
                'number'   => $number,
            ]);

            return $response->json() ?? ['status' => 'error', 'message' => $response->body()];
        } catch (\Throwable $e) {
            Log::error('WhiteInfoBDSms::send Exception', [
                'error'  => $e->getMessage(),
                'number' => $number,
            ]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Method called by sendMessage helper
     */
    public static function send_message(
        Smsgateway $gateway,
        string|array $numbers,
        string $contents,
        string $sender_name = null
    ): bool {
        $params = is_array($gateway->params) ? $gateway->params : json_decode($gateway->params, true);

        $senderId = trim((string) $sender_name);
        $cleanSender = preg_replace('/[^0-9]/', '', $senderId);
        if (!empty($cleanSender)) {
            $senderId = $cleanSender;
        }

        if (empty($senderId)) {
            Log::error('WhiteInfoBDSms::send_message - Missing sender ID');
            return false;
        }

        $apiurl = $params['api_url'] ?? 'https://sms.whiteinfobd.com/api/v1/messages';
        $apikey = $params['api_key'] ?? '';

        if (empty($apikey)) {
            Log::error('WhiteInfoBDSms::send_message - Missing API Key');
            return false;
        }

        if (is_array($numbers)) {
            $normalizedNumbers = array_map(function ($num) {
                $clean = preg_replace('/[^0-9]/', '', (string) $num);
                return (str_starts_with($clean, '01') && strlen($clean) === 11) ? '88' . $clean : $clean;
            }, $numbers);
            $numbersStr = implode(',', $normalizedNumbers);
        } else {
            $clean = preg_replace('/[^0-9]/', '', (string) $numbers);
            $numbersStr = (str_starts_with($clean, '01') && strlen($clean) === 11) ? '88' . $clean : $clean;
        }

        $payload = [
            'sender_id' => $senderId,
            'numbers'   => $numbersStr,
            'body'      => $contents,
            'message'   => $contents,
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apikey,
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ])
                ->post($apiurl, $payload);

            if (!$response->successful()) {
                Log::error('WhiteInfoBDSms::send_message HTTP error', [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
                return false;
            }

            $body = $response->json();
            if ((isset($body['code']) && $body['code'] == 1000) ||
                (isset($body['status']) && in_array(strtolower($body['status']), ['processing', 'success', 'sent']))) {
                return true;
            }

            Log::error('WhiteInfoBDSms::send_message API response rejected', [
                'response' => $body,
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('WhiteInfoBDSms::send_message Exception', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Check real-time wallet/account balance from WhiteInfoBD API
     *
     * @param mixed $gateway
     * @return array
     */
    public static function check_balance($gateway): array
    {
        $params = is_array($gateway->params) ? $gateway->params : json_decode($gateway->params, true);
        $apiKey = $params['api_key'] ?? $params['apikey'] ?? '';
        $apiUrl = $params['api_url'] ?? 'https://sms.whiteinfobd.com/api/v1/messages';

        if (empty($apiKey)) {
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Config Error',
                'message'   => 'WhiteInfoBD API Key is missing.',
            ];
        }

        // Base URL up to /api/v1 or hostname
        $baseUrl = preg_replace('#/api/v1.*#i', '', $apiUrl) ?: 'https://sms.whiteinfobd.com';
        $endpoint = rtrim($baseUrl, '/') . '/api/v1/balance';

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept'        => 'application/json',
                ])
                ->withoutVerifying()
                ->get($endpoint, [
                    'api_key' => $apiKey,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $balance = (float) ($data['balance'] ?? 0);
                $currency = $data['currency'] ?? 'BDT';

                return [
                    'success'   => true,
                    'balance'   => $balance,
                    'currency'  => $currency,
                    'formatted' => '৳' . number_format($balance, 2),
                    'message'   => $data['message'] ?? 'Balance retrieved successfully.',
                    'raw'       => $data,
                ];
            }

            $body = $response->json() ?? [];
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Error',
                'message'   => $body['message'] ?? 'HTTP ' . $response->status() . ' error from provider.',
                'raw'       => $body,
            ];
        } catch (\Throwable $e) {
            Log::error('WhiteInfoBDSms::check_balance Exception', ['error' => $e->getMessage()]);
            return [
                'success'   => false,
                'balance'   => null,
                'formatted' => 'Error',
                'message'   => $e->getMessage(),
            ];
        }
    }
}
