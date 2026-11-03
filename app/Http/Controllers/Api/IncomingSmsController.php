<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncomingSmsController extends Controller
{
    /**
     * Handle incoming SMS webhook from gateways (Anbernet, WhiteInfoBD, Twilio, etc.)
     */
    public function handle(Request $request)
    {
        $all = $request->all();

        // Detect sender phone number
        $from = $request->input('sender')
            ?? $request->input('from')
            ?? $request->input('senderid')
            ?? $request->input('source')
            ?? $request->input('mobile')
            ?? $request->input('phone');

        // Detect receiver / virtual number
        $to = $request->input('receiver')
            ?? $request->input('to')
            ?? $request->input('destaddr')
            ?? $request->input('msisdn')
            ?? $request->input('destination')
            ?? '8809639189627';

        // Detect message text
        $message = $request->input('message')
            ?? $request->input('msg')
            ?? $request->input('text')
            ?? $request->input('body')
            ?? $request->input('msgdata');

        // Detect remote message ID
        $messageId = $request->input('messageid')
            ?? $request->input('id')
            ?? $request->input('msg_id')
            ?? $request->input('sms_id');

        $gatewayType = $request->input('gateway') ?? 'anbernet';

        if (empty($from) || empty($message)) {
            Log::warning('IncomingSmsController - Missing required fields', ['payload' => $all]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Missing from/sender or message/text parameter',
            ], 400);
        }

        $inboxMsg = InboxService::storeMessage(
            sender: (string) $from,
            receiver: (string) $to,
            message: (string) $message,
            messageId: $messageId ? (string) $messageId : null,
            gatewayType: $gatewayType,
            receivedAt: now(),
            rawData: $all
        );

        Log::info('Incoming SMS received and stored', [
            'id'     => $inboxMsg->id,
            'sender' => $from,
            'receiver' => $to,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Incoming SMS stored successfully',
            'data'    => [
                'id'       => $inboxMsg->id,
                'sender'   => $inboxMsg->sender,
                'receiver' => $inboxMsg->receiver,
                'user_id'  => $inboxMsg->user_id,
            ],
        ], 200);
    }
}
