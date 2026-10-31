<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = array(
            array('user_id' => '1','title' => 'Best HR & Payroll Management Software in World (2024)','slug' => 'best-hr-payroll-management-software-in-world-2024','image' => 'uploads/24/05/1716728353-59.png','status' => '1','descriptions' => 'Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary','tags' => '["Consequuntur est eos"]','meta' => '{"title":"Debitis accusamus fu","description":"Magna saepe dolorum"}','created_at' => '2024-03-05 19:01:52','updated_at' => '2024-05-27 00:59:13'),
            array('user_id' => '1','title' => 'Best HR & Payroll Software for Medium & Large Businesses','slug' => 'best-hr-payroll-software-for-medium-large-businesses','image' => 'uploads/24/05/1716728333-145.jfif','status' => '1','descriptions' => 'Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary','tags' => '["Pariatur Neque cons"]','meta' => '{"title":"Esse accusamus quia","description":"Culpa ea omnis sed q"}','created_at' => '2024-03-05 19:02:27','updated_at' => '2024-05-27 00:58:53'),
            array('user_id' => '1','title' => 'Best HR & Payroll Management Software in World (2022)','slug' => 'best-hr-payroll-management-software-in-world-2022','image' => 'uploads/24/05/1716728317-530.jfif','status' => '1','descriptions' => 'Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary','tags' => '["hrm"]','meta' => '{"title":"Best HR & Payroll Management Software in World (2024)","description":"Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary"}','created_at' => '2024-03-10 00:10:33','updated_at' => '2024-05-27 00:58:37'),
            array('user_id' => '1','title' => 'Best HR & Payroll Software for Medium & Large Businesses (2023)','slug' => 'best-hr-payroll-software-for-medium-large-businesses-2023','image' => 'uploads/24/05/1716728298-489.jpg','status' => '1','descriptions' => 'Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary','tags' => '["hrm"]','meta' => '{"title":"Best HR & Payroll Software for Medium & Large Businesses (2023)","description":"Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary"}','created_at' => '2024-03-10 00:13:14','updated_at' => '2024-05-27 00:58:18'),
            array('user_id' => '1','title' => 'Best HR & Payroll Management Software in World (2021)','slug' => 'best-hr-payroll-management-software-in-world-2021','image' => 'uploads/24/05/1716728286-230.jpg','status' => '1','descriptions' => 'Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary','tags' => '["hrm"]','meta' => '{"title":"Best HR & Payroll Management Software in World (2021)","description":"Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary"}','created_at' => '2024-03-10 00:15:04','updated_at' => '2024-05-27 00:58:06'),
            array('user_id' => '1','title' => 'Best HR & Payroll Software for Medium & Large Businesses(2020)','slug' => 'best-hr-payroll-software-for-medium-large-businesses2020','image' => 'uploads/24/05/1716728270-89.jpg','status' => '1','descriptions' => 'Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary','tags' => '["hrm"]','meta' => '{"title":"Best HR & Payroll Software for Medium & Large Businesses","description":"Blessing welcomed ladyship she met humo ured sir breeding her. Six curiosity day assurance bed necessary"}','created_at' => '2024-03-10 00:17:11','updated_at' => '2024-05-27 00:57:50')
        );

        Blog::insert($blogs);
    }
}
