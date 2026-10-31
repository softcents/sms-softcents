<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Models\Gateway;
use App\Models\Transaction;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    use HasUploader;
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $gateways = Gateway::whereStatus(1)->with('currency:id,code,rate,symbol,position')->get();
        $plan = Plan::findOrFail(request('plan_id'));

        return view('payments.index', compact('gateways', 'plan'));
    }

    /**
     * Store a newly created resource in storage.
    */
    public function payment(Request $request, $plan_id, $gateway_id)
    {
        $request->validate([
            'phone' => 'max:15|min:5',
        ]);

        $plan = Plan::findOrFail($plan_id);
        $gateway = Gateway::findOrFail($gateway_id);

        if ($gateway->is_manual) {
            $request->validate([
                'attachment' => 'required|max:2048|file',
            ]);

            $duration_in_days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 0)))));

            $subscribe = PlanSubscribe::create([
                'plan_data' => $plan,
                'plan_id' => $plan->id,
                'price' => $plan->price,
                'user_id' => auth()->id(),
                'gateway_id' => $gateway_id,
                'total_sms' => $plan->total_sms,
                'masking_rate' => $plan->masking_rate,
                'non_masking_rate' => $plan->non_masking_rate,
                'will_expire' => now()->addDays($duration_in_days),
                'attachment' => $this->upload($request, 'attachment'),
                'manual_data' => $request->manual_data,
            ]);

            sendNotification($subscribe->id, route('admin.recharges.index', ['id' => $subscribe->id]), __('New subscription purchased requested.'));
            return redirect(route('users.plans.index'))->with('message', __('Plan purchased successfully.'));
        }

        $amount = $plan->price;

        if ($gateway->namespace == 'App\Library\SslCommerz') {
            Session::put('fund_callback.success_url', '/payment/success');
            Session::put('fund_callback.cancel_url', '/payment/failed');
        } else {
            Session::put('fund_callback.success_url', '/payment/success');
            Session::put('fund_callback.cancel_url', '/payment/failed');
        }

        $payment_data['currency'] = $gateway->currency->code ?? 'USD';
        $payment_data['email'] = auth()->user()->email;
        $payment_data['name'] = auth()->user()->name;
        $payment_data['phone'] = $request->input('phone');
        $payment_data['billName'] = __('Make plan purchase payment');
        $payment_data['amount'] = $amount;
        $payment_data['test_mode'] = $gateway->test_mode;
        $payment_data['charge'] = $gateway->charge ?? 0;
        $payment_data['pay_amount'] = round(convert_money($amount, $gateway->currency) + $gateway->charge);
        $payment_data['gateway_id'] = $gateway->id;
        $payment_data['payment_type'] = 'plan_payment';
        $payment_data['request_from'] = 'merchant';
        $payment_data['plan_id'] = $plan->id;

        foreach ($gateway->data ?? [] as $key => $info) {
            $payment_data[$key] = $info;
        }

        session()->put('gateway_id', $gateway->id);
        session()->put('plan', $plan);

        $redirect = $gateway->namespace::make_payment($payment_data);
        return $redirect;
    }

    public function success()
    {
        if (session('payment_info.already_processed')) {
            session()->forget('gateway_id');
            session()->forget('plan');
            session()->forget('payment_info');

            return redirect(route('users.plans.index'))->with('message', __('Plan purchased successfully.'));
        }

        DB::beginTransaction();
        try {

            /** @var Plan $plan */
            $plan = session('plan');
            $gateway_id = session('gateway_id');
            if (!$plan) {
                return redirect(route('users.plans.index'))->with('error', __('Transaction failed, Please try again.'));
            }

            $user = Auth::user();
            $duration_in_days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 0)))));

            $subscribe = PlanSubscribe::create([
                'plan_data' => $plan,
                'plan_id' => $plan->id,
                'status' => 'approved',
                'price' => $plan->price,
                'user_id' => auth()->id(),
                'gateway_id' => $gateway_id,
                'total_sms' => $plan->total_sms,
                'masking_rate' => $plan->masking_rate,
                'non_masking_rate' => $plan->non_masking_rate,
                'will_expire' => now()->addDays($duration_in_days),
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'gateway_id' => $gateway_id,
                'amount' => $plan->price,
                // 'charge' => , Should be changed
                'reason' => "Subscription Purchased",
                'type' => 'credit',
            ]);

            $user->update([
                'allow_api' => $plan->allow_api,
                'plan_id' => $subscribe->plan_id,
                'will_expire' => $subscribe->will_expire,
                'masking_rate' => $subscribe->masking_rate,
                'balance' => $user->balance + $subscribe->price,
                'non_masking_rate' => $subscribe->non_masking_rate,
            ]);

            sendNotification($subscribe->id, route('admin.recharges.index', ['id' => $subscribe->id]), __('New subscription purchased.'));

            session()->forget('gateway_id');
            session()->forget('plan');

            DB::commit();
            return redirect(route('users.plans.index'))->with('message', __('Plan purchased successfully.'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('users.plans.index'))->with('message', __('Something went wrong!'));
        }
    }

    public function failed()
    {
        return redirect(route('users.plans.index'))->with('error', __('Transaction failed, Please try again.'));
    }

    public function sslCommerzSuccess(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!$request->value_a || !$request->value_b || !$request->value_c) {
                return redirect(route('users.plans.index'))->with('error', __('Transaction failed, Please try again.'));
            }

            $user = User::find($request->value_c);
            Auth::login($user);

            $gateway_id = $request->value_b;
            $plan = Plan::findOrFail($request->value_a);

            $duration_in_days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 0)))));

            $subscribe = PlanSubscribe::create([
                'plan_data' => $plan,
                'plan_id' => $plan->id,
                'status' => 'approved',
                'price' => $plan->price,
                'user_id' => auth()->id(),
                'gateway_id' => $gateway_id,
                'total_sms' => $plan->total_sms,
                'masking_rate' => $plan->masking_rate,
                'non_masking_rate' => $plan->non_masking_rate,
                'will_expire' => now()->addDays($duration_in_days),
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'gateway_id' => $gateway_id,
                'amount' => $plan->price,
                // 'charge' => ,
                'reason' => "Subscription Purchased",
                'type' => 'credit',
            ]);

            $user->update([
                'allow_api' => $plan->allow_api,
                'plan_id' => $subscribe->plan_id,
                'will_expire' => $subscribe->will_expire,
                'masking_rate' => $subscribe->masking_rate,
                'balance' => $user->balance + $subscribe->price,
                'non_masking_rate' => $subscribe->non_masking_rate,
            ]);

            sendNotification($subscribe->id, route('admin.recharges.index', ['id' => $subscribe->id]), __('New subscription purchased.'));

            DB::commit();
            return redirect(route('users.plans.index'))->with('message', __('Plan purchased successfully.'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('users.plans.index'))->with('message', __('Something went wrong!'));
        }
    }

    public function sslCommerzFailed()
    {
        return redirect(route('users.plans.index'))->with('error', __('Transaction failed, Please try again.'));
    }
}
