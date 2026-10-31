<?php

namespace App\Http\Controllers\User;

use App\Models\Sms;
use App\Models\Senderid;
use App\Models\Smsgateway;
use Illuminate\Http\Request;
use App\Imports\NumberImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendSmsJob;

class BulkSmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('expired')->only('store');
    }

    public function index()
    {
        $senderids = Senderid::where('user_id', auth()->id())->latest()->get();
        return view('users.bulk-sms.index', compact('senderids'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaing_name' => 'nullable|string|max:250',
            'senderid_id' => 'required|string|max:20|exists:senderids,id',
            'file' => 'required|file|mimes:xlsx,xls,csv,ods|max:10240',
            'contents' => 'required',
            'schedule_type' => 'required',
            'schedule' => 'nullable',
        ]);

        $import = new NumberImport();
        Excel::import($import, $request->file('file'));
        $numbers = $import->numbers;

        $user = auth()->user();
        $isUnicode = didITypeUnicode($request->contents);
        $total_sms = wordCountDown($isUnicode, $request->contents);
        $total_numbers = count($numbers); // Numbers in array
        $grand_total_sms = $total_numbers * $total_sms; // Total sms count

        $sender_id_info = Senderid::where('user_id', $user->id)->where('gateway_id','!=',0)->findOrFail($request->senderid_id);
        $gateway_details = Smsgateway::where('id',$sender_id_info->gateway_id)->where('status',1)->first();
        $total_charges = calculateCharge($grand_total_sms, $sender_id_info, $user);

        if(!$gateway_details){
            return response()->json([
                'message' => __("The SMS gateway associated with the selected sender ID is currently unavailable. Please contact support."),
            ], 406);
        }

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

                        //$response = $sms->send($request->numbers, $request->contents,$sender_id_info->sender,$api_url,$api_key,$client_id);
                        // Queue each SMS instead of direct sending

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
                        $response = sendMessage($numbers, $request->contents, $sender_id_info);

                        if (!$response) {
                            return response()->json(__('Please enter a valid phone number or contact with admin!'), 406);
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
                    'redirect' => route('users.bulk-sms.index'),
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
