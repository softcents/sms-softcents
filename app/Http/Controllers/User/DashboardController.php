<?php

namespace App\Http\Controllers\User;

use App\Models\Sms;
use App\Models\Group;
use App\Models\Contact;
use App\Models\Recharge;
use App\Models\Template;
use App\Models\PlanSubscribe;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Sms::with('senderid:id,sender')->where('user_id', auth()->id())->latest()->limit(5)->get();
        return view("users.dashboard.index", compact('messages'));
    }

    public function getDashboardData()
    {
        $data['subscription_subscribe'] = PlanSubscribe::whereStatus('approved')->where('user_id', auth()->id())->count();
        $data['recharge_amount'] = currency_format(Recharge::where('user_id', auth()->id())->whereStatus('approved')->sum('amount'));
        $data['total_sms'] = Sms::where('user_id', auth()->id())->count();
        $data['pending_sms'] = Sms::where('user_id', auth()->id())->where('status', 'pending')->count();
        $data['success_sms'] = Sms::where('user_id', auth()->id())->where('status', 'success')->count();
        $data['canceled_sms'] = Sms::where('user_id', auth()->id())->where('status', 'canceled')->count();
        $data['groups'] = Group::where('user_id', auth()->id())->count();
        $data['contacts'] = Contact::where('user_id', auth()->id())->count();
        $data['templates'] = Template::where('user_id', auth()->id())->count();

        return response()->json($data);
    }

    public function yearlyStatistics()
    {
        $data['sms'] = Sms::where('user_id', auth()->id())->whereYear('created_at', request('year') ?? date('Y'))
                            ->selectRaw('MONTHNAME(created_at) as month, COUNT(*) as total_items')
                            ->groupBy('month')
                            ->get();

        return response()->json($data);
    }

    public function smsOverview()
    {
        $data['success_sms'] = Sms::where('user_id', auth()->id())->where('status', 'success')->whereYear('created_at', request('year') ?? date('Y'))->count();
        $data['canceled_sms'] = Sms::where('user_id', auth()->id())->where('status', 'canceled')->whereYear('created_at', request('year') ?? date('Y'))->count();

        return response()->json($data);
    }
}
