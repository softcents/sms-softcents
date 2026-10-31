<?php

namespace Database\Seeders;

use App\Models\Gateway;
use Illuminate\Database\Seeder;

class TapPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = array(
            array('name' => 'Tap Payment','currency_id' => 116,'mode' => 'Sandbox','status' => '1','charge' => 2,'image' => NULL,'data' => '{"secret_key":"sk_test_PLACEHOLDER_KEY","currency":"SAR"}','manual_data' => NULL,'is_manual' => '0','accept_img' => '0','namespace' => 'App\\Library\\TapPayment', 'phone_required' => 0, 'instructions' => NULL, 'created_at' => '2024-02-18 17:45:52','updated_at' => '2024-02-18 17:54:44'),
        );

        Gateway::insert($gateways);
    }
}
