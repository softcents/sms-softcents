<?php

namespace App\Services;

use App\Models\InboxMessage;
use App\Models\Senderid;
use App\Models\Smsgateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InboxService
{
    /**
     * Store incoming message and associate with corresponding user
     */
    public static function storeMessage(
        string $sender,
        string $receiver,
        string $message,
        ?string $messageId = null,
        string $gatewayType = 'anbernet',
        ?int $gatewayId = null,
        ?Carbon $receivedAt = null,
        ?array $rawData = null
    ): ?InboxMessage {
        $cleanSender = preg_replace('/[^0-9]/', '', $sender);
        $cleanReceiver = preg_replace('/[^0-9]/', '', $receiver);

        // Normalize sender: if 11 digits starting with 01, add 88
        if (strlen($cleanSender) === 11 && str_starts_with($cleanSender, '01')) {
            $cleanSender = '88' . $cleanSender;
        }

        // Avoid duplicate by message_id if provided
        if (!empty($messageId)) {
            $exists = InboxMessage::where('message_id', (string) $messageId)->first();
            if ($exists) {
                return $exists;
            }
        }

        // Find user by senderid match (e.g. 8809639189627 or 09639189627)
        $userId = null;
        $matchedSenderid = Senderid::where('sender', $cleanReceiver)
            ->orWhere('sender', $receiver)
            ->orWhere('sender', ltrim($cleanReceiver, '88'))
            ->whereNotNull('user_id')
            ->first();

        if ($matchedSenderid) {
            $userId = $matchedSenderid->user_id;
            if (!$gatewayId && $matchedSenderid->gateway_id) {
                $gatewayId = $matchedSenderid->gateway_id;
            }
        }

        return InboxMessage::create([
            'user_id'      => $userId,
            'gateway_id'   => $gatewayId,
            'gateway_type' => $gatewayType,
            'sender'       => $cleanSender ?: $sender,
            'receiver'     => $cleanReceiver ?: $receiver,
            'message'      => $message,
            'message_id'   => $messageId ? (string) $messageId : null,
            'sms_status'   => 'RECEIVED',
            'is_read'      => false,
            'raw_data'     => $rawData,
            'received_at'  => $receivedAt ?: now(),
        ]);
    }

    /**
     * Poll / Sync incoming messages from Anbernet Incoming API
     */
    public static function syncAnbernet(Smsgateway $gateway, ?string $msisdn = null, ?string $incomingPassword = null): array
    {
        $params = is_array($gateway->params) ? $gateway->params : (json_decode($gateway->params, true) ?: []);
        $account = $params['account'] ?? '';
        $portalPassword = $params['password'] ?? '';
        $defaultMsisdn = $msisdn ?: ($params['senderid'] ?? '');

        if (empty($account)) {
            return [
                'success' => false,
                'message' => 'Account is not configured on Anbernet Gateway.',
                'count'   => 0,
            ];
        }

        $endpoint = 'https://inmsg.anbernet.com/api/incoming/messages';
        $payload = [
            'account'           => $account,
            'portal_password'   => $portalPassword,
            'msisdn'            => $defaultMsisdn,
            'incoming_password' => $incomingPassword ?: ($params['incoming_password'] ?? 'secret'),
        ];

        try {
            $res = Http::timeout(20)
                ->acceptJson()
                ->asJson()
                ->withoutVerifying()
                ->get($endpoint, $payload);

            if (!$res->successful()) {
                // Try POST if GET returns error
                $res = Http::timeout(20)
                    ->acceptJson()
                    ->asJson()
                    ->withoutVerifying()
                    ->post($endpoint, $payload);
            }

            if (!$res->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to reach Anbernet incoming API: ' . ($res->json()['message'] ?? $res->body()),
                    'count'   => 0,
                ];
            }

            $data = $res->json();
            $messages = $data['messages'] ?? [];
            $newCount = 0;

            foreach ($messages as $msg) {
                // In Anbernet: "senderid" is the sender mobile number (e.g. 88017...),
                // "destaddr" or "msisdn" is the virtual number (e.g. 8809639...)
                $from = $msg['senderid'] ?? $msg['from'] ?? '';
                $to = $msg['destaddr'] ?? $msg['msisdn'] ?? $defaultMsisdn;
                $text = $msg['message'] ?? '';
                $msgId = (string) ($msg['messageid'] ?? $msg['id'] ?? '');
                $arrival = !empty($msg['arrivaldate']) ? Carbon::parse($msg['arrivaldate']) : now();

                if (!empty($from) && !empty($text)) {
                    $created = self::storeMessage(
                        sender: $from,
                        receiver: $to,
                        message: $text,
                        messageId: $msgId,
                        gatewayType: 'anbernet',
                        gatewayId: $gateway->id,
                        receivedAt: $arrival,
                        rawData: $msg
                    );

                    if ($created && $created->wasRecentlyCreated) {
                        $newCount++;
                    }
                }
            }

            return [
                'success' => true,
                'message' => "Successfully synced {$newCount} new incoming message(s).",
                'count'   => $newCount,
                'total'   => count($messages),
            ];
        } catch (\Throwable $e) {
            Log::error('InboxService::syncAnbernet error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Sync error: ' . $e->getMessage(),
                'count'   => 0,
            ];
        }
    }
}
