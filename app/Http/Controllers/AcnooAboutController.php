<?php

namespace App\Http\Controllers;

class AcnooAboutController extends Controller
{
    public function index()
    {
        $page_data = get_option('manage-pages');
        return view('web.about.index',compact('page_data'));
    }
}
