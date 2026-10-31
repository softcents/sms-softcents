<?php

use App\Models\GatewayType;
use App\Models\Smsgateway;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        GatewayType::where('name', 'SYSSMS')->update([
            'inputs' => [
                'names' => ['api_url', 'apikey', 'secretkey'],
                'labels' => ['API URL (http://IP:PORT)', 'API Key', 'Secret Key'],
            ],
        ]);

        Smsgateway::where('driver', 'syssms')
            ->orWhere('namespace', 'App\\SmsGateways\\SysSms')
            ->get()
            ->each(function (Smsgateway $gateway) {
                $params = $gateway->params ?? [];
                unset($params['callerID']);

                $gateway->update(['params' => $params]);
            });
    }

    public function down(): void
    {
        GatewayType::where('name', 'SYSSMS')->update([
            'inputs' => [
                'names' => ['api_url', 'apikey', 'secretkey', 'callerID'],
                'labels' => ['API URL (http://IP:PORT)', 'API Key', 'Secret Key', 'Caller ID (Sender)'],
            ],
        ]);

        Smsgateway::where('driver', 'syssms')
            ->orWhere('namespace', 'App\\SmsGateways\\SysSms')
            ->get()
            ->each(function (Smsgateway $gateway) {
                $params = $gateway->params ?? [];
                $params['callerID'] = $params['callerID'] ?? '####';

                $gateway->update(['params' => $params]);
            });
    }
};
