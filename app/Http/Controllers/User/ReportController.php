<?php

namespace App\Http\Controllers\User;

use App\Models\Sms;
use App\Models\Recharge;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    // Quick messages report
    public function quickSms(Request $request)
    {
        $messages = Sms::with('senderid')
            ->where('user_id', auth()->id())
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('number', 'like', '%' . $request->search . '%')
                        ->orWhere('charge', 'like', '%' . $request->search . '%')
                        ->orWhere('ip_address', 'like', '%' . $request->search . '%')
                        ->orWhere('contents', 'like', '%' . $request->search . '%')
                        ->orWhere('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('status', 'like', '%' . $request->search . '%')
                        ->orWhereHas('senderid', function ($q) use ($request) {
                            $q->where('sender', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest();

        if ($request->ajax()) {
            $messages = $messages->get();
            return response()->json([
                'data' => view('users.reports.quick-sms.datas', compact('messages'))->render()
            ]);
        }

        $messages = $messages->paginate(10);
        return view('users.reports.quick-sms.index', compact('messages'));
    }

    // Group messages report
    public function groupSms(Request $request)
    {
        $groups = Sms::where('user_id', auth()->id())->with('senderid:id,sender')->whereNotNull('group_id')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('number', 'like', '%' . $request->search . '%')
                        ->orWhere('charge', 'like', '%' . $request->search . '%')
                        ->orWhere('ip_address', 'like', '%' . $request->search . '%')
                        ->orWhere('contents', 'like', '%' . $request->search . '%')
                        ->orWhere('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('status', 'like', '%' . $request->search . '%')
                        ->orWhereHas('senderid', function ($q) use ($request) {
                            $q->where('sender', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest();

        if ($request->ajax()) {
            $groups = $groups->get();
            return response()->json([
                'data' => view('users.reports.group-sms.datas', compact('groups'))->render()
            ]);
        }

        $groups = $groups->paginate(10);
        return view('users.reports.group-sms.index', compact('groups'));
    }

    // Campaigns messages report
    public function campaigns(Request $request)
    {
        $campaigns = Sms::where('user_id', auth()->id())->with('campaign:id,name', 'senderid:id,sender')->whereNotNull('campaign_id')
                        ->when($request->has('search'), function ($q) use ($request) {
                            $q->where(function ($query) use ($request) {
                                $query->where('total_sms', 'like', '%' . $request->search . '%')
                                    ->orWhere('contents', 'like', '%' . $request->search . '%')
                                    ->orWhere('number', 'like', '%' . $request->search . '%')
                                    ->orWhereHas('campaign', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%')
                                            ->orWhere('total_numbers', 'like', '%' . $request->search . '%');
                                    })
                                    ->orWhereHas('senderid', function ($q) use ($request) {
                                        $q->where('sender', 'like', '%' . $request->search . '%')
                                            ->orWhere('type', 'like', '%' . $request->search . '%');
                                    });
                            });
                        })
                        ->latest();

        if ($request->ajax()) {
            $campaigns = $campaigns->get();
            return response()->json([
                'data' => view('users.reports.campaigns-sms.datas', compact('campaigns'))->render()
            ]);
        }

        $campaigns = $campaigns->paginate(10);
        return view('users.reports.campaigns-sms.index', compact('campaigns'));
    }

    // All subscriptions reports
    public function subscriptions(Request $request)
    {
        $subscribers = PlanSubscribe::with('plan:id,title', 'gateway:id,name')->where('user_id', auth()->id())
                        ->when($request->has('search'), function ($q) use ($request) {
                            $q->where(function ($query) use ($request) {
                                $query->where('invoice_no', 'like', '%' . $request->search . '%')
                                    ->orWhere('price', 'like', '%' . $request->search . '%')
                                    ->orWhere('status', 'like', '%' . $request->search . '%')
                                    ->orWhere('total_sms', 'like', '%' . $request->search . '%')
                                    ->orWhereHas('plan', function ($q) use ($request) {
                                        $q->where('title', 'like', '%' . $request->search . '%');
                                    })
                                    ->orWhereHas('gateway', function ($q) use ($request) {
                                        $q->where('name', 'like', '%' . $request->search . '%');
                                    });
                            });
                        })
                        ->latest();

        if ($request->ajax()) {
            $subscribers = $subscribers->get();
            return response()->json([
                'data' => view('users.reports.subscribers.datas', compact('subscribers'))->render()
            ]);
        }

        $subscribers = $subscribers->paginate(10);
        return view('users.reports.subscribers.index', compact('subscribers'));
    }

    // All Recharge Reports
    public function recharges(Request $request)
    {
        $recharges = Recharge::with('gateway', 'user')->where('user_id', auth()->id())
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('invoice_no', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('status', 'like', '%' . $request->search . '%')
                        ->orWhere('notes', 'like', '%' . $request->search . '%')
                        ->orWhereHas('gateway', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest();

        if ($request->ajax()) {
            $recharges = $recharges->get();
            return response()->json([
                'data' => view('users.reports.recharges.datas', compact('recharges'))->render()
            ]);
        }

        $recharges = $recharges->paginate(10);
        return view('users.reports.recharges.index', compact('recharges'));
    }

    // Transactions
    public function transactions(Request $request)
    {
        $transactions = Transaction::with('user', 'gateway')
                        ->where('user_id', auth()->id())
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
                'data' => view('users.reports.transactions.datas', compact('transactions'))->render()
            ]);
        }

        $transactions = $transactions->paginate(10);
        return view('users.reports.transactions.index', compact('transactions'));
    }
}
