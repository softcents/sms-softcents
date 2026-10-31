<?php

namespace App\SmsGateways;

use Illuminate\Support\Facades\Http;

class RouteMobile
{
    public static function send_message($gateway, $numbers, $contents, $sender_name = null)
    {
        $response = Http::asForm()->{$gateway->params['api_method']}($gateway->params['api_url'], array_merge([
            'dlr' => $gateway->params['dlr'],
            'type' => $gateway->params['type'],
            'source' => $sender_name ?? $gateway->params['source'],
            $gateway->params['number_key'] => $numbers,
            'username' => $gateway->params['username'],
            'password' => $gateway->params['password'],
            $gateway->params['message_key'] => $contents,
        ]));

        $words = explode('|', $response);
        $firstFourWords = array_slice($words, 0, 4);

        if ($firstFourWords[0] == 1701) {
            return true;
        } else {
            return false;
        }
    }
}