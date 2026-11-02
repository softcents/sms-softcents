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
        $senderids = Senderid::with('user')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('sender', 'like', '%' . $request->search . '%')
                        ->orWhere('type', 'like', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%')
                              ->orWhere('email', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest();

        if ($request->ajax()) {
            $senderids = $senderids->get();
            return response()->json([
                'data' => view('admin.senderids.datas', compact('senderids'))->render()
            ]);
        }

        $senderids = $senderids->paginate(10);
        return view('admin.senderids.index', compact('senderids'));
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
            'gateway_id' => 'required|exists:smsgateways,id',
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
            'gateway_id' => 'required|exists:smsgateways,id',
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
        return response()->json(['message' => 'Senderid status updated.']);
    }

    public function deleteAll(Request $request)
    {
        Senderid::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Senderid deleted successfully'),
            'redirect' => route('admin.senderids.index')
        ]);
    }
}
