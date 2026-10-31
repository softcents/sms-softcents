<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
    {

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            array('name' => 'Customer', 'role' => 'user', 'email' => 'customer@customer.com', 'balance' => 10, 'plan_id' => NULL, 'will_expire' => now()->addDays(7), 'phone' => '05169541651', 'image' => NULL, 'status' => 1, 'non_masking_rate' => .25, 'masking_rate' => .50, 'client_id' => 000001, 'client_secret' => '41274e60-a864-46e9-9ef6-22d48e129610', 'password' => bcrypt('customer'), 'email_verified_at' => NULL, 'remember_token' => NULL, 'created_at' => now(), 'updated_at' => now())
        );

        User::insert($users);
    }
}
