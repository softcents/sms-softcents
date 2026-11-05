<?php

namespace Database\Seeders;

use App\Models\Smsgateway;
use Illuminate\Database\Seeder;

class SoftcentsSmsGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $smsgateways = array(
            array(
                'name' => 'PanelSms1',
                'type_id' => 25,
                'priority' => '1',
                'status' => '1',
                'driver' => 'panelsms',
                'namespace' => 'App\\SmsGateways\\PanelSms',
                'params' => '{"apiurl":"####","key":"####","clientid":"####"}',
                'non_masking_rate' => 0.2000,
                'masking_rate' => 0.4500,
                'created_at' => now(),
                'updated_at' => now()
            ),
            array(
                'name' => 'bdw',
                'type_id' => 25,
                'priority' => '1',
                'status' => '1',
                'driver' => 'panelsms',
                'namespace' => 'App\\SmsGateways\\PanelSms',
                'params' => '{"apiurl":"####","key":"####","clientid":"####"}',
                'non_masking_rate' => 0.2000,
                'masking_rate' => 0.4500,
                'created_at' => now(),
                'updated_at' => now()
            ),
            array(
                'name' => 'SYSSMS',
                'type_id' => 26,
                'priority' => '1',
                'status' => '1',
                'driver' => 'syssms',
                'namespace' => 'App\\SmsGateways\\SysSms',
                'params' => '{"api_url":"http:\\/\\/IP:PORT","apikey":"####","secretkey":"####"}',
                'non_masking_rate' => 0.2000,
                'masking_rate' => 0.4500,
                'created_at' => now(),
                'updated_at' => now()
            ),
            array(
                'name' => 'REVE SMS',
                'type_id' => 26,
                'priority' => '1',
                'status' => '1',
                'driver' => 'syssms',
                'namespace' => 'App\\SmsGateways\\SysSms',
                'params' => '{"api_url":"####","apikey":"####","secretkey":"####"}',
                'non_masking_rate' => 0.2000,
                'masking_rate' => 0.4500,
                'created_at' => now(),
                'updated_at' => now()
            ),
            array(
                'name' => 'White Info BD',
                'type_id' => 27,
                'priority' => '1',
                'status' => '1',
                'driver' => 'whiteinfobd',
                'namespace' => 'App\\SmsGateways\\WhiteInfoBDSms',
                'params' => '{"api_url":"####","api_key":"####"}',
                'non_masking_rate' => 0.2250,
                'masking_rate' => 0.4800,
                'created_at' => now(),
                'updated_at' => now()
            ),
        );

        Smsgateway::insert($smsgateways);
    }
}
