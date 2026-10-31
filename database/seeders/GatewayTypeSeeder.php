<?php

namespace Database\Seeders;

use App\Models\GatewayType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GatewayTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateway_types = array(
            // array('name' => 'Nexmo', 'inputs' => '{"names":["api_key","api_secret","sender_id"],"labels":["Api Key","Api Secret","Sender Id"]}', 'namespace' => 'App\\SmsGateways\\Msg91Gateway', 'driver' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
            // array('name' => 'CLICKA TELL', 'inputs' => '{"names":["sender_id","clickatell_api_key"],"labels":["Sender Id","Api Key"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
            // array('name' => 'SMS Broadcast', 'inputs' => '{"names":["sms_broadcast_username","sms_broadcast_password"],"labels":["Username","Password"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
            // array('name' => 'Text Magic', 'inputs' => '{"names":["api_key","sender_id","text_magic_username"],"labels":["Api Key","Sender Id","Username"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
            // array('name' => 'Message Bird', 'inputs' => '{"names":["sender_id","access_key"],"labels":["Sender Id","Access Key"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
            // array('name' => 'Infobip', 'inputs' => '{"names":["sender_id","infobip_api_key","infobip_base_url"],"labels":["Sender Id","Api Key","Base URL"]}', 'namespace' => NULL,'driver' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'AWS SNS', 'inputs' => '{"names":["key","secret","region","from","type"],"labels":["Api Key","Secret Key","Region","Sender ID","Type"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'sns', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Textlocal', 'inputs' => '{"names":["url","username","hash","from"],"labels":["Api URL","User Name","Hash","Sender Name"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'textlocal', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Twilio', 'inputs' => '{"names":["sid","token","from"],"labels":["Api SID","Api Token","Default Number"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'twilio', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Clockwork', 'inputs' => '{"names":["key"],"labels":["Api Key"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'clockwork', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'LINK Mobility', 'inputs' => '{"names":["url","username","password","from"],"labels":["Api URL","User Name","Password","Sender name"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'linkmobility', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Melipayamak', 'inputs' => '{"names":["username","password","from","flash"],"labels":["User Name","Password","Default Number","Flash"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'melipayamak', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Melipayamakpattern', 'inputs' => '{"names":["username","password"],"labels":["User Name","Password"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'melipayamakpattern', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Kavenegar', 'inputs' => '{"names":["apiKey","from"],"labels":["Api Key","Default Number"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'kavenegar', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Smsir', 'inputs' => '{"names":["url","apiKey","secretKey","from"],"labels":["Api URL","Api Key","Secret Key","Default Number"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'smsir', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Tsms', 'inputs' => '{"names":["url","username","password","from"],"labels":["Api URL","User Name","Password","Default Number"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'tsms', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Farazsms', 'inputs' => '{"names":["url","username","password","from"],"labels":["Api URL","User Name","Password","Default Number"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'farazsms', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Farazsmspattern', 'inputs' => '{"names":["url","username","password","from"],"labels":["Api URL","User Name","Password","Default Number"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'farazsmspattern', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'SMS Gateway Me', 'inputs' => '{"names":["apiToken","from"],"labels":["Api Token","Default Device ID"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'smsgatewayme', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'SmsGateWay24', 'inputs' => '{"names":["url","token","deviceid","from"],"labels":["Api URL","Api Token","Device ID","Device SIM Slot"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'smsgateway24', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Ghasedak', 'inputs' => '{"names":["url","apiKey","from"],"labels":["Api URL","Api Key","Default Number"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'ghasedak', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Sms77', 'inputs' => '{"names":["apiKey","flash","from"],"labels":["Api Key","Flash","Sender name"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'sms77', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'SabaPayamak', 'inputs' => '{"names":["url","username","password","from","token_valid_day"],"labels":["Api URL","User Name","Password","Default Number","Token Validity day"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'sabapayamak', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'LSim', 'inputs' => '{"names":["username","password","from"],"labels":["User Name","Password","Sender ID"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'lsim', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Rahyabcp', 'inputs' => '{"names":["url","username","password","from","flash"],"labels":["Api URL","User Name","Password","Default Number","Flash"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'rahyabcp', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Rahyabir', 'inputs' => '{"names":["url","username","password","company","from","token_valid_day"],"labels":["Api URL","User Name","Password","Company Name","Default Number","Token Validity Day"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'rahyabir', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'D7networks', 'inputs' => '{"names":["url","username","password","originator","report_url","token_valid_day"],"labels":["Api URL","User Name","Password","Originator","Report Url","Token Validity Day"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'd7networks', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Hamyarsms', 'inputs' => '{"names":["url","username","password","from","flash"],"labels":["Api URL","User Name","Password","Default Number","Flash"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'hamyarsms', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'SMSApi', 'inputs' => '{"names":["url","username","password","from","cc"],"labels":["Api URL","User Name","Password","Default Number","Country Code"]}', 'namespace' => 'App\\SmsGateways\\TzskSms', 'driver' => 'smsapi', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'Route Mobile', 'inputs' => '{"names":["api_url","api_method","username","password","type","dlr","number_key","destination","source","message_key"],"labels":["Api URL","Api Method","User Name","Password","Type","DLR","Number Key","Destination","Source","Message Key"]}', 'namespace' => 'App\\SmsGateways\\RouteMobile', 'driver' => 'smsapi', 'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('name' => 'SYSSMS', 'inputs' => '{"names":["api_url","apikey","secretkey"],"labels":["API URL (http://IP:PORT)","API Key","Secret Key"]}', 'namespace' => 'App\\SmsGateways\\SysSms', 'driver' => 'syssms', 'status' => 1,'created_at' => now(),'updated_at' => now()),
        );

        GatewayType::insert($gateway_types);
    }
}
