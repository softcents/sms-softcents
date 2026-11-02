<?php

namespace App\Http\Controllers\User;

use App\Models\Sms;
use App\Models\User;
use App\Models\Senderid;
use App\Models\Smsgateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SendSmsJob;

class SoftcentsSmsApiController extends Controller
{
    public function index(Request $request)
    {
        // Support standard parameters and enterprise specification parameters
        $clientId = $request->clientId ?? $request->account_id;
        $clientSecret = $request->clientSecret ?? $request->api_key;
        $numbers = $request->numbers ?? $request->mobile;
        $contents = $request->contents ?? $request->message;
        $senderId = $request->senderId ?? $request->masking;

        if (empty($clientId) || empty($clientSecret)) {
            return response()->json([
                'status' => 401,
                'message' => __('Client ID (or account_id) and Client Secret (or api_key) are required.'),
            ], 401);
        }

        $user = User::where(['client_id' => $clientId, 'client_secret' => $clientSecret])->first();

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => __('User not found or invalid credentials.'),
            ], 404);
        }

        // Validate client IP against whitelist & account permissions
        $clientIp = \App\Services\IpWhitelistService::getClientIp($request);
        $ipCheck = \App\Services\IpWhitelistService::check($user, $clientIp);
        if (!$ipCheck['allowed']) {
            return response()->json([
                'status' => $ipCheck['code'],
                'error' => $ipCheck['status'],
                'message' => $ipCheck['message'],
                'client_ip' => $ipCheck['client_ip'],
            ], $ipCheck['code']);
        }

        $request->merge([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'numbers' => $numbers,
            'contents' => $contents,
            'senderId' => $senderId,
        ]);

        $request->validate([
            'numbers' => 'required',
            'contents' => 'required',
            'schedule' => 'nullable',
            'schedule_type' => 'nullable',
            'senderId' => 'required|string',
        ]);

        $sender_id_info = Senderid::where(['user_id' => $user->id, 'sender' => $request->senderId])->where('gateway_id', '!=', 0)->first();

        if (!$sender_id_info) {
            return response()->json([
                'status' => 404,
                'message' => __('Sender id not found or not assigned to your account.'),
            ], 404);
        }

        $gateway_details = Smsgateway::where('id', $sender_id_info->gateway_id)->where('status', 1)->first();

        if (!$gateway_details) {
            return response()->json([
                'status' => 406,
                'message' => __("The SMS gateway associated with the selected sender ID is currently unavailable. Please contact support."),
            ], 406);
        }

        $isUnicode = didITypeUnicode($request->contents);
        $total_sms = wordCountDown($isUnicode, $request->contents);
        $total_numbers = count(explode(',', $request->numbers)); // Numbers in array
        $grand_total_sms = $total_numbers * $total_sms; // Total sms count
        $total_charges = calculateCharge($grand_total_sms, $sender_id_info, $user);

        if ($total_charges <= $user->balance) {
            DB::beginTransaction();
            try {

                $gateway = Smsgateway::whereStatus(1)->exists();
                if (!$gateway) {
                    return response()->json([
                        'message' => __("Api gateway is not available, Please contact with admin.")
                    ], 406);
                }

                if (in_array(trim($gateway_details->driver), ['panelsms', 'whiteinfobd'])) {
                    // Queue each SMS instead of direct sending
                    $params = $gateway_details->params;
                    $api_url = $params['api_url'] ?? $params['apiurl'] ?? '';
                    $api_key = $params['api_key'] ?? $params['key'] ?? '';
                    $client_id = $params['clientid'] ?? '';

                    $numbers = explode(',', $request->numbers);

                    foreach ($numbers as $number) {
                        dispatch(new \App\Jobs\SendSmsJob(
                            $number,
                            $request->contents,
                            [
                                'driver'   => trim($gateway_details->driver),
                                'senderid' => $sender_id_info->sender,
                                'apiurl'   => $api_url,
                                'apikey'   => $api_key,
                                'clientid' => $client_id,
                            ]
                        ))->onQueue('sms');
                    }
                    session()->put('gateway_id', $gateway_details->id);
                } else {
                    $response = sendMessage($request->numbers, $request->contents, $sender_id_info);

                    if (!$response) {
                        return response()->json(__('Please enter a valid phone number or contact with admin!'), 406);
                    }
                }


                $message_details = makeSms($request, $sender_id_info, $isUnicode, $user, $total_sms);
                foreach (array_chunk($message_details, 1000) as $chunk) {
                    Sms::insert($chunk);
                }

                $user->update([
                    'balance' => $user->balance - $total_charges
                ]);

                session()->forget('gateway_id');

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => "Message sent successfully",
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                \Illuminate\Support\Facades\Log::error('Send SMS API Error: ' . $e->getMessage());
                return response()->json([
                    'status' => 500,
                    'message' => __('Failed to process message: ') . $e->getMessage(),
                ], 500);
            }
        } else {
            return response()->json([
                'message' => __("You don't have enough balance. Please recharge now.")
            ], 406);
        }
    }
}
