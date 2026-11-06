<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Senderid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Smsgateway;

class SoftcentsSenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $gatewayId = $request->get('gateway_id');
        $type = $request->get('type');
        $status = $request->get('status');
        $usage = $request->get('usage');
        $perPage = (int) $request->get('per_page', 20);
        if ($perPage <= 0 || $perPage > 500) {
            $perPage = 20;
        }

        $query = Senderid::with(['user', 'gateway'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);
                $q->where(function ($sub) use ($search) {
                    $sub->where('sender', 'like', '%' . $search . '%')
                        ->orWhere('type', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('name', 'like', '%' . $search . '%')
                              ->orWhere('email', 'like', '%' . $search . '%')
                              ->orWhere('phone', 'like', '%' . $search . '%')
                              ->orWhere('username', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('gateway', function ($g) use ($search) {
                            $g->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($request->filled('gateway_id'), function ($q) use ($gatewayId) {
                if ($gatewayId === '0' || $gatewayId === 'unassigned') {
                    $q->where(function ($sub) {
                        $sub->whereNull('gateway_id')->orWhere('gateway_id', 0);
                    });
                } else {
                    $q->where('gateway_id', (int) $gatewayId);
                }
            })
            ->when($request->filled('usage'), function ($q) use ($usage) {
                if ($usage === 'unused') {
                    $q->whereNull('user_id');
                } elseif ($usage === 'used') {
                    $q->whereNotNull('user_id');
                }
            })
            ->when($request->filled('type'), function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($request->filled('status'), function ($q) use ($status) {
                $q->where('status', (int) $status);
            })
            ->latest('id');

        // Total count of senderids & metrics
        $totalSenderidsCount = Senderid::count();
        $totalUnusedCount = Senderid::whereNull('user_id')->count();
        $totalUsedCount = Senderid::whereNotNull('user_id')->count();
        $unassignedCount = Senderid::where(function ($q) {
            $q->whereNull('gateway_id')->orWhere('gateway_id', 0);
        })->count();
        $unassignedUnusedCount = Senderid::where(function ($q) {
            $q->whereNull('gateway_id')->orWhere('gateway_id', 0);
        })->whereNull('user_id')->count();
        $maskingCount = Senderid::where('type', 'Masking')->count();
        $nonMaskingCount = Senderid::where('type', 'Non-Masking')->count();
        $activeCount = Senderid::where('status', 1)->count();
        $deactiveCount = Senderid::where('status', 0)->count();

        // Gateway statistics (Name + total count + unused count)
        $gateways = Smsgateway::select('id', 'name', 'status')
            ->withCount([
                'senderids',
                'senderids as unused_senderids_count' => function ($q) {
                    $q->whereNull('user_id');
                },
                'senderids as used_senderids_count' => function ($q) {
                    $q->whereNotNull('user_id');
                }
            ])
            ->orderBy('name')
            ->get();

        $gatewayList = Smsgateway::select('id', 'name')->latest()->get();

        $senderids = $query->paginate($perPage)->withQueryString();

        // Calculate how many distinct users each sender on this page is assigned to
        $senderUserCounts = Senderid::select('sender', \Illuminate\Support\Facades\DB::raw('count(distinct user_id) as user_count'))
            ->whereIn('sender', $senderids->pluck('sender')->unique()->filter())
            ->groupBy('sender')
            ->pluck('user_count', 'sender');

        if ($request->ajax()) {
            return response()->json([
                'data' => view('admin.senderids.datas', compact('senderids', 'senderUserCounts'))->render(),
                'pagination' => (string) $senderids->links('pagination::bootstrap-5'),
                'total' => $senderids->total(),
            ]);
        }

        $userList = User::whereIn('role', ['user', 'sp', 'ssp'])
            ->select('id', 'name', 'username', 'phone', 'role')
            ->orderBy('name')
            ->get();

        return view('admin.senderids.index', compact(
            'senderids',
            'senderUserCounts',
            'gateways',
            'gatewayList',
            'userList',
            'totalSenderidsCount',
            'totalUnusedCount',
            'totalUsedCount',
            'unassignedCount',
            'unassignedUnusedCount',
            'maskingCount',
            'nonMaskingCount',
            'activeCount',
            'deactiveCount'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::whereIn('role', ['user', 'sp', 'ssp'])->select('id', 'name', 'username', 'phone', 'role')->orderBy('name')->get();
        $gatewayList = Smsgateway::latest()->select("id", "name")->get();
        return view('admin.senderids.create', compact('users','gatewayList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'sender' => 'required|string|max:500',
            'type' => 'required|string|in:Non-Masking,Masking',
            'status' => 'required|integer|in:0,1',
            'gateway_id' => 'required',
        ]);
        
        $data = $request->except('rate');
        if (empty($data['user_id'])) {
            $data['user_id'] = null;
        }

        Senderid::create($data);

        return response()->json([
            'message' => __('Senderid created successfully'),
            'redirect' => route('admin.senderids.index')
        ]);
    }

    public function edit(Senderid $senderid)
    {
        $users = User::whereIn('role', ['user', 'sp', 'ssp'])->select('id', 'name', 'username', 'phone', 'role')->orderBy('name')->get();
        $gatewayList = Smsgateway::latest()->select("id", "name")->get();
        return view('admin.senderids.edit', compact('senderid', 'users','gatewayList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Senderid $senderid)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'sender' => 'required|string|max:500',
            'type' => 'required|string|in:Non-Masking,Masking',
            'status' => 'required|integer|in:0,1',
            'gateway_id' => 'required',
        ]);

        $data = $request->except('rate');
        if (empty($data['user_id'])) {
            $data['user_id'] = null;
        }

        $senderid->update($data);

        return response()->json([
            'message' => __('Senderid updated successfully'),
            'redirect' => route('admin.senderids.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Senderid $senderid)
    {
        $senderid->delete();

        return response()->json([
            'message' => __('Senderid deleted successfully'),
            'redirect' => route('admin.senderids.index')
        ]);
    }

    public function status(Request $request, $id)
    {
        $senderid = Senderid::findOrFail($id);
        $senderid->update(['status' => $request->status]);
        return response()->json(['message' => __('Senderid status updated.')]);
    }

    public function deleteAll(Request $request)
    {
        Senderid::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Senderid deleted successfully'),
            'redirect' => route('admin.senderids.index')
        ]);
    }

    /**
     * Quick / Bulk Assign Gateway to selected Sender IDs
     */
    public function assignGateway(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:senderids,id',
            'gateway_id' => 'required',
        ]);

        $targetGatewayId = (int) $request->gateway_id;
        if ($targetGatewayId !== 0) {
            $gwExists = Smsgateway::where('id', $targetGatewayId)->exists();
            if (!$gwExists) {
                return response()->json(['error' => __('Invalid gateway selected')], 422);
            }
        }

        Senderid::whereIn('id', $request->ids)->update([
            'gateway_id' => $targetGatewayId
        ]);

        $count = count($request->ids);
        $gatewayName = $targetGatewayId === 0 ? __('Unassigned') : Smsgateway::find($targetGatewayId)?->name;

        return response()->json([
            'message' => __(':count Sender IDs assigned to :gateway successfully.', [
                'count' => $count,
                'gateway' => $gatewayName
            ]),
            'redirect' => route('admin.senderids.index')
        ]);
    }

    /**
     * Get list of all users assigned to a specific sender ID
     */
    public function assignedUsers(Request $request)
    {
        $sender = trim((string) $request->get('sender'));
        if ($sender === '') {
            return response()->json(['error' => __('Sender ID is required')], 422);
        }

        $records = Senderid::with(['user', 'gateway'])
            ->where('sender', $sender)
            ->latest('id')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'user_id' => $s->user_id,
                    'user_name' => optional($s->user)->name ?? __('N/A'),
                    'username' => optional($s->user)->username ?? '',
                    'phone' => optional($s->user)->phone ?? __('N/A'),
                    'email' => optional($s->user)->email ?? __('N/A'),
                    'gateway_name' => optional($s->gateway)->name ?? __('Unassigned'),
                    'status' => (bool) $s->status,
                    'created_at' => $s->created_at ? $s->created_at->format('d M, Y') : '',
                ];
            });

        return response()->json([
            'sender' => $sender,
            'total_users' => $records->count(),
            'users' => $records,
        ]);
    }

    /**
     * Quick / Bulk Assign Customer to selected Sender IDs
     */
    public function assignUser(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:senderids,id',
            'user_id' => 'nullable',
        ]);

        $rawUserId = $request->input('user_id');
        $targetUserId = (!empty($rawUserId) && (int) $rawUserId > 0) ? (int) $rawUserId : null;

        if ($targetUserId !== null) {
            $userExists = User::where('id', $targetUserId)->exists();
            if (!$userExists) {
                return response()->json(['error' => __('Invalid user selected.')], 422);
            }
        }

        Senderid::whereIn('id', $request->ids)->update([
            'user_id' => $targetUserId,
        ]);

        $count = count($request->ids);
        $targetUser = $targetUserId ? User::find($targetUserId) : null;
        $userName = $targetUser
            ? ($targetUser->name . ' (@' . ($targetUser->username ?: ('user' . $targetUser->id)) . ')')
            : __('Unassigned / Unused');

        return response()->json([
            'message' => __(':count Sender ID(s) assigned to :user successfully.', [
                'count' => $count,
                'user' => $userName,
            ]),
            'redirect' => route('admin.senderids.index'),
        ]);
    }

    /**
     * Check if a sender ID string is already assigned to any user(s)
     */
    public function checkAssignment(Request $request)
    {
        $sender = trim((string) $request->get('sender'));
        $excludeId = $request->get('exclude_id');

        if ($sender === '') {
            return response()->json([
                'assigned' => false,
                'count' => 0,
                'users' => [],
            ]);
        }

        $query = Senderid::with(['user:id,name,username,phone,email', 'gateway:id,name'])
            ->where('sender', $sender)
            ->whereNotNull('user_id');

        if (!empty($excludeId)) {
            $query->where('id', '!=', (int) $excludeId);
        }

        $records = $query->get();

        $users = $records->map(function ($s) {
            return [
                'sender_id_record' => $s->id,
                'user_id' => $s->user_id,
                'name' => optional($s->user)->name ?? __('Unknown'),
                'username' => optional($s->user)->username ?? '',
                'phone' => optional($s->user)->phone ?? '',
                'email' => optional($s->user)->email ?? '',
                'gateway_name' => optional($s->gateway)->name ?? __('Unassigned'),
                'type' => $s->type,
            ];
        })->values();

        return response()->json([
            'assigned' => $users->isNotEmpty(),
            'count' => $users->count(),
            'users' => $users,
        ]);
    }
}
