<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = array(
            array('id' => '1','satisfy_about' => 'Customer Support','text' => 'last 5 years I buy various software, mobile apps from codecanyon. So far more than 30 software, mobile apps have been purchased. I am impressed with the instant support Acnoo has given me and the efficiency...','star' => '5','client_name' => 'rummansaki99','client_image' => 'uploads/24/01/1706155955-312.png','created_at' => '2024-01-24 17:56:08','updated_at' => '2024-01-25 12:00:51'),
            array('id' => '2','satisfy_about' => 'Customer Support','text' => 'Guys, I purchased this like a few days ago... and let me tell you... the code is clean and the customer support is awesome top notch... like all my concerns were addressed over whatsapp and I\'m good to go! Big ups to the authors','star' => '5','client_name' => 'Ebrahim_envato','client_image' => 'uploads/24/01/1706157585-590.png','created_at' => '2024-01-24 18:04:36','updated_at' => '2024-01-25 17:48:29'),
            array('id' => '3','satisfy_about' => 'Customer Support','text' => 'Acnoo gives a great support. I am a newbie on Flutter and Firebase, I couldn\'t set up the app by myself. Acnoo did everything to me and I got a working app. Trust them, they are  a great team. Good luck for Acnoo.','star' => '5','client_name' => 'bestomall','client_image' => 'uploads/24/01/1706157923-156.png','created_at' => '2024-01-25 10:45:23','updated_at' => '2024-01-25 11:59:16')
        );
        Testimonial::insert($testimonials);
    }
}
