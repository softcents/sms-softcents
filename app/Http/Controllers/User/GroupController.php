<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groups = Group::where('user_id', auth()->id())
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->latest();

        if ($request->ajax()) {
            $groups = $groups->get();
            return response()->json([
                'data' => view('users.groups.datas', compact('groups'))->render()
            ]);
        }

        $groups = $groups->paginate(10);
        return view('users.groups.index', compact('groups'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'status' => 'required|boolean',
        ]);

        Group::create($request->all() + [
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => __('Group created successfully'),
            'redirect' => route('users.groups.index')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        abort_if($group->user_id != auth()->id(), 404);
        return view('users.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'status' => 'required|boolean',
        ]);

        abort_if($group->user_id != auth()->id(), 404);
        $group->update($request->all());

        return response()->json([
            'message' => __('Group updated successfully'),
            'redirect' => route('users.groups.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        abort_if($group->user_id != auth()->id(), 404);

        $group->delete();
        return response()->json([
            'message'   => __('Group deleted successfully'),
            'redirect'  => route('users.groups.index')
        ]);
    }

    public function status(Request $request, Group $group)
    {
        abort_if($group->user_id != auth()->id(), 404);
        $group->update(['status' => $request->status]);
        return response()->json(['message' => 'Group status updated.']);
    }

    public function deleteAll(Request $request)
    {
        Group::where('user_id', auth()->id())->whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Groups deleted successfully'),
            'redirect' => route('users.groups.index')
        ]);
    }
}
