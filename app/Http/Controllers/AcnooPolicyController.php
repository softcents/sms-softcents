<?php

namespace App\Http\Controllers;

class AcnooPolicyController extends Controller
{
    public function privacy()
    {
        $page_data = get_option('manage-pages');
        return view('web.policies.privacy',compact('page_data'));
    }

    public function refund()
    {
        $page_data = get_option('manage-pages');
        return view('web.policies.refund',compact('page_data'));
    }
}
