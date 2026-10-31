<?php

namespace App\Library;

use App\Models\Gateway;
use App\Models\Plan;
use App\Models\Recharge;
use App\Models\Transaction;
use App\Models\PlanSubscribe;
use App\Models\PiprapayTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Throwable;

class PipraPay
{
    public static function redirect_if_payment_success()
    {
        if (Session::has('fund_callback')) {
            return url(Session::get('fund_callback')['success_url']);
        }

        return url('users/payment/success');
    }

    public static function redirect_if_payment_faild()
    {
        if (Session::has('fund_callback')) {
            return url(Session::get('fund_callback')['cancel_url']);
        }

        return url('users/payment/failed');
    }

    public static function fallback()
    {
        return route('users.pipra-pay.status');
    }

    public static function make_payment($array)
    {
        $gateway = Gateway::findOrFail($array['gateway_id']);
        $apiKey = $array['api_key'] ?? '';
        $baseUrl = self::baseUrl($array, $gateway);

        $data = [
            'api_key' => $apiKey,
            'base_url' => $baseUrl,
            'payment_mode' => 'pipra_pay',
            'amount' => $array['pay_amount'],
            'charge' => $array['charge'],
            'phone' => $array['phone'],
            'gateway_id' => $array['gateway_id'],
            'main_amount' => $array['amount'],
            'payment_type' => $array['payment_type'] ?? '',
            'billName' => $array['billName'],
            'name' => $array['name'],
            'email' => $array['email'],
            'currency' => $array['currency'],
        ];

        $webhookUrl = ($array['payment_type'] ?? '') === 'recharge_payment'
            ? route('pipra-pay.webhook.recharge')
            : route('pipra-pay.webhook.plan');

        try {
            $response = Http::withHeaders([
                'MHS-PIPRAPAY-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->baseUrl($baseUrl)->post('checkout/redirect', [
                'full_name' => $array['name'],
                'email_address' => $array['email'],
                'mobile_number' => $array['phone'] ?? '',
                'amount' => (string) $array['pay_amount'],
                'currency' => $array['currency'],
                'metadata' => json_encode([
                    'gateway_id' => $array['gateway_id'],
                    'payment_type' => $array['payment_type'] ?? '',
                    'user_id' => auth()->id(),
                ]),
                'return_url' => self::fallback(),
                'webhook_url' => $webhookUrl,
            ]);

            if (!$response->successful()) {
                session()->put('payment_msg', $response->json('error.message') ?? __('Unable to initialize Pipra Pay payment.'));

                return request()->expectsJson() ? self::redirect_if_payment_faild() : redirect(self::redirect_if_payment_faild());
            }

            $responseBody = $response->json();
            $checkoutUrl = $responseBody['pp_url'] ?? null;
            $ppId = $responseBody['pp_id'] ?? null;

            if (!$checkoutUrl || !$ppId) {
                session()->put('payment_msg', __('Invalid Pipra Pay checkout response.'));

                return request()->expectsJson() ? self::redirect_if_payment_faild() : redirect(self::redirect_if_payment_faild());
            }

            $data['pp_id'] = $ppId;
            Session::put('pipra_pay_credentials', $data);

            PiprapayTransaction::create([
                'pp_id' => $ppId,
                'user_id' => auth()->id(),
                'gateway_id' => $array['gateway_id'],
                'payment_type' => $array['payment_type'] ?? '',
                'amount' => $array['amount'],
                'charge' => $array['charge'],
                'plan_id' => $array['plan_id'] ?? null,
                'status' => 'pending',
            ]);

            return request()->expectsJson() ? $checkoutUrl : redirect($checkoutUrl);
        } catch (Throwable $th) {
            session()->put('payment_msg', $th->getMessage());

            return request()->expectsJson() ? self::redirect_if_payment_faild() : redirect(self::redirect_if_payment_faild());
        }
    }

    public function status(Request $request)
    {
        if (!Session::has('pipra_pay_credentials')) {
            return redirect(self::redirect_if_payment_faild());
        }

        $info = Session::get('pipra_pay_credentials');
        $ppId = $request->query('pp_id', $request->query('invoice_id', $info['pp_id'] ?? null));

        if (!$ppId) {
            Session::forget('pipra_pay_credentials');

            return redirect(self::redirect_if_payment_faild());
        }

        try {
            $response = Http::withHeaders([
                'MHS-PIPRAPAY-API-KEY' => $info['api_key'],
                'Content-Type' => 'application/json',
            ])->baseUrl($info['base_url'])->post('verify-payment', [
                'pp_id' => $ppId,
            ]);

            $payment = $response->json();

            if ($response->successful() && strtolower($payment['status'] ?? '') === 'completed' && $this->amountMatches($payment, $info)) {
                Session::forget('pipra_pay_credentials');

                $pending = PiprapayTransaction::where('pp_id', $ppId)->first();

                if ($pending && $pending->status === 'completed') {
                    Session::put('payment_info', [
                        'payment_id' => $payment['transaction_id'] ?? $ppId,
                        'payment_method' => 'pipra_pay',
                        'gateway_id' => $info['gateway_id'],
                        'payment_type' => $info['payment_type'],
                        'amount' => $info['main_amount'],
                        'charge' => $info['charge'],
                        'status' => 1,
                        'payment_status' => 1,
                        'already_processed' => true,
                    ]);
                } else {
                    if ($pending) {
                        $pending->update(['status' => 'completed']);
                    }

                    Session::put('payment_info', [
                        'payment_id' => $payment['transaction_id'] ?? $ppId,
                        'payment_method' => 'pipra_pay',
                        'gateway_id' => $info['gateway_id'],
                        'payment_type' => $info['payment_type'],
                        'amount' => $info['main_amount'],
                        'charge' => $info['charge'],
                        'status' => 1,
                        'payment_status' => 1,
                    ]);
                }

                return redirect(self::redirect_if_payment_success());
            }

            session()->put('payment_msg', $payment['error']['message'] ?? __('Pipra Pay payment was not completed.'));
        } catch (Throwable $th) {
            session()->put('payment_msg', $th->getMessage());
        }

        Session::forget('pipra_pay_credentials');
        Session::put('payment_info', ['payment_status' => 0]);

        return redirect(self::redirect_if_payment_faild());
    }

    public function webhook(Request $request)
    {
        return response()->json([
            'status' => $request->all() ? 'ok' : 'error',
            'message' => $request->all() ? null : 'Invalid payload',
        ], 200);
    }

    public function webhookPlan(Request $request)
    {
        $payload = $request->all();

        if (!$payload || empty($payload['pp_id'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 200);
        }

        $ppId = $payload['pp_id'];
        $webhookStatus = strtolower($payload['status'] ?? '');

        $pendingTransaction = PiprapayTransaction::where('pp_id', $ppId)->first();

        if (!$pendingTransaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 200);
        }

        if ($pendingTransaction->status !== 'pending') {
            return response()->json(['status' => 'ok']);
        }

        if ($webhookStatus !== 'completed') {
            $pendingTransaction->update(['status' => 'failed']);

            return response()->json(['status' => 'ok']);
        }

        $gateway = Gateway::find($pendingTransaction->gateway_id);

        if (!$gateway) {
            return response()->json(['status' => 'error', 'message' => 'Gateway not found'], 200);
        }

        if (!$this->verifyWithPiprapay($ppId, $gateway, $pendingTransaction->amount)) {
            return response()->json(['status' => 'error', 'message' => 'Payment verification failed'], 200);
        }

        $plan = Plan::find($pendingTransaction->plan_id);

        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'Plan not found'], 200);
        }

        $user = User::find($pendingTransaction->user_id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 200);
        }

        DB::beginTransaction();
        try {
            $duration_in_days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 0)))));

            $subscribe = PlanSubscribe::create([
                'plan_data' => $plan,
                'plan_id' => $plan->id,
                'status' => 'approved',
                'price' => $plan->price,
                'user_id' => $user->id,
                'gateway_id' => $gateway->id,
                'total_sms' => $plan->total_sms,
                'masking_rate' => $plan->masking_rate,
                'non_masking_rate' => $plan->non_masking_rate,
                'will_expire' => now()->addDays($duration_in_days),
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'gateway_id' => $gateway->id,
                'amount' => $plan->price,
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

            $pendingTransaction->update([
                'status' => 'completed',
                'transaction_id' => $payload['transaction_id'] ?? null,
            ]);

            sendNotification($subscribe->id, route('admin.recharges.index', ['id' => $subscribe->id]), __('New subscription purchased via webhook.'));

            DB::commit();

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' => 'Processing failed'], 200);
        }
    }

    public function webhookRecharge(Request $request)
    {
        $payload = $request->all();

        if (!$payload || empty($payload['pp_id'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 200);
        }

        $ppId = $payload['pp_id'];
        $webhookStatus = strtolower($payload['status'] ?? '');

        $pendingTransaction = PiprapayTransaction::where('pp_id', $ppId)->first();

        if (!$pendingTransaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 200);
        }

        if ($pendingTransaction->status !== 'pending') {
            return response()->json(['status' => 'ok']);
        }

        if ($webhookStatus !== 'completed') {
            $pendingTransaction->update(['status' => 'failed']);

            return response()->json(['status' => 'ok']);
        }

        $gateway = Gateway::find($pendingTransaction->gateway_id);

        if (!$gateway) {
            return response()->json(['status' => 'error', 'message' => 'Gateway not found'], 200);
        }

        if (!$this->verifyWithPiprapay($ppId, $gateway, $pendingTransaction->amount)) {
            return response()->json(['status' => 'error', 'message' => 'Payment verification failed'], 200);
        }

        $user = User::find($pendingTransaction->user_id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 200);
        }

        DB::beginTransaction();
        try {
            $recharge = Recharge::create([
                'status' => 'approved',
                'user_id' => $user->id,
                'gateway_id' => $gateway->id,
                'amount' => $pendingTransaction->amount,
            ]);

            Transaction::create([
                'type' => 'credit',
                'user_id' => $user->id,
                'gateway_id' => $gateway->id,
                'charge' => $pendingTransaction->charge,
                'reason' => "Balance Recharged",
                'amount' => $pendingTransaction->amount,
            ]);

            $user->update([
                'balance' => $user->balance + $pendingTransaction->amount,
            ]);

            $pendingTransaction->update([
                'status' => 'completed',
                'transaction_id' => $payload['transaction_id'] ?? null,
            ]);

            sendNotification($recharge->id, route('admin.recharges.index', ['id' => $recharge->id]), __('New recharge via webhook.'));

            DB::commit();

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' => 'Processing failed'], 200);
        }
    }

    private function verifyWithPiprapay(string $ppId, Gateway $gateway, float $amount): bool
    {
        $apiKey = $gateway->data['api_key'] ?? '';
        $baseUrl = $gateway->data['base_url'] ?? '';

        if (empty($baseUrl)) {
            $baseUrl = $gateway->mode === 'Live' ? 'https://piprapay.com' : 'https://sandbox.piprapay.com';
        }

        if (empty($apiKey)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'MHS-PIPRAPAY-API-KEY' => $apiKey,
                'Content-Type' => 'application/json',
            ])->baseUrl($baseUrl)->post('verify-payment', [
                'pp_id' => $ppId,
            ]);

            if (!$response->successful()) {
                return false;
            }

            $payment = $response->json();

            if (strtolower($payment['status'] ?? '') !== 'completed') {
                return false;
            }

            if (isset($payment['amount'])) {
                return round((float) $payment['amount'], 2) === round($amount, 2);
            }

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    private static function baseUrl(array $array, Gateway $gateway): string
    {
        if (!empty($array['base_url'])) {
            return rtrim($array['base_url'], '/');
        }

        return $gateway->mode === 'Live' ? 'https://piprapay.com' : 'https://sandbox.piprapay.com';
    }

    private function amountMatches(array $payment, array $info): bool
    {
        if (!isset($payment['amount'])) {
            return true;
        }

        return round((float) $payment['amount'], 2) === round((float) $info['amount'], 2);
    }
}
