<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = array(
            array('title' => 'NON Masking SMS','image' => 'uploads/24/05/1716728162-403.png','status' => '1','description' => 'Send and receive SMS from a specially- selected number or even port an existing one.','created_at' => now(),'updated_at' => now()),
            array('title' => 'Masking SMS','image' => 'uploads/24/05/1716728176-643.png','status' => '1','description' => 'Send and receive SMS from a specially- selected number or even port an existing one.','created_at' => now(),'updated_at' => now()),
            array('title' => 'Api Service','image' => 'uploads/24/05/1716728189-937.png','status' => '1','description' => 'Send and receive SMS from a specially- selected number or even port an existing one.','created_at' => now(),'updated_at' => now())
        );

        Service::insert($services);
    }
}
