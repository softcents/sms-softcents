<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = array(
            array('title' => 'Non - Masking','price' => 10,'masking_rate' => 0.5,'non_masking_rate' => 0.25,'total_sms' => 200,'duration' => 'weekly','features' => '{"features_features_features_0":["MNP Charge","1"],"features_features_features_1":["Sender ID","1"],"features_features_features_2":["Inbox Facility","1"],"features_features_features_3":["HTTP API Access","1"],"features_features_features_4":["Auto Backup Gateway Routing"],"features_features_features_5":["Can Send OTP SMS","1"],"features_features_features_6":["Fast Delivery Speed"],"features_features_features_7":["Delivery Report","1"],"features_features_features_8":["Validity :1y to 5Years"],"features_features_features_9":["File to SMS"],"features_features_features_10":["Dynamic SMS"],"features_features_features_11":["Group SMS","1"],"features_features_features_12":["Online Payment (Bkash Auto)","1"]}','status' => '1','created_at' => '2023-12-18 13:00:28','updated_at' => '2023-12-18 13:01:42'),
            array('title' => 'Masking','price' => 20,'masking_rate' => 0.5,'non_masking_rate' => 0.25,'total_sms' => 200,'duration' => '15_days','features' => '{"features_features_0":["MNP Charge","1"],"features_features_1":["Sender ID","1"],"features_features_2":["Inbox Facility","1"],"features_features_3":["HTTP API Access","1"],"features_features_4":["Auto Backup Gateway Routing"],"features_features_5":["Can Send OTP SMS","1"],"features_features_6":["Fast Delivery Speed","1"],"features_features_7":["Delivery Report","1"],"features_features_8":["Validity :1y to 5Years","1"],"features_features_9":["File to SMS"],"features_features_10":["Dynamic SMS","1"],"features_features_11":["Group SMS","1"],"features_features_12":["Online Payment (Bkash Auto)","1"]}','status' => '1','created_at' => '2023-12-18 13:00:28','updated_at' => '2023-12-18 13:05:27'),
            array('title' => 'Masking & Non-masking','price' => 200,'masking_rate' => 0.5,'non_masking_rate' => 0.25,'total_sms' => 2000,'duration' => '15_days','features' => '{"features_0":["MNP Charge","1"],"features_1":["Sender ID","1"],"features_2":["Inbox Facility","1"],"features_3":["HTTP API Access","1"],"features_4":["Auto Backup Gateway Routing","1"],"features_5":["Can Send OTP SMS","1"],"features_6":["Fast Delivery Speed","1"],"features_7":["Delivery Report","1"],"features_8":["Validity :1y to 5Years","1"],"features_9":["File to SMS","1"],"features_10":["Dynamic SMS","1"],"features_11":["Group SMS","1"],"features_12":["Online Payment (Bkash Auto)","1"]}','status' => '1','created_at' => '2023-12-18 13:00:28','updated_at' => '2023-12-18 13:00:28')
        );

        Plan::insert($plans);
    }
}
