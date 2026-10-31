<?php

namespace App\SmsGateways;

class TzskSms
{
    public static function send_message($gateway, $numbers, $contents)
    {
        foreach ($gateway->params as $key => $param) {
            config(['sms.drivers.'.$gateway->driver.'.'.$key => $param]);
        }

        sms()->via($gateway->driver)->send($contents, function($sms) use($numbers) {
            $sms->to(explode(',', $numbers));
        });

        return true;
    }
}
