<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/ssl-commerz/payment/success',
        '/ssl-commerz/payment/failed',
        '/ssl-commerz/recharge/success',
        '/ssl-commerz/recharge/failed',
        'users/phonepe/status',
        'users/paytm/status',
        '/pipra-pay/webhook',
        '/pipra-pay/webhook/plan',
        '/pipra-pay/webhook/recharge',
        '/send-sms'
    ];
}
