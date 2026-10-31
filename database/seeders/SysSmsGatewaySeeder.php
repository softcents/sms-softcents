<?php

namespace Database\Seeders;

use App\Models\GatewayType;
use App\Models\Smsgateway;
use Illuminate\Database\Seeder;

/**
 * Idempotent seeder that installs the SYSSMS HTTP API gateway type and a
 * placeholder gateway instance on existing installations.
 *
 * Run once with:
 *   php artisan db:seed --class=SysSmsGatewaySeeder
 *
 * Re-running is safe – existing rows are not duplicated.
 */
class SysSmsGatewaySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Register the gateway type (template used by the admin UI).
        $type = GatewayType::updateOrCreate(
            ['name' => 'SYSSMS'],
            [
                'inputs'    => json_encode([
                    'names'  => ['api_url', 'apikey', 'secretkey'],
                    'labels' => ['API URL (http://IP:PORT)', 'API Key', 'Secret Key'],
                ]),
                'namespace' => 'App\\SmsGateways\\SysSms',
                'driver'    => 'syssms',
                'status'    => 1,
            ]
        );

        // 2. Create a disabled placeholder gateway instance that admins can
        //    fill in and activate through the admin panel.
        Smsgateway::firstOrCreate(
            ['name' => 'SYSSMS'],
            [
                'type_id'   => $type->id,
                'priority'  => 1,
                'status'    => 0,
                'driver'    => 'syssms',
                'namespace' => 'App\\SmsGateways\\SysSms',
                'params'    => [
                    'api_url'   => 'http://IP:PORT',
                    'apikey'    => '####',
                    'secretkey' => '####',
                ],
            ]
        );

        $this->command->info('SYSSMS gateway type and instance seeded successfully.');
    }
}
