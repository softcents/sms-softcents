<?php

namespace App\Http\Controllers\User;

use App\Models\Sms;
use App\Models\Campaign;
use App\Models\Senderid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CampaignSms;

class AcnooCampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('expired')->only('store');
    }

    public function index(Request $request)
    {
        $campaigns = CampaignSms::with('campaign', 'senderid')->where('user_id', auth()->id())
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('contents', 'like', '%' . $request->search . '%')
                        ->orWhere('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('numbers', 'like', '%' . $request->search . '%');
                });
            })
            ->orWhereHas('campaign', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('total_numbers', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('senderid', function ($q) use ($request) {
                $q->where('sender', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%');
            })
            ->latest();

        if ($request->ajax()) {
            $campaigns = $campaigns->get();
            return response()->json([
                'data' => view('users.campaigns.datas', compact('campaigns'))->render()
            ]);
        }

        $campaigns = $campaigns->paginate(10);
        return view('users.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $campaigns = Campaign::latest()->get();
        $senderids = Senderid::where('user_id', auth()->id())->latest()->get();
        return view('users.campaigns.create', compact('senderids', 'campaigns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaing_name' => 'nullable|string|max:250',
            'senderid_id' => 'required|max:20|exists:senderids,id',
            'campaign_id' => 'required|max:20|exists:campaigns,id',
            'contents' => 'required',
            'range' => 'required|integer|min:1',
            'schedule_type' => 'required',
            'schedule' => 'nullable',
        ]);

        DB::beginTransaction();
        try {

            $user = auth()->user();
            $isUnicode = didITypeUnicode($request->contents);
            $sender_id_info = Senderid::where('user_id', $user->id)->findOrFail($request->senderid_id);

            // For slice sms range.
            $campaing = Campaign::findOrFail($request->campaign_id);
            $numbers_array = explode(',', $campaing->numbers);
            $numbers = array_slice($numbers_array, 0, $request->range);
            $sliced_numbers = implode(',', $numbers);

            $total_sms = wordCountDown($isUnicode, $request->contents);
            $grand_total_sms = count($numbers) * $total_sms; // Total sms count
            $total_charges = calculateCharge($grand_total_sms, $sender_id_info, $user);

            if ($total_charges <= $user->balance) {
                $campaign_sms = CampaignSms::create($request->all() + [
                                    'user_id' => auth()->id(),
                                    'numbers' => $sliced_numbers,
                                    'charges' => $total_charges,
                                    'is_unicode' => $isUnicode,
                                    'ip_address' => $request->ip(),
                                    'status' => 'pending',
                                ]);

                sendNotification($campaign_sms->id, route('admin.campaigns.index', ['id' => $campaign_sms->id]), __('New campaing has been created.'));

                DB::commit();
                return response()->json([
                    'message' => __('Campaign sms created successfully'),
                    'redirect' => route('users.campaigns.index')
                ]);
            } else {
                return response()->json([
                    'message' => __("You don't have enough balance. Please recharge now.")
                ], 406);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something went wrong!'), 404);
        }
    }

    public function show(string $id)
    {
        DB::beginTransaction();
        try {

            $user = auth()->user();
            $sms = CampaignSms::findOrFail($id);

            if ($sms->status != 'pending') {
                return response()->json([
                    'message' => __('You can not do this for this campaing.'),
                ], 406);
            }

            if ($sms->charges <= $user->balance) {

                $newSms = $sms->replicate();
                $newSms->save();

                sendNotification($sms->id, route('admin.campaigns.index', ['id' => $sms->id]), __('New campaing has been created.'));

                DB::commit();
                return response()->json([
                    'message' => __('Campaign sms created successfully'),
                    'redirect' => route('users.campaigns.index')
                ]);
            } else {
                return response()->json([
                    'message' => __("You don't have enough balance. Please recharge now.")
                ], 406);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something went wrong!'), 404);
        }
    }

    public function edit(string $id)
    {
        $sms = Sms::findOrFail($id);
        abort_if($sms->status != 'pending', 404);

        $campaigns = Campaign::latest()->get();
        $senderids = Senderid::where('user_id', auth()->id())->latest()->get();

        return view('users.campaigns.edit', compact('sms', 'senderids', 'campaigns'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'campaing_name' => 'nullable|string|max:250',
            'senderid_id' => 'required|max:20|exists:senderids,id',
            'campaign_id' => 'required|max:20|exists:campaigns,id',
            'contents' => 'required',
            'schedule_type' => 'required',
            'schedule' => 'nullable',
        ]);

        $sms = Sms::findOrFail($id);

        if ($sms->status != 'pending') {
            return response()->json([
                'message' => __('You can not edit this campaing right now.'),
            ], 406);
        }

        $campaing = Campaign::findOrFail($request->campaign_id);
        $sms->update($request->all() + [
            'user_id' => auth()->id(),
            'numbers' => $campaing->numbers,
        ]);

        return response()->json([
            'message' => __('Campaign sms updated successfully'),
            'redirect' => route('users.campaigns.index')
        ]);
    }

    public function destroy(string $id)
    {
        $sms = Sms::findOrFail($id);

        if ($sms->status != 'pending') {
            return response()->json([
                'message' => __('You can not edit this campaing right now.'),
            ], 406);
        }
        $sms->delete();

        return response()->json([
            'message' => __('Campaign sms deleted successfully'),
            'redirect' => route('users.campaigns.index')
        ]);
    }
}
