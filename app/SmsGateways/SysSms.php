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
}
