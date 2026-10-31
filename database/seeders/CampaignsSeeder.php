<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = array(
            array('name' => 'ইঞ্জিনিয়ারস','status' => 1,'total_numbers' => 3,'numbers' => '8801712022529,8801877593340,8801631865339','description' => 'ইঞ্জিনিয়ারস','created_at' => now(),'updated_at' => now()),
            array('name' => 'Team Dhorbongsho','status' => 1,'total_numbers' => 2,'numbers' => '8801877593340,8801631865339','description' => 'Team Dhorbongsho','created_at' => now(),'updated_at' => now())
        );

        Campaign::insert($campaigns);
    }
}
