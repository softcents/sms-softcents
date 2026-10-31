<?php

namespace App\SmsGateways;

use Illuminate\Support\Facades\Http;

class PanelSms
{
    public function send($number, $message, $senderid, $apiurl, $apikey, $clientid)
    {
        //echo "PanelSms gateway hit for number: $number\n";
        //echo "Message: $message\n";

        $payload = [
            "SenderId"      => $senderid,
            "Is_Unicode"    => true,
            "Is_Flash"      => false,
            "DataCoding"    => "8",
            "SchedTime"     => "",
            "GroupId"       => "",
            "Message"       => $message,
            "MobileNumbers" => $number,
            "ApiKey"        => $apikey,
            "ClientId"      => $clientid,
        ];

        $response = Http::post($apiurl, $payload);

        //echo "Response: " . json_encode($response->json()) . "\n";

        return $response->json();
    }
}
