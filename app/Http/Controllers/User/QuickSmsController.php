<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Senderid;
use App\Models\Sms;
use App\Models\Smsgateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\SmsGateways\PanelSms;

use App\Jobs\SendSmsJob;

class QuickSmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('expired')->only('store');
    }

    public function index()
    {
        $senderids = Senderid::where('user_id', auth()->id())->latest()->get();
        return view('users.quick-sms.index', compact('senderids'));
    }

    public function store(Request $request, PanelSms $sms)
    {
        $request->validate([
            'campaing_name' => 'nullable|string|max:250',
            'senderid_id' => 'required|string|max:20|exists:senderids,id',
            'numbers' => 'required|min:5',
            'contents' => 'required',
            'schedule_type' => 'required',
            'schedule' => 'nullable',
        ]);

        $user = auth()->user();
        $isUnicode = didITypeUnicode($request->contents);
        $total_sms = wordCountDown($isUnicode, $request->contents);
        $total_numbers = count(explode(',', $request->numbers)); // Numbers in array
        $grand_total_sms = $total_numbers * $total_sms; // Total sms count

        $sender_id_info = Senderid::where('user_id', $user->id)->where('gateway_id','!=',0)->findOrFail($request->senderid_id);
        $gateway_details = Smsgateway::where('id',$sender_id_info->gateway_id)->where('status',1)->first();

        if (!$gateway_details) {
            return response()->json([
                'message' => __("The SMS gateway associated with the selected sender ID is currently unavailable. Please contact support."),
            ], 406);
        }

        $total_charges = calculateCharge($grand_total_sms, $sender_id_info, $user);

        if ($total_charges <= $user->balance) {
            DB::beginTransaction();
            try {

                if ($request->schedule_type == 'sendnow') {
                    $gateway = Smsgateway::whereStatus(1)->exists();

                    if (!$gateway) {
                        return response()->json([
                            'message' => __("Api gateway is not available, Please contact with admin."),
                        ], 406);
                    }
                    if(trim($gateway_details->driver) == 'panelsms'){
                        $params = $gateway_details->params;
                        $api_url = $params['apiurl'];
                        $api_key = $params['key'];
                        $client_id = $params['clientid'];

                        $numbers = explode(',', $request->numbers);

                        foreach ($numbers as $number) {
                            dispatch(new \App\Jobs\SendSmsJob(
                                $number,
                                $request->contents,
                                [
                                    'senderid' => $sender_id_info->sender,
                                    'apiurl' => $api_url,
                                    'apikey' => $api_key,
                                    'clientid' => $client_id,
                                ]
                            ))->onQueue('sms');
                        }
                        session()->put('gateway_id', $gateway_details->id);
                    }else{
                        $response = sendMessage($request->numbers, $request->contents, $sender_id_info);

                        if (!$response) {
                            return response()->json(__('Invalid credentials, please contact with your sms service provider.'), 406);
                        }
                    }


                }

                $message_details = makeSms($request, $sender_id_info, $isUnicode, $user, $total_sms);
                Sms::insert($message_details);

                $user->update([
                    'balance' => $user->balance - $total_charges,
                ]);

                session()->forget('gateway_id');

                DB::commit();
                return response()->json([
                    'message' => __("Message sent successfully."),
                    'redirect' => route('users.quick-sms.index'),
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(__('Invalid phone number or credentials.'), 404);
            }
        } else {
            return response()->json([
                'message' => __("You don't have enough balance. Please recharge now."),
            ], 406);
        }
    }
}
