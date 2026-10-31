<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use App\Http\Controllers\Controller;
use App\Models\Plan;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subscribers = PlanSubscribe::with('plan:id,title', 'gateway:id,name')->where('user_id', auth()->id())
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('invoice_no', 'like', '%' . $request->search . '%')
                        ->orWhere('price', 'like', '%' . $request->search . '%')
                        ->orWhere('status', 'like', '%' . $request->search . '%')
                        ->orWhere('total_sms', 'like', '%' . $request->search . '%');
                });
            })
            ->orWhereHas('plan', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })
            ->orWhereHas('gateway', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest();

        if ($request->ajax()) {
            $subscribers = $subscribers->get();
            return response()->json([
                'data' => view('users.subscribers.datas', compact('subscribers'))->render()
            ]);
        }

        $subscribers = $subscribers->paginate(10);
        return view('users.subscribers.index', compact('subscribers'));
    }

    public function acnooFilter(Request $request)
    {
        $subscribers = PlanSubscribe::with('plan:id,title')
            ->where('user_id', auth()->id())
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('invoice_no', 'like', '%' . $request->search . '%')
                        ->orWhere('price', 'like', '%' . $request->search . '%')
                        ->orWhere('total_sms', 'like', '%' . $request->search . '%')
                        ->orWhere('transaction_id', 'like', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('plan', function ($q) use ($request) {
                            $q->where('title', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);


        if ($request->ajax()) {
            return response()->json([
                'data' => view('admin.subscribers.datas', compact('subscribers'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function show(string $id)
    {
        $subscribe = PlanSubscribe::findOrFail($id);
        return view('users.subscribers.show', compact('subscribe'));
    }
}
