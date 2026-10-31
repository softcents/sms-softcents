<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = array(
            array('id' => '1','question' => 'What is an SMS API? What is an SMS gateway API?','answer' => 'An SMS API (or SMS Gateway API) is a software interface                       that allows developers to build code that can send and                       receive SMS messages using an Application Programming  Interface (API).','status' => '0','created_at' => '2024-01-25 10:49:52','updated_at' => '2024-01-25 10:54:28'),
            array('id' => '2','question' => 'Can I send SMS messages to any numbers?','answer' => 'An SMS API (or SMS Gateway API) is a software interface that allows developers to build code that can send and                       receive SMS messages using an Application Programming  Interface (API).','status' => '1','created_at' => '2024-01-25 10:50:22','updated_at' => '2024-01-25 12:19:50'),
            array('id' => '3','question' => 'Is there a limit to the number of SMS I can send   and receive?','answer' => 'An SMS API (or SMS Gateway API) is a software interface that allows developers to build code that can send and                       receive SMS messages using an Application Programming  Interface (API).','status' => '0','created_at' => '2024-01-25 10:50:51','updated_at' => '2024-01-25 12:26:34'),
            array('id' => '4','question' => 'How secure is SMS Gateways feature?','answer' => 'An SMS API (or SMS Gateway API) is a software interface that allows developers to build code that can send and                       receive SMS messages using an Application Programming  Interface (API).','status' => '0','created_at' => '2024-01-25 10:51:27','updated_at' => '2024-01-25 12:20:12'),
            array('id' => '5','question' => 'Is there a limit to the number of SMS I can send  and receive?','answer' => 'An SMS API (or SMS Gateway API) is a software interface that allows developers to build code that can send and                       receive SMS messages using an Application Programming  Interface (API).','status' => '1','created_at' => '2024-01-25 10:52:01','updated_at' => '2024-01-25 12:20:22')
          );
          Faq::insert($faqs);
    }
}
