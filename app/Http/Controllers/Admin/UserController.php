<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\User;
use App\Models\Senderid;
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

class UserController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:users-create')->only('create', 'store');
        $this->middleware('permission:users-read')->only('index', 'show');
        $this->middleware('permission:users-update')->only('edit', 'update');
        $this->middleware('permission:users-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $users = User::where('role', '!=', 'superadmin')
                    ->with('plan:id,title')
                    ->where('role', $request->users)
                    ->when($request->has('search'), function ($q) use ($request) {
                        $q->where(function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%')
                                ->orWhere('role', 'like', '%' . $request->search . '%')
                                ->orWhere('balance', 'like', '%' . $request->search . '%')
                                ->orWhere('address', 'like', '%' . $request->search . '%')
                                ->orWhere('phone', 'like', '%' . $request->search . '%');
                        });
                    })
                    ->when(request('low_balance'), function($query) {
                        $query->where('balance', '<=', DB::raw('low_blnc_alrt'));
                    })
                    ->latest();

        if ($request->ajax()) {
            $users = $users->get();

            return response()->json([
                'data' => view('admin.users.datas', compact('users'))->render()
            ]);
        }

        $users = $users->paginate(10);
        $users->appends(['users' => request('users'), 'low_balance' => 'low_balance']);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $plans = Plan::latest()->get();
        $roles = Role::where('name', '!=', 'superadmin')->latest()->get();
        return view('admin.users.create', compact('roles', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
            'balance' => 'nullable|numeric',
            'low_blnc_alrt' => 'nullable|numeric',
            'phone' => 'nullable|string',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
            'image' => 'nullable|image|max:1024',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        DB::beginTransaction();
        try {
            if ($request->plan_id) {
                $plan = Plan::find($request->plan_id);
                $duration_in_days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 0)))));
            }

            $user = User::create($request->except('image', 'password', 'balance', 'low_blnc_alrt') + [
                'masking_rate' => $request->plan_id ? $plan->masking_rate : 0,
                'non_masking_rate' => $request->plan_id ? $plan->non_masking_rate : 0,
                'will_expire' => $request->plan_id ? now()->addDays($duration_in_days) : NULL,
                'balance' => $request->balance ?? 0,
                'password' => Hash::make($request->password),
                'low_blnc_alrt' => $request->low_blnc_alrt ?? 0,
                'client_secret' => $request->role == 'user' ? Str::uuid() : null,
                'image' => $request->image ? $this->upload($request, 'image') : null,
            ]);

            if ($request->role == 'user') {
                $sender_id = Senderid::where('is_default', 1)->first();

                Senderid::create([
                    'user_id' => $user->id,
                    'sender' => $sender_id->sender,
                    'type' => $sender_id->type,
                ]);

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
                'login_id' => $request->email,
                'password' => $request->password,
            ];

            if (env('MAIL_USERNAME')) {
                if (env('QUEUE_MAIL')) {
                    Mail::to($request->email)->queue(new WelcomeMail($data));
                } else {
                    Mail::to($request->email)->send(new WelcomeMail($data));
                }
            } else {
                return response()->json([
                    'message' => __('Please setup you mail credentials. For sending mail to the users.'),
                ], 406);
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
        $roles = Role::latest()->get();
        $plans = Plan::latest()->get();

        return view('admin.users.edit', compact('user', 'roles', 'plans'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role == 'superadmin' && auth()->user()->role != 'superadmin') {
            return response()->json(__('You can not update a superadmin.'), 400);
        }

        $request->validate([
            'balance' => 'nullable|numeric',
            'low_blnc_alrt' => 'nullable|numeric',
            'phone' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed',
            'image' => 'nullable|image|max:1024',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        DB::beginTransaction();
        try {

            if ($user->role != 'user') {
                $request->validate([
                    'role' => 'required|string',
                ]);

                $role = Role::where('name', $request->role)->first();
                $user->roles()->sync($role->id);
            }

            $duration_in_days = 0;

            if ($request->plan_id) {
                $plan = Plan::find($request->plan_id);
                $duration_in_days = $plan->duration == 'yearly' ? 365 : ($plan->duration == '6_monthly' ? 180 : ($plan->duration == '3_monthly' ? 90 : ($plan->duration == 'monthly' ? 30 : ($plan->duration == '15_days' ? 15 : ($plan->duration == 'weekly' ? 7 : 0)))));
            }

            $user->update($request->except('image', 'password') + [
                'masking_rate' => $plan->masking_rate ?? 0,
                'non_masking_rate' => $plan->non_masking_rate ?? 0,
                'will_expire' => $duration_in_days ? now()->addDays($duration_in_days) : NULL,
                'image' => $request->image ? $this->upload($request, 'image', $user->image) : $user->image,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            DB::commit();
            return response()->json([
                'message' => __($user->role == 'user' ? "User updated successfully" : "Staff updated successfully"),
                'redirect' => route('admin.users.index', ['users' => $user->role == 'user' ? 'user' : "admin"])
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
}
