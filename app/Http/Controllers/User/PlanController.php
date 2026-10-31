<?php

namespace App\Http\Controllers\User;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::whereStatus(1)->latest()->paginate(10);
        return view('users.plans.index', compact('plans'));
    }
}
