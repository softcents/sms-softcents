<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CronJobController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cron-jobs-read')->only('index');
    }

    public function index()
    {
        return view('admin.settings.cron-jobs.index');
    }
}
