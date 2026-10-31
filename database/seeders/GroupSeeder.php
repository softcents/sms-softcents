<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = array(
            array('user_id' => 3,'name' => 'Membership Customers','status' => '1','created_at' => '2023-12-04 11:56:40','updated_at' => '2023-12-04 11:56:40'),
            array('user_id' => 3,'name' => 'New Customers','status' => '1','created_at' => '2023-12-04 12:22:56','updated_at' => '2023-12-04 12:22:56')
        );

        Group::insert($groups);
    }
}
