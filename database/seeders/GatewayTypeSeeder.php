<?php

namespace Database\Seeders;

use App\Models\GatewayType;
use Illuminate\Database\Seeder;

class GatewayTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateway_types = array(
            array(
                'name' => 'PANEL SMS',
                'inputs' => '{"names":["apiurl","key","clientid"],"labels":["API Url","Api Key","Client ID"]}',
                'namespace' => 'App\\SmsGateways\\PanelSms',
                'driver' => 'panelsms',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ),
            array(
                'name' => 'SYSSMS',
                'inputs' => '{"names":["api_url","apikey","secretkey"],"labels":["API URL (http://IP:PORT)","API Key","Secret Key"]}',
                'namespace' => 'App\\SmsGateways\\SysSms',
                'driver' => 'syssms',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ),
            array(
                'name' => 'White Info BD',
                'inputs' => '{"names":["api_url","api_key"],"labels":["API URL","API Key"]}',
                'namespace' => 'App\\SmsGateways\\WhiteInfoBDSms',
                'driver' => 'whiteinfobd',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ),
            array(
                'name' => 'Anbernet SMS',
                'inputs' => '{"names":["api_url","account","password","token","api_key","transtype"],"labels":["API URL (e.g. https://wapi.anbernet.com:9956)","Account / Username (e.g. softcents)","Portal Password (Required for Live Balance check)","Portal Token (Required for Live Balance check)","API Key (Used for Sending SMS)","Transaction Type (T=Transactional, P=Promotional)"]}',
                'namespace' => 'App\\SmsGateways\\AnbernetSms',
                'driver' => 'anbernet',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ),
        );

        GatewayType::insert($gateway_types);
    }
}
