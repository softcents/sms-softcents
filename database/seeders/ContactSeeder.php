<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = array(
            array('user_id' => 3, 'group_id' => 1, 'number' => '8801877593340', 'name' => 'Md. Safiull Alam','email' => NULL,'status' => '1','created_at' => '2023-12-04 11:56:40','updated_at' => '2023-12-04 11:56:40'),
            array('user_id' => 3, 'group_id' => 1, 'number' => '8801631865339', 'name' => 'Torongo Bhai','email' => NULL,'status' => '1','created_at' => '2023-12-04 11:56:40','updated_at' => '2023-12-04 11:56:40'),
            array('user_id' => 3, 'group_id' => 2, 'number' => '8801712022529', 'name' => 'Shahidul Islam','email' => NULL,'status' => '1','created_at' => '2023-12-04 12:25:19','updated_at' => '2023-12-04 12:25:19')
        );

        Contact::insert($contacts);
    }
}
