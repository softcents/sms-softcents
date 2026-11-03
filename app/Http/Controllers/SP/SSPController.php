<?php

namespace App\Http\Controllers\SP;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\ImpersonationService;

class SSPController extends Controller
{
    use HasUploader;

    public function index(Request $request)
    {
        $sp = auth()->user();

        $ssps = User::where('parent_id', $sp->id)
            ->where('role', 'ssp')
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

        return view('sp.ssps.index', compact('ssps'));
    }

    public function create()
    {
        return view('sp.ssps.create');
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
            'company_name' => 'nullable|string|max:191',
        ]);

        $ssp = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'ssp',
            'parent_id' => $sp->id,
            'balance' => 0,
            'status' => 'active',
            'masking_rate' => $request->filled('masking_rate') ? (float)$request->masking_rate : (float)($sp->masking_rate ?? 0.75),
            'non_masking_rate' => $request->filled('non_masking_rate') ? (float)$request->non_masking_rate : (float)($sp->non_masking_rate ?? 0.39),
            'company_name' => $request->company_name,
            'client_secret' => Str::uuid(),
        ]);

        try {
            if (\App\Helpers\ResellerMailHelper::configureMailer($sp) || env('MAIL_USERNAME')) {
                $mailData = [
                    'name' => $ssp->name,
                    'url' => route('login'),
                    'login_id' => $ssp->username ?: $ssp->email,
                    'password' => $request->password,
                ];
                \Illuminate\Support\Facades\Mail::to($ssp->email)->send(new \App\Mail\WelcomeMail($mailData));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Reseller welcome mail failed: ' . $e->getMessage());
        }

        return redirect()->route('sp.ssps.index')->with('success', __('Sub Reseller (SSP) created successfully.'));
    }

    public function edit(User $ssp)
    {
        if ($ssp->parent_id !== auth()->id() || $ssp->role !== 'ssp') {
            abort(403);
        }
        return view('sp.ssps.edit', compact('ssp'));
    }

    public function update(Request $request, User $ssp)
    {
        if ($ssp->parent_id !== auth()->id() || $ssp->role !== 'ssp') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:191|unique:users,username,' . $ssp->id,
            'email' => 'required|email|unique:users,email,' . $ssp->id,
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6|confirmed',
            'masking_rate' => 'nullable|numeric|min:0',
            'non_masking_rate' => 'nullable|numeric|min:0',
            'company_name' => 'nullable|string|max:191',
            'status' => 'nullable|string|in:active,pending,suspend',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'masking_rate' => $request->filled('masking_rate') ? (float)$request->masking_rate : $ssp->masking_rate,
            'non_masking_rate' => $request->filled('non_masking_rate') ? (float)$request->non_masking_rate : $ssp->non_masking_rate,
            'company_name' => $request->company_name,
            'status' => $request->status ?? $ssp->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $ssp->update($data);

        return redirect()->route('sp.ssps.index')->with('success', __('Sub Reseller (SSP) updated successfully.'));
    }

    /**
     * Quick update Sub Reseller SMS rates.
     */
    public function updateRate(Request $request, User $ssp)
    {
        if ($ssp->parent_id !== auth()->id() || $ssp->role !== 'ssp') {
            abort(403);
        }

        $request->validate([
            'masking_rate' => 'required|numeric|min:0',
            'non_masking_rate' => 'required|numeric|min:0',
        ]);

        $ssp->update([
            'masking_rate' => (float) $request->masking_rate,
            'non_masking_rate' => (float) $request->non_masking_rate,
        ]);

        return back()->with('success', __('Sub Reseller SMS rate updated successfully: Masking: ৳:masking | Non-Masking: ৳:non_masking', [
            'masking' => $ssp->masking_rate,
            'non_masking' => $ssp->non_masking_rate,
        ]));
    }

    public function recharge(Request $request, User $ssp)
    {
        if ($ssp->parent_id !== auth()->id() || $ssp->role !== 'ssp') {
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
                $ssp->increment('balance', $amount);

                Transaction::create([
                    'invoice_no' => 'TRX-' . strtoupper(Str::random(10)),
                    'user_id' => $ssp->id,
                    'amount' => $amount,
                    'reason' => 'Balance recharged by SP (' . $sp->name . ')',
                    'type' => 'credit',
                ]);
            } else {
                if ($ssp->balance < $amount) {
                    return back()->with('error', __('Sub Reseller does not have enough balance to deduct.'));
                }

                $ssp->decrement('balance', $amount);
                $sp->increment('balance', $amount);

                Transaction::create([
                    'invoice_no' => 'TRX-' . strtoupper(Str::random(10)),
                    'user_id' => $ssp->id,
                    'amount' => $amount,
                    'reason' => 'Balance deducted by SP (' . $sp->name . ')',
                    'type' => 'debit',
                ]);
            }

            DB::commit();
            return back()->with('success', __('Balance updated successfully.'));
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('error', __('Failed to process balance: ' . $e->getMessage()));
        }
    }

    public function loginAs(User $ssp)
    {
        return ImpersonationService::loginAs($ssp);
    }
}
