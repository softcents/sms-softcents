<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Senderid;
use App\Models\Sms;
use App\Models\Smsgateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendSmsJob;

class SoftcentsGroupSmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('expired')->only('store');
    }

    public function index()
    {
        $groups = Group::where('user_id', auth()->id())->withCount('contacts')->latest()->get();
        $senderids = Senderid::where('user_id', auth()->id())
            ->where('status', true)
            ->latest('id')
            ->get();

        return view('users.group-sms.index', compact('senderids', 'groups'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'campaing_name' => 'nullable|string|max:250',
            'senderid_id' => 'required|string|max:20|exists:senderids,id',
            'group_id' => 'required|string|max:20|exists:groups,id',
            'contents' => 'required',
            'range_start' => 'nullable|integer|min:1',
            'range_end' => 'nullable|string',
            'schedule_type' => 'required',
            'schedule' => 'nullable',
        ]);

        $rawNumbers = Contact::where('group_id', $request->group_id)->pluck('number')->toArray();
        $start = max(1, (int)($request->range_start ?: 1));
        $startIndex = $start - 1;
        $rangeEndVal = trim((string)($request->range_end ?: 'All'));

        if (strtolower($rangeEndVal) === 'all' || empty($rangeEndVal)) {
            $numbers = array_slice($rawNumbers, $startIndex);
        } else {
            $end = (int)$rangeEndVal;
            if ($end >= $start) {
                $count = $end - $start + 1;
            } else {
                $count = max(1, $end);
            }
            $numbers = array_slice($rawNumbers, $startIndex, $count);
        }

        if (empty($numbers)) {
            return response()->json([
                'message' => __('No contacts found in the selected range.'),
            ], 422);
        }

        $request->merge(['numbers' => implode(',', $numbers)]);

        $user = auth()->user();
        $isUnicode = didITypeUnicode($request->contents);
        $total_sms = wordCountDown($isUnicode, $request->contents);

        $sender_id_info = Senderid::where('user_id', $user->id)->where('gateway_id','!=',0)->findOrFail($request->senderid_id);
        $gateway_details = Smsgateway::where('id',$sender_id_info->gateway_id)->where('status',1)->first();

        if(!$gateway_details){
            return response()->json([
                'message' => __("The SMS gateway associated with the selected sender ID is currently unavailable. Please contact support."),
            ], 406);
        }

        $grand_total_sms = count($numbers) * $total_sms; // Total sms count
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
                    if (in_array(trim($gateway_details->driver), ['panelsms', 'whiteinfobd', 'anbernet'])) {
                        // Queue each SMS instead of direct sending
                        $params = is_array($gateway_details->params) ? $gateway_details->params : (json_decode($gateway_details->params, true) ?: []);
                        $api_url = $params['api_url'] ?? $params['apiurl'] ?? '';
                        $api_key = $params['api_key'] ?? $params['key'] ?? '';
                        $client_id = $params['clientid'] ?? '';

                        foreach ($numbers as $number) {
                            dispatch(new \App\Jobs\SendSmsJob(
                                $number,
                                $request->contents,
                                array_merge($params, [
                                    'driver'   => trim($gateway_details->driver),
                                    'senderid' => $sender_id_info->sender,
                                    'apiurl'   => $api_url,
                                    'apikey'   => $api_key,
                                    'clientid' => $client_id,
                                ])
                            ))->onQueue('sms');
                        }
                        session()->put('gateway_id', $gateway_details->id);
                    } else {
                        $response = sendMessage($numbers, $request->contents, $sender_id_info);

                        if (!$response) {
                            return response()->json(__('Please enter a valid phone number or contact with admin!'), 406);
                        }
                    }
                }

                $message_details = makeSms($request, $sender_id_info, $isUnicode, $user, $total_sms);
                foreach (array_chunk($message_details, 1000) as $chunk) {
                    Sms::insert($chunk);
                }

                $user->update([
                    'balance' => $user->balance - $total_charges,
                ]);

                session()->forget('gateway_id');

                DB::commit();
                return response()->json([
                    'message' => "Message sent successfully",
                    'redirect' => route('users.group-sms.store'),
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
