<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Service;

class SoftcentsServiceController extends Controller
{
    public function index()
    {
        $page_data = get_option('manage-pages');
        return view('web.termofservice.index',compact('page_data'));
    }
    public function show(string $id)
    {
        $page_data = get_option('manage-pages');
        $service = Service::findOrFail($id);
        $plans = Plan::whereStatus(1)->latest()->get();
        $countries = [['name' => 'Bangladesh', 'code' => 'BD']];

        return view('web.services.show', compact('service', 'plans', 'page_data', 'countries'));
    }
}
