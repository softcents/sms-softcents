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
use App\Models\Sms;
use App\Models\SenderIp;
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

    public function show($id)
    {
        $user = User::with([
            'plan',
            'parent:id,name,username,role',
            'senderIps' => function ($q) {
                $q->latest();
            }
        ])->findOrFail($id);

        $senderids = Senderid::where('user_id', $user->id)
            ->with('gateway:id,name,status')
            ->latest()
            ->get();

        // Calculate Profit & Loss specifically for this user
        $costSql = \App\Services\ProfitLossService::getCostSqlExpression('superadmin');
        $financial = Sms::where('sms.user_id', $user->id)
            ->leftJoin('senderids as s', 'sms.senderid_id', '=', 's.id')
            ->leftJoin('smsgateways as gw', 'sms.smsgateway_id', '=', 'gw.id')
            ->selectRaw("
                COUNT(*) as total_sms,
                COALESCE(SUM(sms.total_sms), COUNT(*)) as total_parts,
                COALESCE(SUM(sms.charge), 0) as sms_revenue,
                COALESCE(SUM({$costSql}), 0) as sms_cost,
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN sms.charge ELSE 0 END) as masking_revenue,
                SUM(CASE WHEN s.type LIKE '%Masking%' AND s.type NOT LIKE '%Non%' THEN {$costSql} ELSE 0 END) as masking_cost,
                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN sms.charge ELSE 0 END) as non_masking_revenue,
                SUM(CASE WHEN s.type NOT LIKE '%Masking%' OR s.type LIKE '%Non%' THEN {$costSql} ELSE 0 END) as non_masking_cost
            ")->first();

        $subscriptionRevenue = (float) PlanSubscribe::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('price');

        // Strictly calculate revenue and profit from USED SMS charges only (not package deposits)
        $smsRevenue = (float) ($financial->sms_revenue ?? 0);
        $totalCost = (float) ($financial->sms_cost ?? 0);
        $netProfit = $smsRevenue - $totalCost;
        $profitMargin = $smsRevenue > 0 ? round(($netProfit / $smsRevenue) * 100, 2) : 0;

        $stats = [
            'total_sms' => (int) ($financial->total_sms ?? 0),
            'total_parts' => (int) ($financial->total_parts ?? 0),
            'delivered_sms' => Sms::where('user_id', $user->id)->where('status', 'success')->count(),
            'failed_sms' => Sms::where('user_id', $user->id)->where('status', 'failed')->count(),
            'total_revenue' => $smsRevenue,
            'sms_revenue' => $smsRevenue,
            'subscription_revenue' => $subscriptionRevenue,
            'total_cost' => $totalCost,
            'net_profit' => $netProfit,
            'profit_margin' => $profitMargin,
            'used_revenue' => $smsRevenue,
            'used_cost' => $totalCost,
            'used_profit' => $netProfit,
            'used_margin' => $profitMargin,
            'masking_revenue' => (float) ($financial->masking_revenue ?? 0),
            'masking_cost' => (float) ($financial->masking_cost ?? 0),
            'masking_profit' => ((float) ($financial->masking_revenue ?? 0)) - ((float) ($financial->masking_cost ?? 0)),
            'non_masking_revenue' => (float) ($financial->non_masking_revenue ?? 0),
            'non_masking_cost' => (float) ($financial->non_masking_cost ?? 0),
            'non_masking_profit' => ((float) ($financial->non_masking_revenue ?? 0)) - ((float) ($financial->non_masking_cost ?? 0)),
            'total_recharged' => (float) Recharge::where('user_id', $user->id)->where('status', 'approved')->sum('amount'),
            'total_subscriptions' => PlanSubscribe::where('user_id', $user->id)->count(),
        ];

        $recentSms = Sms::where('user_id', $user->id)
            ->with(['senderid:id,sender', 'smsgateway:id,name'])
            ->latest()
            ->take(10)
            ->get();

        $recentRecharges = Recharge::where('user_id', $user->id)
            ->with('gateway:id,name')
            ->latest()
            ->take(5)
            ->get();

        $recentSubscriptions = PlanSubscribe::where('user_id', $user->id)
            ->with(['plan:id,title,price', 'gateway:id,name'])
            ->latest()
            ->take(5)
            ->get();

        $allPlans = Plan::where('status', 1)->latest()->get();
        $allRechargeableUsers = collect([$user]);

        return view('admin.users.show', compact(
            'user',
            'senderids',
            'stats',
            'recentSms',
            'recentRecharges',
            'recentSubscriptions',
            'allPlans',
            'allRechargeableUsers'
        ));
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

            $initialBalance = $request->filled('balance') ? (float) $request->balance : 0;
            if (!empty($plan) && (float) $plan->price > 0) {
                $initialBalance += (float) $plan->price;
            }

            $user = User::create($request->except('image', 'password', 'balance', 'low_blnc_alrt', 'white_label_logo', 'white_label_favicon') + [
                'masking_rate' => $request->filled('masking_rate') ? (float)$request->masking_rate : 0.75,
                'non_masking_rate' => $request->filled('non_masking_rate') ? (float)$request->non_masking_rate : 0.39,
                'will_expire' => now()->addYears(10),
                'balance' => $initialBalance,
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

                    if ((float) $plan->price > 0) {
                        Transaction::create([
                            'user_id' => $user->id,
                            'amount' => $plan->price,
                            'reason' => "Subscription Purchased: {$plan->title}",
                            'type' => 'credit',
                        ]);
                    }
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
                'masking_rate' => $request->filled('masking_rate') ? (float)$request->masking_rate : $user->masking_rate,
                'non_masking_rate' => $request->filled('non_masking_rate') ? (float)$request->non_masking_rate : $user->non_masking_rate,
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
        $adminName = $admin ? $admin->name : 'Admin';

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
            $planPrice = (float) $plan->price;
            $shouldAddBalance = $request->boolean('add_balance', false);
            $newBalance = (float) $user->balance;

            // Check if user already has an active subscription for the SAME plan
            $existingActiveSub = PlanSubscribe::where('user_id', $user->id)
                ->where('plan_id', $plan->id)
                ->where('status', 'approved')
                ->where(function ($q) {
                    $q->whereNull('will_expire')->orWhere('will_expire', '>=', now()->toDateString());
                })
                ->latest()
                ->first();

            if ($existingActiveSub) {
                // RENEW: Do not create duplicate active package! Extend expiration from current expiry date
                $baseDate = ($existingActiveSub->will_expire && \Carbon\Carbon::parse($existingActiveSub->will_expire)->isFuture())
                    ? \Carbon\Carbon::parse($existingActiveSub->will_expire)
                    : now();
                $willExpire = $baseDate->copy()->addDays($days);

                $existingActiveSub->update([
                    'will_expire' => $willExpire,
                    'notes' => ($existingActiveSub->notes ? $existingActiveSub->notes . " | " : "") . "Renewed on " . now()->format('d M Y') . " by " . $adminName,
                ]);

                if ($shouldAddBalance && $planPrice > 0) {
                    $newBalance += $planPrice;
                    Transaction::create([
                        'user_id' => $user->id,
                        'amount' => $planPrice,
                        'reason' => "Subscription Renewed: {$plan->title}",
                        'type' => 'credit',
                    ]);
                }

                $updateData = [
                    'plan_id' => $plan->id,
                    'balance' => $newBalance,
                    'allow_api' => $plan->allow_api ?? true,
                    'will_expire' => $willExpire,
                ];

                if ($request->boolean('update_rates', true)) {
                    $updateData['masking_rate'] = $maskingRate;
                    $updateData['non_masking_rate'] = $nonMaskingRate;
                }

                $user->update($updateData);
                $user->refresh();

                DB::commit();

                $message = __("Package ':plan' renewed successfully for :user! Expiration extended until :date.", [
                    'plan' => $plan->title,
                    'user' => $user->name,
                    'date' => $willExpire->format('d M Y'),
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'is_renewed' => true,
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
            }

            // If user has a DIFFERENT previous approved plan, mark old plan as expired so only one plan is active
            PlanSubscribe::where('user_id', $user->id)
                ->where('plan_id', '!=', $plan->id)
                ->where('status', 'approved')
                ->update(['status' => 'expired']);

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
                'notes' => $request->notes ?: "Plan assigned by Admin ({$adminName})",
            ]);

            if ($shouldAddBalance && $planPrice > 0) {
                $newBalance += $planPrice;

                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $planPrice,
                    'reason' => "Subscription Purchased: {$plan->title}",
                    'type' => 'credit',
                ]);
            }

            $updateData = [
                'plan_id' => $plan->id,
                'balance' => $newBalance,
                'allow_api' => $plan->allow_api ?? true,
                'will_expire' => $willExpire,
            ];

            if ($request->boolean('update_rates', true)) {
                $updateData['masking_rate'] = $maskingRate;
                $updateData['non_masking_rate'] = $nonMaskingRate;
            }

            $user->update($updateData);
            $user->refresh();

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

    /**
     * Update user tariff rates (Masking & Non-Masking)
     */
    public function updateTariff(Request $request, ?User $user = null)
    {
        $userId = $user ? $user->id : $request->user_id;

        $request->validate([
            'user_id' => $user ? 'nullable' : 'required|exists:users,id',
            'non_masking_rate' => 'required|numeric|min:0',
            'masking_rate' => 'required|numeric|min:0',
        ]);

        $targetUser = $user ?: User::findOrFail($userId);

        $targetUser->update([
            'non_masking_rate' => (float) $request->non_masking_rate,
            'masking_rate' => (float) $request->masking_rate,
        ]);

        $message = __('Tariff updated for :user — Non-Masking: ৳:nm | Masking: ৳:m', [
            'user' => $targetUser->name,
            'nm' => number_format($targetUser->non_masking_rate, 2),
            'm' => number_format($targetUser->masking_rate, 2),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'user_id' => $targetUser->id,
                'non_masking_rate' => (float) $targetUser->non_masking_rate,
                'masking_rate' => (float) $targetUser->masking_rate,
                'formatted_non_masking' => '৳' . number_format($targetUser->non_masking_rate, 2),
                'formatted_masking' => '৳' . number_format($targetUser->masking_rate, 2),
            ]);
        }

        return back()->with('success', $message);
    }
}
