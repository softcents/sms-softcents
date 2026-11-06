<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Senderid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController extends Controller
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
                'data' => view('admin.sms.datas', compact('senderids'))->render()
            ]);
        }

        $senderids = $senderids->paginate(10);
        return view('admin.sms.index', compact('senderids'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'user')->select('id', 'name', 'phone')->latest()->get();
        return view('admin.sms.create', compact('users'));
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
        ]);

        $data = $request->all();
        if (empty($data['user_id'])) {
            $data['user_id'] = null;
        }

        Senderid::create($data);

        return response()->json([
            'message' => __('Senderid created successfully'),
            'redirect' => route('admin.sms.index')
        ]);
    }

    public function edit(Senderid $senderid)
    {
        $users = User::where('role', 'user')->select('id', 'name', 'phone')->latest()->get();
        return view('admin.sms.edit', compact('senderid', 'users'));
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
        ]);

        $data = $request->all();
        if (empty($data['user_id'])) {
            $data['user_id'] = null;
        }

        $senderid->update($data);

        return response()->json([
            'message' => __('Senderid updated successfully'),
            'redirect' => route('admin.sms.index')
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
            'redirect' => route('admin.sms.index')
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
            'redirect' => route('admin.sms.index')
        ]);
    }
}
