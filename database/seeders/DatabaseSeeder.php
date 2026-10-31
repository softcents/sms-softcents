<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FaqSeeder::class,
            TestimonialSeeder::class,
            PermissionSeeder::class,
            UserTableSeeder::class,
            OptionTableSeeder::class,
            CurrencySeeder::class,
            // OthersCurrenciesSeeder::class,
            SenderIdSeeder::class,
            PlanTableSeeder::class,
            GatewayTypeSeeder::class,
            // TemplateSeeder::class,
            // GroupSeeder::class,
            // ContactSeeder::class,
            // CampaignsSeeder::class,
            AcnooSmsGatewaySeeder::class,
            ServiceSeeder::class,
            GatewaySeeder::class,
            PipraPaySeeder::class,
            // TapPaymentSeeder::class,
            BlogSeeder::class,
        ]);
    }
}
