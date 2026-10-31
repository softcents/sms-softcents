<?php

namespace App\Http\Controllers\User;

use App\Helpers\HasUploader;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Recharge;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RechargePaymentController extends Controller
{
    use HasUploader;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amount = session('amount');
        $gateways = Gateway::with('currency:id,code,rate,symbol,position')->where('status', 1)->get();

        return view('users.payments.index', compact('gateways', 'amount'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function recharge(Request $request, $gateway_id)
    {
        $amount = session('amount');
        $gateway = Gateway::findOrFail($gateway_id);

        if ($gateway->is_manual) {
            $request->validate([
                'attachment' => 'required|max:2048|file',
            ]);

            $recharge = Recharge::create([
                'amount' => $amount,
                'user_id' => auth()->id(),
                'gateway_id' => $gateway->id,
                'manual_data' => $request->manual_data,
                'attachment' => $this->upload($request, 'attachment'),
            ]);

            session()->forget('amount');
            sendNotification($recharge->id, route('admin.recharges.index', ['id' => $recharge->id]), __('New recharge requested.'));
            return redirect(route('users.recharges.index'))->with('message', __('Recharge request successfully. Please wait for admin approval.'));
        }

        $amount = session('amount');
        session()->put('gateway', $gateway);

        if ($gateway->namespace == 'App\Library\SslCommerz') {
            Session::put('fund_callback.success_url', '/ssl-commerz/recharge/success');
            Session::put('fund_callback.cancel_url', '/ssl-commerz/recharge/failed');
        } else {
            Session::put('fund_callback.success_url', '/users/recharge/success');
            Session::put('fund_callback.cancel_url', '/users/recharge/failed');
        }

        $payment_data['currency'] = $gateway->currency->code ?? 'USD';
        $payment_data['email'] = auth()->user()->email;
        $payment_data['name'] = auth()->user()->name;
        $payment_data['phone'] = $request->input('phone');
        $payment_data['billName'] = __('Make recharge payment');
        $payment_data['amount'] = $amount;
        $payment_data['test_mode'] = $gateway->test_mode;
        $payment_data['charge'] = $gateway->charge ?? 0;
        $payment_data['pay_amount'] = round(convert_money($amount, $gateway->currency) + $gateway->charge);
        $payment_data['gateway_id'] = $gateway->id;
        $payment_data['payment_type'] = 'recharge_payment';
        $payment_data['request_from'] = 'merchant';

        foreach ($gateway->data ?? [] as $key => $info) {
            $payment_data[$key] = $info;
        }

        $redirect = $gateway->namespace::make_payment($payment_data);
        return $redirect;
    }

    public function success()
    {
        if (session('payment_info.already_processed')) {
            session()->forget('gateway');
            session()->forget('amount');
            session()->forget('payment_info');

            return redirect(route('users.recharges.index'))->with('message', __('Recharge successfully.'));
        }

        DB::beginTransaction();
        try {

            if (!session('amount') || !session('gateway')) {
                return redirect(route('users.recharges.index'))->with('error', __('Transaction failed, Please try again.'));
            }

            $user = auth()->user();
            /** @var Gateway $gateway */
            $gateway = session('gateway');

            $recharge = Recharge::create([
                'status' => 'approved',
                'user_id' => auth()->id(),
                'gateway_id' => $gateway->id,
                'amount' => session('amount'),
            ]);

            Transaction::create([
                'type' => 'credit',
                'user_id' => $user->id,
                'gateway_id' => $gateway->id,
                'charge' => $gateway->charge,
                'reason' => "Balance Recharged",
                'amount' => session('amount'),
            ]);

            $user->update([
                'balance' => $user->balance + session('amount'),
            ]);

            sendNotification($recharge->id, route('admin.recharges.index', ['id' => $recharge->id]), __('New recharged.'));

            session()->forget('gateway');
            session()->forget('amount');

            DB::commit();
            return redirect(route('users.recharges.index'))->with('message', __('Recharge successfully.'));

        } catch (Exception $e) {
            DB::rollback();
            return redirect(route('users.recharges.index'))->with('message', __('Something went wrong!'));
        }
    }

    public function failed()
    {
        return redirect(route('users.recharges.index'))->with('error', __('Transaction failed, Please try again.'));
    }

    public function sslCommerzSuccess(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!$request->value_a || !$request->value_b || !$request->value_c) {
                return redirect(route('users.recharges.index'))->with('error', __('Transaction failed, Please try again.'));
            }

            $user = User::find($request->value_c);
            Auth::login($user);

            $gateway = Gateway::findOrFail($request->value_b);

            $recharge = Recharge::create([
                'status' => 'approved',
                'user_id' => auth()->id(),
                'gateway_id' => $gateway->id,
                'amount' => $request->amount - $gateway->charge,
            ]);

            Transaction::create([
                'type' => 'credit',
                'user_id' => $user->id,
                'gateway_id' => $gateway->id,
                'charge' => $gateway->charge,
                'reason' => "Balance Recharged",
                'amount' => $request->amount - $gateway->charge,
            ]);

            $user->update([
                'balance' => $user->balance + ($request->amount - $gateway->charge)
            ]);

            sendNotification($recharge->id, route('admin.recharges.index', ['id' => $recharge->id]), __('New recharged.'));

            DB::commit();
            return redirect(route('users.recharges.index'))->with('message', __('Recharge successfully.'));

        } catch (Exception $e) {
            DB::rollback();
            return redirect(route('users.recharges.index'))->with('error', __('Something went wrong!'));
        }
    }

    public function sslCommerzFailed()
    {
        return redirect(route('users.recharges.index'))->with('error', __('Transaction failed, Please try again.'));
    }
}
