<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Service;

class AcnooServiceController extends Controller
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
        $countries = base_path('lang/countrylist.json');
        $countries = json_decode(file_get_contents($countries), true);

        return view('web.services.show', compact('service', 'plans', 'page_data', 'countries'));
    }
}
