<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Newsletter;
use App\Models\Plan;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        $page_data = get_option('manage-pages');
        $services = Service::whereStatus(1)->latest()->get();
        $plans = Plan::whereStatus(1)->latest()->get();
        $testimonials = Testimonial::latest()->get();
        $faqs = Faq::latest()->get();
        $countries = base_path('lang/countrylist.json');
        $countries = json_decode(file_get_contents($countries), true);

        return view('web.index',compact('page_data', 'plans', 'testimonials', 'faqs', 'services', 'countries'));
    }

    public function newsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters'
        ]);

        Newsletter::create($request->all());

        return response()->json('You have been subscribed newsletter successfully.', 200);

    }
}
