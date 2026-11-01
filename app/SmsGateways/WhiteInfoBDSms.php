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
        $payload = [
            'sender_id' => $senderid,
            'numbers'   => $number,
            'body'      => $message,
            'message'   => $message,
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

        $senderId = trim((string) ($sender_name ?: ($params['sender_id'] ?? '')));
        if ($senderId === '') {
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
            $numbersStr = implode(',', $numbers);
        } else {
            $numbersStr = (string) $numbers;
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
}
