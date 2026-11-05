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
            ->when($request->filled('type'), function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($request->filled('status'), function ($q) use ($status) {
                $q->where('status', (int) $status);
            })
            ->latest('id');

        // Total count of senderids & metrics
        $totalSenderidsCount = Senderid::count();
        $unassignedCount = Senderid::where(function ($q) {
            $q->whereNull('gateway_id')->orWhere('gateway_id', 0);
        })->count();
        $maskingCount = Senderid::where('type', 'Masking')->count();
        $nonMaskingCount = Senderid::where('type', 'Non-Masking')->count();
        $activeCount = Senderid::where('status', 1)->count();
        $deactiveCount = Senderid::where('status', 0)->count();

        // Gateway statistics (Name + count of sender IDs)
        $gateways = Smsgateway::select('id', 'name', 'status')
            ->withCount('senderids')
            ->orderBy('name')
            ->get();

        $gatewayList = Smsgateway::select('id', 'name')->latest()->get();

        $senderids = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'data' => view('admin.senderids.datas', compact('senderids'))->render(),
                'pagination' => (string) $senderids->links('pagination::bootstrap-5'),
                'total' => $senderids->total(),
            ]);
        }

        return view('admin.senderids.index', compact(
            'senderids',
            'gateways',
            'gatewayList',
            'totalSenderidsCount',
            'unassignedCount',
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
        $users = User::where('role', 'user')->select('id', 'name', 'phone')->latest()->get();
        $gatewayList = Smsgateway::latest()->select("id", "name")->get();
        return view('admin.senderids.create', compact('users','gatewayList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sender' => 'required|string|max:500',
            'type' => 'required|string|in:Non-Masking,Masking',
            'status' => 'required|integer|in:0,1',
            'gateway_id' => 'required',
            'rate' => 'nullable|numeric|min:0'
        ]);
        
        Senderid::create($request->all());

        return response()->json([
            'message' => __('Senderid created successfully'),
            'redirect' => route('admin.senderids.index')
        ]);
    }

    public function edit(Senderid $senderid)
    {
        $users = User::where('role', 'user')->select('id', 'name', 'phone')->latest()->get();
        $gatewayList = Smsgateway::latest()->select("id", "name")->get();
        return view('admin.senderids.edit', compact('senderid', 'users','gatewayList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Senderid $senderid)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sender' => 'required|string|max:500',
            'type' => 'required|string|in:Non-Masking,Masking',
            'status' => 'required|integer|in:0,1',
            'gateway_id' => 'required',
            'rate' => 'nullable|numeric|min:0'
        ]);

        $senderid->update($request->all());

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
}
