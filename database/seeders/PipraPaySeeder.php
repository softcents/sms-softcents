<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Gateway;
use Illuminate\Database\Seeder;

class PipraPaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencyId = Currency::where('code', 'BDT')->value('id') ?? Currency::value('id');

        Gateway::updateOrCreate(
            ['namespace' => 'App\\Library\\PipraPay'],
            [
                'name' => 'Pipra Pay',
                'currency_id' => $currencyId,
                'mode' => 'Sandbox',
                'status' => '1',
                'charge' => 0,
                'image' => null,
                'data' => [
                    'api_key' => 'your-pipra-pay-api-key',
                    'base_url' => 'https://sandbox.piprapay.com',
                ],
                'manual_data' => null,
                'is_manual' => '0',
                'accept_img' => '0',
                'phone_required' => 1,
                'instructions' => null,
            ]
        );
    }
}
