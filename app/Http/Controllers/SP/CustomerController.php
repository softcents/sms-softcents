<?php

namespace App\Http\Controllers\SP;

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
        $sp = auth()->user();

        // Include direct customers and customers of downline SSPs
        $sspIds = User::where('parent_id', $sp->id)->where('role', 'ssp')->pluck('id')->toArray();
        $allowedParentIds = array_merge([$sp->id], $sspIds);

        $filter = $request->get('filter', 'all'); // 'all', 'direct', 'ssp'

        $query = User::where('role', 'user');

        if ($filter === 'direct') {
            $query->where('parent_id', $sp->id);
        } elseif ($filter === 'ssp') {
            $query->whereIn('parent_id', $sspIds);
        } else {
            $query->whereIn('parent_id', $allowedParentIds);
        }

        $customers = $query
            ->with('parent:id,name,username,role')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('username', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('sp.customers.index', compact('customers', 'filter'));
    }

    public function create()
    {
        return view('sp.customers.create');
    }

    public function store(Request $request)
    {
        $sp = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:191|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
            'masking_rate' => 'nullable|numeric|min:0',
            'non_masking_rate' => 'nullable|numeric|min:0',
        ], [
            'name.required' => __('Full Name is required.'),
            'email.required' => __('Email Address is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'email.unique' => __('This email address is already registered.'),
            'username.unique' => __('This username is already taken. Please choose another.'),
            'password.required' => __('Password is required.'),
            'password.min' => __('Password must be at least 6 characters.'),
            'password.confirmed' => __('Confirm Password does not match Password.'),
        ]);

        DB::beginTransaction();
        try {
            $customer = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'parent_id' => $sp->id,
                'balance' => 0,
                'status' => 'active',
                'masking_rate' => $request->filled('masking_rate') ? (float)$request->masking_rate : (float)($sp->masking_rate ?? 0.75),
                'non_masking_rate' => $request->filled('non_masking_rate') ? (float)$request->non_masking_rate : (float)($sp->non_masking_rate ?? 0.39),
                'client_secret' => Str::uuid(),
            ]);

            $defaultSender = Senderid::where('is_default', 1)->first();
            if ($defaultSender) {
                Senderid::create([
                    'user_id' => $customer->id,
                    'sender' => $defaultSender->sender,
                    'type' => $defaultSender->type,
                    'gateway_id' => $defaultSender->gateway_id ?? 0,
                    'rate' => null,
                    'status' => true,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Customer creation failed: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => __('Failed to create customer: ') . $e->getMessage()
                ], 422);
            }

            return back()->withInput()->with('error', __('Failed to create customer: ') . $e->getMessage());
        }

        try {
            if (\App\Helpers\ResellerMailHelper::configureMailer($sp) || env('MAIL_USERNAME')) {
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => __('Customer created successfully.'),
                'redirect' => route('sp.customers.index')
            ]);
        }

        return redirect()->route('sp.customers.index')->with('success', __('Customer created successfully.'));
    }

    public function edit(User $customer)
    {
        if ($customer->parent_id !== auth()->id() || $customer->role !== 'user') {
            abort(403);
        }
        return view('sp.customers.edit', compact('customer'));
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
            'masking_rate' => $request->filled('masking_rate') ? (float)$request->masking_rate : $customer->masking_rate,
            'non_masking_rate' => $request->filled('non_masking_rate') ? (float)$request->non_masking_rate : $customer->non_masking_rate,
            'status' => $request->status ?? $customer->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => __('Customer updated successfully.'),
                'redirect' => route('sp.customers.index')
            ]);
        }

        return redirect()->route('sp.customers.index')->with('success', __('Customer updated successfully.'));
    }

    /**
     * Quick update customer rates.
     */
    public function updateRate(Request $request, User $customer)
    {
        if ($customer->parent_id !== auth()->id() || $customer->role !== 'user') {
            abort(403);
        }

        $request->validate([
            'masking_rate' => 'required|numeric|min:0',
            'non_masking_rate' => 'required|numeric|min:0',
        ]);

        $customer->update([
            'masking_rate' => (float) $request->masking_rate,
            'non_masking_rate' => (float) $request->non_masking_rate,
        ]);

        return back()->with('success', __('Customer SMS rate updated successfully: Masking: ৳:masking | Non-Masking: ৳:non_masking', [
            'masking' => $customer->masking_rate,
            'non_masking' => $customer->non_masking_rate,
        ]));
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

        $sp = auth()->user();
        $amount = (float) $request->amount;

        DB::beginTransaction();
        try {
            if ($request->type === 'add') {
                if ($sp->balance < $amount) {
                    return back()->with('error', __('You do not have enough balance to transfer.'));
                }

                $sp->decrement('balance', $amount);
                $customer->increment('balance', $amount);

                Transaction::create([
                    'invoice_no' => 'TRX-' . strtoupper(Str::random(10)),
                    'user_id' => $customer->id,
                    'amount' => $amount,
                    'reason' => 'Balance recharged by SP (' . $sp->name . ')',
                    'type' => 'credit',
                ]);
            } else {
                if ($customer->balance < $amount) {
                    return back()->with('error', __('Customer does not have enough balance to deduct.'));
                }

                $customer->decrement('balance', $amount);
                $sp->increment('balance', $amount);

                Transaction::create([
                    'invoice_no' => 'TRX-' . strtoupper(Str::random(10)),
                    'user_id' => $customer->id,
                    'amount' => $amount,
                    'reason' => 'Balance deducted by SP (' . $sp->name . ')',
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
