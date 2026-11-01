<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Senderid;
use App\Models\Transaction;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\ImpersonationService;

class CustomerController extends Controller
{
    use HasUploader;

    public function index(Request $request)
    {
        $ssp = auth()->user();

        $customers = User::where('parent_id', $ssp->id)
            ->where('role', 'user')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(15);

        return view('ssp.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('ssp.customers.create');
    }

    public function store(Request $request)
    {
        $ssp = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:191|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
            'masking_rate' => 'nullable|numeric|min:0',
            'non_masking_rate' => 'nullable|numeric|min:0',
        ]);

        $customer = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'parent_id' => $ssp->id,
            'balance' => 0,
            'status' => 'active',
            'masking_rate' => $request->masking_rate ?? $ssp->masking_rate,
            'non_masking_rate' => $request->non_masking_rate ?? $ssp->non_masking_rate,
            'client_secret' => Str::uuid(),
        ]);

        $defaultSender = Senderid::where('is_default', 1)->first();
        if ($defaultSender) {
            Senderid::create([
                'user_id' => $customer->id,
                'sender' => $defaultSender->sender,
                'type' => $defaultSender->type,
                'gateway_id' => $defaultSender->gateway_id ?? 0,
                'rate' => $defaultSender->rate,
                'status' => true,
            ]);
        }

        try {
            if (\App\Helpers\ResellerMailHelper::configureMailer($ssp) || env('MAIL_USERNAME')) {
                $mailData = [
                    'name' => $customer->name,
                    'url' => route('login'),
                    'login_id' => $customer->username ?: $customer->email,
                    'password' => $request->password,
                ];
                \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\WelcomeMail($mailData));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Reseller welcome mail failed: ' . $e->getMessage());
        }

        return redirect()->route('ssp.customers.index')->with('success', __('Customer created successfully.'));
    }

    public function edit(User $customer)
    {
        if ($customer->parent_id !== auth()->id() || $customer->role !== 'user') {
            abort(403);
        }
        return view('ssp.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        if ($customer->parent_id !== auth()->id() || $customer->role !== 'user') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:191|unique:users,username,' . $customer->id,
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6|confirmed',
            'masking_rate' => 'nullable|numeric|min:0',
            'non_masking_rate' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:active,pending,suspend',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'masking_rate' => $request->masking_rate ?? $customer->masking_rate,
            'non_masking_rate' => $request->non_masking_rate ?? $customer->non_masking_rate,
            'status' => $request->status ?? $customer->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);

        return redirect()->route('ssp.customers.index')->with('success', __('Customer updated successfully.'));
    }

    public function recharge(Request $request, User $customer)
    {
        if ($customer->parent_id !== auth()->id() || $customer->role !== 'user') {
            abort(403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:add,deduct',
            'notes' => 'nullable|string|max:255',
        ]);

        $ssp = auth()->user();
        $amount = (float) $request->amount;

        DB::beginTransaction();
        try {
            if ($request->type === 'add') {
                if ($ssp->balance < $amount) {
                    return back()->with('error', __('You do not have enough balance to transfer.'));
                }

                $ssp->decrement('balance', $amount);
                $customer->increment('balance', $amount);

                Transaction::create([
                    'invoice_no' => 'TRX-' . strtoupper(Str::random(10)),
                    'user_id' => $customer->id,
                    'amount' => $amount,
                    'reason' => 'Balance recharged by SSP (' . $ssp->name . ')',
                    'type' => 'credit',
                ]);
            } else {
                if ($customer->balance < $amount) {
                    return back()->with('error', __('Customer does not have enough balance to deduct.'));
                }

                $customer->decrement('balance', $amount);
                $ssp->increment('balance', $amount);

                Transaction::create([
                    'invoice_no' => 'TRX-' . strtoupper(Str::random(10)),
                    'user_id' => $customer->id,
                    'amount' => $amount,
                    'reason' => 'Balance deducted by SSP (' . $ssp->name . ')',
                    'type' => 'debit',
                ]);
            }

            DB::commit();
            return back()->with('success', __('Balance updated successfully.'));
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('error', __('Failed to update balance: ' . $e->getMessage()));
        }
    }

    public function loginAs(User $customer)
    {
        return ImpersonationService::loginAs($customer);
    }
}
