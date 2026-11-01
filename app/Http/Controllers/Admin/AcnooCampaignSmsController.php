<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampaignSms;
use App\Models\Senderid;
use App\Models\Sms;
use App\Models\Smsgateway;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcnooCampaignSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $campaign_sms = CampaignSms::with('campaign', 'senderid', 'user')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('numbers', 'like', '%' . $request->search . '%')
                        ->orWhere('charges', 'like', '%' . $request->search . '%')
                        ->orWhere('ip_address', 'like', '%' . $request->search . '%')
                        ->orWhere('contents', 'like', '%' . $request->search . '%')
                        ->orWhere('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('status', 'like', '%' . $request->search . '%');
                });
            })
            ->orWhereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('campaign', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('senderid', function ($q) use ($request) {
                $q->where('sender', 'like', '%' . $request->search . '%');
            })
            ->latest();

        if ($request->ajax()) {
            $campaign_sms = $campaign_sms->get();
            return response()->json([
                'data' => view('admin.campaigns_sms.datas', compact('campaign_sms'))->render(),
            ]);
        }

        $campaign_sms = $campaign_sms->paginate(10);
        return view('admin.campaigns_sms.index', compact('campaign_sms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:canceled,success',
        ]);

        DB::beginTransaction();
        try {

            $campaign_sms = CampaignSms::findOrFail($request->id);
            $user = User::findOrFail($campaign_sms->user_id);
            $sender_id_info = Senderid::find($campaign_sms->senderid_id);

            if ($campaign_sms->status != 'pending') {
                return response()->json([
                    'message' => "You can not update this campaign status.",
                ], 406);
            }

            if ($request->status == 'canceled') {

                $request->validate([
                    'notes' => 'required|max:1000',
                ]);
            } elseif ($request->status == 'success') {
                if ($user->balance >= $campaign_sms->charges) {

                    $total_sms = wordCountDown($campaign_sms->is_unicode, $campaign_sms->contents);

                    $response = [];
                    if (!$campaign_sms->schedule) {
                        $gateway = Smsgateway::whereStatus(1)->exists();
                        if (!$gateway) {
                            return response()->json([
                                'message' => __("Api gateway is not available, Please contact with admin."),
                            ], 406);
                        }
                        $response = sendMessage($campaign_sms->numbers, $campaign_sms->contents, $sender_id_info);
                        if (!$response) {
                            return response()->json(__('Please enter a valid phone number or contact with admin!'), 406);
                        }
                    }

                    $message_details = makeCampaignSms($campaign_sms, $sender_id_info, $user, $total_sms, $response);
                    foreach (array_chunk($message_details, 1000) as $chunk) {
                    Sms::insert($chunk);
                }

                    $user->update([
                        'balance' => $user->balance - $campaign_sms->charges,
                    ]);

                    session()->forget('gateway_id');
                } else {
                    return response()->json([
                        'message' => "Insufficient customer balance.",
                    ], 406);
                }
            }

            $campaign_sms->update([
                'notes' => $request->notes,
                'status' => $request->status,
            ]);

            DB::commit();
            return response()->json([
                'message' => "Campaign sms " . $request->status . " successfully",
                'redirect' => route('admin.campaigns_sms.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Invalid phone number or credentials.'), 404);
        }
    }
}
