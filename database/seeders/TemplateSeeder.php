<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = array(
            array('user_id' => 3, 'name' => 'Shera Rate 0.20', 'text' => 'বাংলাদেশের সেরা রেট ২২ পয়সা!','status' => 1,'created_at' => now(),'updated_at' => now())
        );

        Template::insert($templates);
    }
}
