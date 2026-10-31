<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Recharge;
use Illuminate\Http\Request;

class RechargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $recharges = Recharge::with('gateway')->where('user_id', auth()->id())
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('invoice_no', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('status', 'like', '%' . $request->search . '%')
                        ->orWhere('notes', 'like', '%' . $request->search . '%');
                });
            })
            ->orWhereHas('gateway', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest();

        if ($request->ajax()) {
            $recharges = $recharges->get();
            return response()->json([
                'data' => view('users.recharges.datas', compact('recharges'))->render()
            ]);
        }

        $recharges = $recharges->paginate(10);
        return view('users.recharges.index', compact('recharges'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        session()->put('amount', $request->amount);

        return response()->json([
            'message' => __('Please select a payment method.'),
            'redirect' => route('recharges-gateways.index')
        ]);
    }

    public function show(string $id)
    {
        $recharge = Recharge::findOrFail($id);
        abort_if($recharge->user_id != auth()->id(), 404);
        return view('users.recharges.show', compact('recharge'));
    }
}
