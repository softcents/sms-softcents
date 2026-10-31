<?php

namespace Database\Seeders;

use App\Models\Smsgateway;
use Illuminate\Database\Seeder;

class AcnooSmsGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $smsgateways = array(
            array('name' => 'Twilio', 'type_id' => '3', 'priority' => '1', 'status' => '0', 'driver' => 'twilio', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"sid":"####","token":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'AWS SNS', 'type_id' => '1', 'priority' => '1', 'status' => '0', 'driver' => null, 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"key":"####","secret":"####","region":"####","from":"####","type":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Textlocal', 'type_id' => '2', 'priority' => '1', 'status' => '0', 'driver' => null, 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","hash":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Clockwork', 'type_id' => '4', 'priority' => '1', 'status' => '0', 'driver' => 'clockwork', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"key":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'LINK Mobility', 'type_id' => '5', 'priority' => '2', 'status' => '0', 'driver' => 'linkmobility', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Melipayamak', 'type_id' => '6', 'priority' => '3', 'status' => '0', 'driver' => 'melipayamak', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"username":"####","password":"####","from":"####","flash":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Melipayamakpattern', 'type_id' => '7', 'priority' => '4', 'status' => '0', 'driver' => 'melipayamakpattern', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"username":"####","password":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Kavenegar', 'type_id' => '8', 'priority' => '2', 'status' => '0', 'driver' => 'kavenegar', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"apiKey":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Smsir', 'type_id' => '9', 'priority' => '2', 'status' => '0', 'driver' => 'smsir', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","apiKey":"####","secretKey":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Tsms', 'type_id' => '10', 'priority' => '2', 'status' => '0', 'driver' => 'tsms', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Farazsms', 'type_id' => '11', 'priority' => '12', 'status' => '0', 'driver' => 'farazsms', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Farazsmspattern', 'type_id' => '12', 'priority' => '5', 'status' => '0', 'driver' => 'farazsmspattern', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'SMS Gateway Me', 'type_id' => '13', 'priority' => '49', 'status' => '0', 'driver' => 'smsgatewayme', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"apiToken":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'SmsGateWay24', 'type_id' => '14', 'priority' => '85', 'status' => '0', 'driver' => 'smsgateway24', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","token":"####","deviceid":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Ghasedak', 'type_id' => '15', 'priority' => '5', 'status' => '0', 'driver' => 'ghasedak', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","apiKey":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Sms77', 'type_id' => '16', 'priority' => '85', 'status' => '0', 'driver' => 'sms77', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"apiKey":"####","flash":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'SabaPayamak', 'type_id' => '17', 'priority' => '8', 'status' => '0', 'driver' => 'sabapayamak', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","from":"####","token_valid_day":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'LSim', 'type_id' => '18', 'priority' => '48', 'status' => '0', 'driver' => 'lsim', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"username":"####","password":"####","from":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Rahyabcp', 'type_id' => '19', 'priority' => '8', 'status' => '0', 'driver' => 'rahyabcp', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","from":"####","flash":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Rahyabir', 'type_id' => '20', 'priority' => '59', 'status' => '0', 'driver' => 'rahyabir', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","company":"####","from":"####","token_valid_day":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'D7networks', 'type_id' => '21', 'priority' => '48', 'status' => '0', 'driver' => 'd7networks', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","originator":"####","report_url":"####","token_valid_day":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'SMSApi', 'type_id' => '23', 'priority' => '456', 'status' => '0', 'driver' => 'smsapi', 'namespace' => 'App\\SmsGateways\\TzskSms', 'params' => '{"url":"####","username":"####","password":"####","from":"####","cc":"####"}', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Route Mobile', 'type_id' => '24', 'priority' => '1', 'status' => '1', 'driver' => 'smsapi', 'namespace' => 'App\\SmsGateways\\RouteMobile', 'params' => '{"api_url":"http:\\/\\/api.rmlconnect.net\\/bulksms\\/bulksms","api_method":"GET","username":"","password":"","type":"0","dlr":"1","number_key":"destination","source":"","message_key":"message"}', 'created_at' => now()->addDay(), 'updated_at' => now()->addDay()),
            array('name' => 'SYSSMS', 'type_id' => '25', 'priority' => '1', 'status' => '0', 'driver' => 'syssms', 'namespace' => 'App\\SmsGateways\\SysSms', 'params' => '{"api_url":"http:\\/\\/IP:PORT","apikey":"####","secretkey":"####"}', 'created_at' => now(), 'updated_at' => now()),
        );

        Smsgateway::insert($smsgateways);
    }
}
