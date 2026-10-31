<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sms;
use App\Models\Recharge;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class ReportController extends Controller
{
    // Quick messages report
    public function quickSms(Request $request)
    {
        $messages = Sms::with('senderid:id,sender', 'user:id,name', 'smsgateway:id,name')
                        ->whereNull(['group_id', 'campaign_id'])
                        ->when($request->has('search'), function ($q) use ($request) {
                            $q->where(function ($query) use ($request) {
                                $query->where('number', 'like', '%' . $request->search . '%')
                                    ->orWhere('charge', 'like', '%' . $request->search . '%')
                                    ->orWhere('ip_address', 'like', '%' . $request->search . '%')
                                    ->orWhere('contents', 'like', '%' . $request->search . '%')
                                    ->orWhere('notes', 'like', '%' . $request->search . '%')
                                    ->orWhere('status', 'like', '%' . $request->search . '%')
                                    ->orWhereHas('user', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%')
                                            ->orWhere('email', 'like', '%' . $request->search . '%');
                                    })
                                    ->orWhereHas('campaign', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%');
                                    })
                                    ->orWhereHas('senderid', function ($q) use ($request) {
                                        $q->where('sender', 'like', '%' . $request->search . '%');
                                    });
                            });
                        })
                        ->latest();

        if ($request->ajax()) {
            $messages = $messages->get();
            return response()->json([
                'data' => view('admin.reports.quick-sms.datas', compact('messages'))->render()
            ]);
        }

        $messages = $messages->paginate(10);
        return view('admin.reports.quick-sms.index', compact('messages'));
    }

    // Group messages report
    public function groupSms(Request $request)
    {

        $groups = Sms::with('senderid:id,sender', 'user:id,name', 'smsgateway:id,name')
                    ->whereNotNull('group_id')
                    ->when($request->has('search'), function ($q) use ($request) {
                        $q->where(function ($query) use ($request) {
                            $query->where('number', 'like', '%' . $request->search . '%')
                                ->orWhere('charge', 'like', '%' . $request->search . '%')
                                ->orWhere('ip_address', 'like', '%' . $request->search . '%')
                                ->orWhere('contents', 'like', '%' . $request->search . '%')
                                ->orWhere('notes', 'like', '%' . $request->search . '%')
                                ->orWhere('status', 'like', '%' . $request->search . '%')
                                ->orWhereHas('user', function ($q) use ($request) {
                                    $q->where('name', 'like', '%' . $request->search . '%')
                                        ->orWhere('email', 'like', '%' . $request->search . '%');
                                })
                                ->orWhereHas('campaign', function ($q) use ($request) {
                                    $q->where('name', 'like', '%' . $request->search . '%');
                                })
                                ->orWhereHas('senderid', function ($q) use ($request) {
                                    $q->where('sender', 'like', '%' . $request->search . '%');
                                });
                        });
                    })
                    ->latest();

        if ($request->ajax()) {
            $groups = $groups->get();
            return response()->json([
                'data' => view('admin.reports.group-sms.datas', compact('groups'))->render()
            ]);
        }

        $groups = $groups->paginate(10);
        return view('admin.reports.group-sms.index', compact('groups'));
    }

    // Campaigns messages report
    public function campaigns(Request $request)
    {
        $campaign_sms = Sms::with('senderid:id,sender', 'user:id,name', 'smsgateway:id,name', 'campaign:id,name')
                        ->whereNotNull('campaign_id')
                        ->when($request->has('search'), function ($q) use ($request) {
                            $q->where(function ($query) use ($request) {
                                $query->where('number', 'like', '%' . $request->search . '%')
                                    ->orWhere('charge', 'like', '%' . $request->search . '%')
                                    ->orWhere('ip_address', 'like', '%' . $request->search . '%')
                                    ->orWhere('contents', 'like', '%' . $request->search . '%')
                                    ->orWhere('notes', 'like', '%' . $request->search . '%')
                                    ->orWhere('status', 'like', '%' . $request->search . '%')
                                    ->orWhereHas('user', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%')
                                            ->orWhere('email', 'like', '%' . $request->search . '%');
                                    })
                                    ->orWhereHas('campaign', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%');
                                    })
                                    ->orWhereHas('senderid', function ($q) use ($request) {
                                        $q->where('sender', 'like', '%' . $request->search . '%');
                                    });
                            });
                        })
                        ->latest();

        if ($request->ajax()) {
            $campaign_sms = $campaign_sms->get();
            return response()->json([
                'data' => view('admin.reports.campaigns-sms.datas', compact('campaign_sms'))->render()
            ]);
        }

        $campaign_sms = $campaign_sms->paginate(10);
        return view('admin.reports.campaigns-sms.index', compact('campaign_sms'));
    }

    // All subscriptions reports
    public function subscriptions(Request $request)
    {
        $subscribers = PlanSubscribe::with('user', 'plan:id,title')
                        ->when($request->has('search'), function ($q) use ($request) {
                            $q->where(function ($query) use ($request) {
                                $query->where('invoice_no', 'like', '%' . $request->search . '%')
                                    ->orWhere('price', 'like', '%' . $request->search . '%')
                                    ->orWhere('total_sms', 'like', '%' . $request->search . '%')
                                    ->orWhereHas('user', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%');
                                    })
                                    ->orWhereHas('plan', function ($q) use ($request) {
                                        $q->where('title', 'like', '%' . $request->search . '%');
                                    });
                            });
                        })
                        ->latest();

        if ($request->ajax()) {
            $subscribers = $subscribers->get();

            return response()->json([
                'data' => view('admin.reports.subscribers.datas', compact('subscribers'))->render()
            ]);
        }

        $subscribers = $subscribers->paginate(10);
        return view('admin.reports.subscribers.index', compact('subscribers'));
    }

    // All Recharge Reports
    public function recharges(Request $request)
    {
        $recharges = Recharge::with('user', 'gateway')
                        ->when($request->has('search'), function ($q) use ($request) {
                            $q->where(function ($query) use ($request) {
                                $query->where('invoice_no', 'like', '%' . $request->search . '%')
                                    ->orWhere('amount', 'like', '%' . $request->search . '%')
                                    ->orWhere('status', 'like', '%' . $request->search . '%')
                                    ->orWhereHas('user', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%');
                                    })
                                    ->orWhereHas('gateway', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%');
                                    });
                            });
                        })
                        ->latest();

        if ($request->ajax()) {
            $recharges = $recharges->get();
            return response()->json([
                'data' => view('admin.reports.recharges.datas', compact('recharges'))->render()
            ]);
        }

        $recharges = $recharges->paginate(10);
        return view('admin.reports.recharges.index', compact('recharges'));
    }

    // Transactions
    public function transactions(Request $request)
    {
        $transactions = Transaction::with('user', 'gateway')
                        ->when($request->has('search'), function ($q) use ($request) {
                            $q->where(function ($query) use ($request) {
                                $query->where('invoice_no', 'like', '%' . $request->search . '%')
                                    ->orWhere('amount', 'like', '%' . $request->search . '%')
                                    ->orWhere('charge', 'like', '%' . $request->search . '%')
                                    ->orWhere('reason', 'like', '%' . $request->search . '%')
                                    ->orWhereHas('user', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%');
                                    });
                            });
                        })
                        ->latest();

        if ($request->ajax()) {
            $transactions = $transactions->get();
            return response()->json([
                'data' => view('admin.reports.transactions.datas', compact('transactions'))->render()
            ]);
        }

        $transactions = $transactions->paginate(10);
        return view('admin.reports.transactions.index', compact('transactions'));
    }
}
