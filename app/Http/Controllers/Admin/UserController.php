<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\User;
use App\Models\Senderid;
use App\Models\Transaction;
use App\Models\Recharge;
use App\Models\Gateway;
use App\Mail\WelcomeMail;
use Illuminate\Support\Str;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\PlanSubscribe;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Services\ImpersonationService;

class UserController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:users-create')->only('create', 'store');
        $this->middleware('permission:users-read')->only('index', 'show');
        $this->middleware('permission:users-update')->only('edit', 'update', 'recharge');
        $this->middleware('permission:users-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $role = $request->get('users', 'user');

        $users = User::where('role', '!=', 'superadmin')
                    ->with(['plan:id,title', 'parent:id,name,username'])
                    ->where('role', $role)
                    ->when($request->filled('search'), function ($q) use ($request) {
                        $term = trim($request->search);
                        $likeOperator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                        $q->where(function ($query) use ($term, $likeOperator) {
                            $query->where('name', $likeOperator, '%' . $term . '%')
                                ->orWhere('username', $likeOperator, '%' . $term . '%')
                                ->orWhere('email', $likeOperator, '%' . $term . '%')
                                ->orWhere('role', $likeOperator, '%' . $term . '%')
                                ->orWhere('balance', $likeOperator, '%' . $term . '%')
                                ->orWhere('address', $likeOperator, '%' . $term . '%')
                                ->orWhere('phone', $likeOperator, '%' . $term . '%');
                        });
                    })
                    ->when($request->filled('low_balance'), function($query) {
                        $query->where('balance', '<=', DB::raw('low_blnc_alrt'));
                    })
                    ->latest();

        $allPlans = Plan::where('status', 1)->latest()->get();

        if ($request->ajax()) {
            $users = $users->get();

            return response()->json([
                'data' => view('admin.users.datas', compact('users', 'allPlans'))->render()
            ]);
        }

        $users = $users->paginate(10)->withQueryString();

        $allRechargeableUsers = User::where('role', '!=', 'superadmin')
            ->select('id', 'name', 'username', 'role', 'balance', 'phone')
            ->orderByRaw("CASE role WHEN 'sp' THEN 1 WHEN 'ssp' THEN 2 WHEN 'user' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users', 'allRechargeableUsers', 'allPlans'));
    }

    public function create()
    {
        $plans = Plan::latest()->get();
        $roles = Role::where('name', '!=', 'superadmin')->latest()->get();
        $resellers = User::whereIn('role', ['sp', 'ssp'])->select('id', 'name', 'username', 'role')->get();
        return view('admin.users.create', compact('roles', 'plans', 'resellers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
            'username' => 'nullable|string|max:191|unique:users,username',
            'balance' => 'nullable|numeric',
            'low_blnc_alrt' => 'nullable|numeric',
            'phone' => 'nullable|string',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
            'image' => 'nullable|image|max:2048',
            'plan_id' => 'nullable|exists:plans,id',
            'masking_rate' => 'nullable|numeric|min:0',
            'non_masking_rate' => 'nullable|numeric|min:0',
            'parent_id' => 'nullable|exists:users,id',
            'company_name' => 'nullable|string|max:191',
            'white_label_title' => 'nullable|string|max:191',
            'white_label_logo' => 'nullable|image|max:2048',
            'white_label_favicon' => 'nullable|image|max:1024',
        ]);

        DB::beginTransaction();
        try {
            if ($request->plan_id) {
                $plan = Plan::find($request->plan_id);
                $duration_in_days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 0)))));
            }

            $user = User::create($request->except('image', 'password', 'balance', 'low_blnc_alrt', 'white_label_logo', 'white_label_favicon') + [
                'masking_rate' => $request->masking_rate ?? ($request->plan_id ? $plan->masking_rate : 0),
                'non_masking_rate' => $request->non_masking_rate ?? ($request->plan_id ? $plan->non_masking_rate : 0),
                'will_expire' => $request->plan_id ? now()->addDays($duration_in_days) : NULL,
                'balance' => $request->balance ?? 0,
                'password' => Hash::make($request->password),
                'low_blnc_alrt' => $request->low_blnc_alrt ?? 0,
                'client_secret' => Str::uuid(),
                'image' => $request->image ? $this->upload($request, 'image') : null,
                'white_label_logo' => $request->white_label_logo ? $this->upload($request, 'white_label_logo') : null,
                'white_label_favicon' => $request->white_label_favicon ? $this->upload($request, 'white_label_favicon') : null,
            ]);

            if ($request->role == 'user') {
                $sender_id = Senderid::where('is_default', 1)->first();

                if ($sender_id) {
                    Senderid::create([
                        'user_id' => $user->id,
                        'sender' => $sender_id->sender,
                        'type' => $sender_id->type,
                        'gateway_id' => $sender_id->gateway_id ?? 0,
                        'rate' => $sender_id->rate,
                    ]);
                }

                if ($plan ?? false) {
                    PlanSubscribe::create([
                        'plan_data' => $plan,
                        'status' => 'approved',
                        'plan_id' => $plan->id,
                        'user_id' => $user->id,
                        'price' => $plan->price,
                        'total_sms' => $plan->total_sms,
                        'masking_rate' => $plan->masking_rate,
                        'non_masking_rate' => $plan->non_masking_rate,
                        'will_expire' => now()->addDays($duration_in_days),
                    ]);
                }
            }

            $data = [
                'url' => route('login'),
                'name' => $request->name,
                'login_id' => $user->username ?: $request->email,
                'password' => $request->password,
            ];

            try {
                if (env('MAIL_USERNAME')) {
                    if (env('QUEUE_MAIL')) {
                        Mail::to($request->email)->queue(new WelcomeMail($data));
                    } else {
                        Mail::to($request->email)->send(new WelcomeMail($data));
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Welcome mail failed: ' . $e->getMessage());
            }

            if ($request->role != 'user') {
                $role = Role::where('name', $request->role)->first();
                $user->roles()->sync($role->id);
            }

            sendNotification($user->id, route('admin.users.index', ['users' => $request->role]), __(ucfirst($request->role) . ' has been created.'));

            DB::commit();
            return response()->json([
                'message' => __(ucfirst($request->role) . ' created successfully'),
                'redirect' => route('admin.users.index', ['users' => $request->role])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something went wrong.'), 400);
        }
    }

    public function edit(User $user)
    {
        if ($user->role == 'superadmin' && auth()->user()->role != 'superadmin') {
            abort(403);
        }
        $roles = Role::where('name', '!=', 'superadmin')->latest()->get();
        $plans = Plan::latest()->get();
        $resellers = User::whereIn('role', ['sp', 'ssp'])->where('id', '!=', $user->id)->select('id', 'name', 'username', 'role')->get();

        return view('admin.users.edit', compact('user', 'roles', 'plans', 'resellers'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role == 'superadmin' && auth()->user()->role != 'superadmin') {
            return response()->json(__('You can not update a superadmin.'), 400);
        }

        $request->validate([
            'username' => 'nullable|string|max:191|unique:users,username,' . $user->id,
            'balance' => 'nullable|numeric',
            'low_blnc_alrt' => 'nullable|numeric',
            'phone' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed',
            'image' => 'nullable|image|max:2048',
            'plan_id' => 'nullable|exists:plans,id',
            'masking_rate' => 'nullable|numeric|min:0',
            'non_masking_rate' => 'nullable|numeric|min:0',
            'parent_id' => 'nullable|exists:users,id',
            'company_name' => 'nullable|string|max:191',
            'white_label_title' => 'nullable|string|max:191',
            'white_label_logo' => 'nullable|image|max:2048',
            'white_label_favicon' => 'nullable|image|max:1024',
        ]);

        DB::beginTransaction();
        try {

            if ($user->role != 'user' && $request->filled('role')) {
                $role = Role::where('name', $request->role)->first();
                if ($role) {
                    $user->roles()->sync($role->id);
                }
            }

            $duration_in_days = 0;

            if ($request->plan_id) {
                $plan = Plan::find($request->plan_id);
                $duration_in_days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 0)))));
            }

            $user->update($request->except('image', 'password', 'white_label_logo', 'white_label_favicon') + [
                'masking_rate' => $request->masking_rate ?? ($plan->masking_rate ?? $user->masking_rate),
                'non_masking_rate' => $request->non_masking_rate ?? ($plan->non_masking_rate ?? $user->non_masking_rate),
                'will_expire' => $duration_in_days ? now()->addDays($duration_in_days) : $user->will_expire,
                'image' => $request->image ? $this->upload($request, 'image', $user->image) : $user->image,
                'white_label_logo' => $request->white_label_logo ? $this->upload($request, 'white_label_logo', $user->white_label_logo) : $user->white_label_logo,
                'white_label_favicon' => $request->white_label_favicon ? $this->upload($request, 'white_label_favicon', $user->white_label_favicon) : $user->white_label_favicon,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            if (!empty($plan) && $plan->id != $user->getOriginal('plan_id')) {
                PlanSubscribe::create([
                    'plan_data' => $plan->toArray(),
                    'status' => 'approved',
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'price' => $plan->price,
                    'total_sms' => $plan->total_sms,
                    'masking_rate' => $user->masking_rate,
                    'non_masking_rate' => $user->non_masking_rate,
                    'will_expire' => $user->will_expire,
                    'notes' => 'Updated via User Profile Edit',
                ]);
            }

            DB::commit();
            return response()->json([
                'message' => __(ucfirst($user->role) . " updated successfully"),
                'redirect' => route('admin.users.index', ['users' => $user->role])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something went wrong.'), 400);
        }
    }


    public function destroy(User $user)
    {
        if ($user->role == 'superadmin') {
            return response()->json(__('You can not delete a superadmin.'), 400);
        }
        if ($user->balance) {
            return response()->json(__('You can not delete this user. This user balance is ' . $user->balance), 406);
        }

        $type = $user->role;
        if (file_exists($user->image)) {
            Storage::delete($user->image);
        }
        $user->delete();
        return response()->json([
            'message' => __(ucfirst($type) . ' deleted successfully'),
            'redirect' => route('admin.users.index', ['users' => $type])
        ]);
    }


    public function deleteAll(Request $request)
    {
        $idsToDelete = $request->input('ids');

        $superadminUsers = User::whereIn('id', $idsToDelete)->where('role', 'superadmin')->count();
        if ($superadminUsers > 0) {
            return response()->json(__('You cannot delete a superadmin.'), 400);
        }

        $has_balance = User::whereIn('id', $idsToDelete)->where('balance', '>', 0)->exists();
        if ($has_balance) {
            return response()->json(__('You can not delete any user who has any balance.'), 406);
        }

        $users = User::whereIn('id', $idsToDelete)->get();
        foreach ($users as $user) {
            if (file_exists($user->image)) {
                Storage::delete($user->image);
            }
        }

        User::whereIn('id', $idsToDelete)->delete();

        return response()->json([
            'message' => __('User deleted successfully'),
            'redirect' => url()->previous(),
        ]);
    }

    public function loginAs(User $user)
    {
        return ImpersonationService::loginAs($user);
    }

    public function returnToAdmin()
    {
        return ImpersonationService::returnToPrevious();
    }

    /**
     * Admin direct balance recharge or deduction for Resellers (SP/SSP) and Customers.
     */
    public function recharge(Request $request, ?User $user = null)
    {
        if (!$user || !$user->exists) {
            $request->validate(['user_id' => 'required|exists:users,id']);
            $user = User::findOrFail($request->user_id);
        }

        if ($user->role === 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => __('Cannot adjust balance for superadmin.')
            ], 422);
        }

        $request->validate([
            'type' => 'required|in:add,deduct',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:100',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $amount = (float) $request->amount;
        $type = $request->type; // 'add' or 'deduct'
        $admin = auth()->user();

        if ($type === 'deduct' && $user->balance < $amount) {
            return response()->json([
                'success' => false,
                'message' => __('User does not have enough balance to deduct.') . ' ' . __('Current balance: ') . currency_format($user->balance)
            ], 422);
        }

        DB::beginTransaction();
        try {
            if ($type === 'add') {
                $user->increment('balance', $amount);
                $actionText = __('recharged by Admin');
                $trxType = 'credit';
            } else {
                $user->decrement('balance', $amount);
                $actionText = __('deducted by Admin');
                $trxType = 'debit';
            }

            $user->refresh();

            $noteSuffix = $request->notes ? ' - ' . $request->notes : '';
            $paymentMethod = $request->payment_method ?: 'Admin Direct / Cash';
            $defaultGateway = Gateway::where('is_manual', true)->first() ?? Gateway::first();
            $gatewayId = $defaultGateway ? $defaultGateway->id : null;

            // 1. Transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'gateway_id' => $gatewayId,
                'amount' => $amount,
                'charge' => 0,
                'reason' => "Balance {$actionText} ({$admin->name}){$noteSuffix}",
                'type' => $trxType,
            ]);

            // 2. Recharge record (for audit trail & reports)
            $recharge = Recharge::create([
                'user_id' => $user->id,
                'gateway_id' => $gatewayId,
                'amount' => $amount,
                'status' => 'approved',
                'notes' => ($type === 'deduct' ? '[DEDUCTION] ' : '') . ($request->notes ?: "Balance {$actionText} ({$admin->name})"),
                'manual_data' => [
                    'method' => $paymentMethod,
                    'trxid' => $request->transaction_id ?: $transaction->invoice_no,
                    'admin_id' => $admin->id,
                    'admin_name' => $admin->name,
                    'type' => $type,
                ],
                'reseller_id' => null,
            ]);

            DB::commit();

            $formattedNewBalance = currency_format($user->balance);
            $message = $type === 'add'
                ? __(':amount added successfully to :name. New Balance: :balance', [
                    'amount' => currency_format($amount),
                    'name' => $user->name,
                    'balance' => $formattedNewBalance,
                ])
                : __(':amount deducted successfully from :name. New Balance: :balance', [
                    'amount' => currency_format($amount),
                    'name' => $user->name,
                    'balance' => $formattedNewBalance,
                ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'user_id' => $user->id,
                    'balance' => $user->balance,
                    'formatted_balance' => $formattedNewBalance,
                    'is_low_balance' => $user->low_blnc_alrt >= $user->balance,
                ]);
            }

            return back()->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to update balance: ') . $e->getMessage()
                ], 500);
            }
            return back()->with('error', __('Failed to update balance: ') . $e->getMessage());
        }
    }

    /**
     * Assign or upgrade a subscription plan to a user
     */
    public function assignPlan(Request $request, ?User $user = null)
    {
        if (!$user || !$user->exists) {
            $request->validate(['user_id' => 'required|exists:users,id']);
            $user = User::findOrFail($request->user_id);
        }

        if ($user->role === 'superadmin') {
            return response()->json([
                'success' => false,
                'message' => __('Cannot assign plan to superadmin.')
            ], 422);
        }

        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'duration_days' => 'nullable|integer|min:1',
            'add_balance' => 'nullable|boolean',
            'update_rates' => 'nullable|boolean',
            'custom_masking_rate' => 'nullable|numeric|min:0',
            'custom_non_masking_rate' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $admin = auth()->user();

        // Calculate validity
        $days = (int) $request->duration_days;
        if ($days <= 0) {
            $days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 365)))));
        }
        $willExpire = now()->addDays($days);

        $planNonMasking = (float) $plan->non_masking_rate;
        if ($planNonMasking > 5) {
            $planNonMasking = $planNonMasking / 100;
        }

        $maskingRate = $request->filled('custom_masking_rate') ? (float) $request->custom_masking_rate : (float) $plan->masking_rate;
        $nonMaskingRate = $request->filled('custom_non_masking_rate') ? (float) $request->custom_non_masking_rate : $planNonMasking;

        DB::beginTransaction();
        try {
            $subscribe = PlanSubscribe::create([
                'plan_data' => $plan->toArray(),
                'status' => 'approved',
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'price' => $plan->price,
                'total_sms' => $plan->total_sms,
                'masking_rate' => $maskingRate,
                'non_masking_rate' => $nonMaskingRate,
                'will_expire' => $willExpire,
                'notes' => $request->notes ?: "Plan assigned by Admin ({$admin->name})",
            ]);

            $updateData = [
                'plan_id' => $plan->id,
                'allow_api' => $plan->allow_api ?? true,
                'will_expire' => $willExpire,
            ];

            if ($request->boolean('update_rates', true)) {
                $updateData['masking_rate'] = $maskingRate;
                $updateData['non_masking_rate'] = $nonMaskingRate;
            }

            if ($request->boolean('add_balance', false)) {
                $updateData['balance'] = (float) $user->balance + (float) $plan->price;
            }

            $user->update($updateData);

            DB::commit();

            $message = __("Plan ':plan' assigned successfully to :user. Validity until :date.", [
                'plan' => $plan->title,
                'user' => $user->name,
                'date' => $willExpire->format('d M Y'),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_title' => $plan->title,
                    'masking_rate' => $user->masking_rate,
                    'non_masking_rate' => $user->non_masking_rate,
                    'will_expire' => $willExpire->format('d M Y'),
                    'balance' => $user->balance,
                    'formatted_balance' => currency_format($user->balance),
                ]);
            }

            return back()->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to assign plan: ') . $e->getMessage()
                ], 500);
            }
            return back()->with('error', __('Failed to assign plan: ') . $e->getMessage());
        }
    }
}
