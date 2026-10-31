<?php

namespace Database\Seeders;

use App\Models\Senderid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SenderIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $senderids = array(
            array('user_id' => NULL,'sender' => '12345','type' => 'Non-Masking','is_default' => 1,'status' => 1,'created_at' => now(),'updated_at' => now()),
            array('user_id' => 3,'sender' => '12345','type' => 'Non-Masking','is_default' => 0,'status' => 1,'created_at' => now(),'updated_at' => now())
        );

        Senderid::insert($senderids);
    }
}
