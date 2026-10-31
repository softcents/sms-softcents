<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\User;
use App\Models\Gateway;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subscribers = PlanSubscribe::with('user:id,name', 'plan:id,title', 'gateway:id,name')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('invoice_no', 'like', '%' . $request->search . '%')
                        ->orWhere('price', 'like', '%' . $request->search . '%')
                        ->orWhere('total_sms', 'like', '%' . $request->search . '%');
                });
            })
            ->orWhereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('plan', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })
            ->latest();

        if ($request->ajax()) {
            $subscribers = $subscribers->get();

            return response()->json([
                'data' => view('admin.subscribers.datas', compact('subscribers'))->render()
            ]);
        }

        $subscribers = $subscribers->paginate(10);
        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function show(string $id)
    {
        $subscribe = PlanSubscribe::with('user:id,name', 'gateway:id,name,manual_data,is_manual', 'plan:id,title')->findOrFail($id);
        return view('admin.subscribers.show', compact('subscribe'));
    }

    public function print(string $id)
    {
        $subscribe = PlanSubscribe::with('user:id,name', 'gateway:id,name', 'plan:id,title')->findOrFail($id);
        return view('admin.subscribers.print', compact('subscribe'));
    }

    public function status(Request $request, $id)
    {
        $subscribe = PlanSubscribe::findOrFail($id);
        $plan = Plan::findOrFail($subscribe->plan_id);
        $gateway = Gateway::findOrFail($subscribe->gateway_id);

        if ($subscribe->status != 'pending') {
            return response()->json(__('You can not change ' . $subscribe->status . ' to ' . $request->status), 406);
        }

        if (in_array($request->status, ['approved', 'canceled'])) {

            DB::beginTransaction();
            try {

                if ($request->status == 'approved') {
                    $user = User::findOrFail($subscribe->user_id);

                    Transaction::create([
                        'user_id' => $user->id,
                        'gateway_id' => $gateway->id,
                        'amount' => $plan->price,
                        'charge' => $gateway->recharge,
                        'reason' => "Subscription Purchased",
                        'type' => 'credit',
                    ]);

                    $user->update([
                        'balance' => $request->status == 'approved' ? ($user->balance + $subscribe->price) : ($user->balance - $subscribe->price),
                        'allow_api' => $request->status == 'approved' ? $plan->allow_api : 0,
                        'plan_id' => $request->status == 'approved' ? $subscribe->plan_id : NULL,
                        'masking_rate' => $request->status == 'approved' ? $subscribe->masking_rate : 0,
                        'will_expire' => $request->status == 'approved' ? $subscribe->will_expire : NULL,
                        'non_masking_rate' => $request->status == 'approved' ? $subscribe->non_masking_rate : 0,
                    ]);
                }

                $subscribe->update([
                    'status' => $request->status,
                    'notes' => $request->notes,
                ]);

                DB::commit();
                return response()->json([
                    'redirect' => route('admin.subscribers.index'),
                    'message' => __('Subscription has been ' . $request->status)
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'redirect' => route('admin.subscribers.index'),
                    'message' => __('Something went wrong!')
                ], 406);
            }
        } else {
            return response()->json([
                'redirect' => route('admin.subscribers.index'),
                'message' => __("You're a fraud!")
            ], 406);
        }
    }
}
