<?php

namespace App\Http\Controllers\SSP;

use App\Helpers\HasUploader;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Recharge;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechargeController extends Controller
{
    use HasUploader;

    /**
     * List all recharge requests received from downline customers.
     */
    public function index(Request $request)
    {
        $sspId = auth()->id();
        $recharges = Recharge::with(['user', 'gateway'])
            ->where('reseller_id', $sspId)
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('invoice_no', 'like', "%{$search}%")
                        ->orWhere('amount', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('name', 'like', "%{$search}%")
                              ->orWhere('username', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(15);

        return view('ssp.recharges.index', compact('recharges'));
    }

    /**
     * Approve a customer's recharge request.
     */
    public function approve(Request $request, Recharge $recharge)
    {
        abort_if($recharge->reseller_id !== auth()->id(), 403);

        if ($recharge->status !== 'pending') {
            return back()->with('error', __('This recharge request has already been processed.'));
        }

        $ssp = auth()->user();
        if ($ssp->balance < $recharge->amount) {
            return back()->with('error', __('Insufficient balance! You need at least :amount BDT in your account to approve this customer recharge. Please recharge your own account first.', ['amount' => $recharge->amount]));
        }

        DB::beginTransaction();
        try {
            $customer = $recharge->user;

            // Deduct from SSP balance
            $ssp->decrement('balance', $recharge->amount);

            // Add to Customer balance
            $customer->increment('balance', $recharge->amount);

            // Update recharge status
            $recharge->update([
                'status' => 'approved',
                'notes' => $request->notes ?? __('Approved by Sub-Reseller')
            ]);

            // SSP debit transaction
            Transaction::create([
                'type' => 'debit',
                'user_id' => $ssp->id,
                'gateway_id' => $recharge->gateway_id,
                'charge' => 0,
                'reason' => 'Customer Recharge: ' . ($customer->username ?? $customer->name),
                'amount' => $recharge->amount,
            ]);

            // Customer credit transaction
            Transaction::create([
                'type' => 'credit',
                'user_id' => $customer->id,
                'gateway_id' => $recharge->gateway_id,
                'charge' => 0,
                'reason' => 'Balance Recharged via ' . ($recharge->gateway->name ?? 'Sub-Reseller'),
                'amount' => $recharge->amount,
            ]);

            DB::commit();
            return back()->with('success', __('Recharge request approved and :amount BDT added to :name successfully.', ['amount' => $recharge->amount, 'name' => $customer->name]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Something went wrong: ') . $e->getMessage());
        }
    }

    /**
     * Reject a customer's recharge request.
     */
    public function reject(Request $request, Recharge $recharge)
    {
        abort_if($recharge->reseller_id !== auth()->id(), 403);

        if ($recharge->status !== 'pending') {
            return back()->with('error', __('This recharge request has already been processed.'));
        }

        $recharge->update([
            'status' => 'rejected',
            'notes' => $request->notes ?? __('Rejected by Sub-Reseller')
        ]);

        return back()->with('success', __('Recharge request rejected successfully.'));
    }

    /**
     * SSP recharging their own balance from parent SP (or Super Admin).
     */
    public function myRecharge()
    {
        $ssp = auth()->user();
        $parent = $ssp->parent;

        $gateways = collect();
        if ($parent) {
            $gateways = Gateway::where('user_id', $parent->id)->where('status', 1)->get();
        }
        if ($gateways->isEmpty()) {
            $gateways = Gateway::whereNull('user_id')->where('status', 1)->get();
        }

        $myRecharges = Recharge::with('gateway')
            ->where('user_id', $ssp->id)
            ->latest()
            ->paginate(10);

        return view('ssp.recharges.my-recharge', compact('gateways', 'myRecharges', 'parent'));
    }

    /**
     * Submit SSP recharge request to parent SP.
     */
    public function storeMyRecharge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'gateway_id' => 'required|exists:gateways,id',
            'manual_data' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $gateway = Gateway::findOrFail($request->gateway_id);
        $ssp = auth()->user();

        $data = [
            'amount' => $request->amount,
            'user_id' => $ssp->id,
            'gateway_id' => $gateway->id,
            'status' => 'pending',
            'manual_data' => ['note' => $request->manual_data],
            'reseller_id' => $gateway->user_id ?? $ssp->parent_id ?? null,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $this->upload($request, 'attachment');
        }

        Recharge::create($data);

        return redirect()->route('ssp.my-recharge')->with('success', __('Your recharge request has been submitted.'));
    }
}
