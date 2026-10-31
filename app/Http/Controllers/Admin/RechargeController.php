<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Recharge;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Gateway;

class RechargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $recharges = Recharge::with('user')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('invoice_no', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('status', 'like', '%' . $request->search . '%');
                });
            })
            ->orWhereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest();

        if ($request->ajax()) {
            $recharges = $recharges->get();
            return response()->json([
                'data' => view('admin.recharges.datas', compact('recharges'))->render()
            ]);
        }

        $recharges = $recharges->paginate(10);
        return view('admin.recharges.index', compact('recharges'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string|max:200',
            'transaction_id' => 'nullable|string|max:200',
            'status' => 'required|string|in:approved,pending',
        ]);

        $user = User::findOrFail($request->user_id);

        DB::beginTransaction();
        try {

            $recharge = Recharge::create($request->all() + [
                'user_id' => auth()->id()
            ]);

            if (request('status') == 'approved') {
                $user->update([
                    'balance' => $user->balance + $recharge->amount,
                ]);
            }

            sendNotification($recharge->id, route('admin.recharges.index'), __('New balance added.'));

            DB::commit();
            return response()->json([
                'message' => __('Balance added successfully for ' . $user->name),
                'redirect' => route('admin.recharges.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('admin.recharges.index'))->with('error', __('Something went wrong!'));
        }
    }

    public function show(string $id)
    {
        $recharge = Recharge::with('user:id,name', 'gateway:id,name,manual_data,is_manual')->findOrFail($id);
        return view('admin.recharges.show', compact('recharge'));
    }

    public function print(string $id)
    {
        $recharge = Recharge::with('user:id,name', 'gateway:id,name')->findOrFail($id);
        return view('admin.recharges.print', compact('recharge'));
    }

    public function statusUpdate(Request $request, string $id)
    {
        if ($request->status == 'canceled') {
            $request->validate([
                'notes' => 'required|max:1000'
            ]);
        }

        $recharge = Recharge::findOrFail($id);
        $gateway = Gateway::findOrFail($recharge->gateway_id);

        if (in_array(request('status'), ['approved', 'canceled'])) {

            DB::beginTransaction();
            try {

                if (request('status') == 'approved') {
                    $user = User::findOrFail($recharge->user_id);

                    Transaction::create([
                        'type' => 'credit',
                        'user_id' => $user->id,
                        'gateway_id' => $gateway->id,
                        'charge' => $gateway->charge,
                        'reason' => "Balance Recharged",
                        'amount' => $recharge->amount,
                    ]);

                    $user->update([
                        'balance' => $user->balance + $recharge->amount,
                    ]);
                }

                $recharge->update([
                    'notes' => $request->notes,
                    'status' => $request->status,
                ]);

                DB::commit();
                return response()->json([
                    'message' => __('Recharge has been ' . request('status')),
                    'redirect' => route('admin.recharges.index')
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'message' => __('Something went wrong!'),
                    'redirect' => route('admin.recharges.index')
                ], 406);
            }
        } else {
            return response()->json([
                'message' => __("You're a fraud!"),
                'redirect' => route('admin.recharges.index')
            ], 406);
        }
    }
}
