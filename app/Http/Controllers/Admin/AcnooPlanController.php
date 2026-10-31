<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class AcnooPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:plans-read')->only('index');
        $this->middleware('permission:plans-create')->only('create', 'store');
        $this->middleware('permission:plans-update')->only('edit', 'update','status');
        $this->middleware('permission:plans-delete')->only('destroy','deleteAll');
    }

    public function index(Request $request)
    {
        $plans = Plan::latest()->paginate(10);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'price' => 'nullable|numeric',
            'status' => 'nullable|integer',
            'duration' => 'required|string',
            'title' => 'required|string|max:255',
            'masking_rate' => 'required|numeric|gt:0',
            'non_masking_rate' => 'required|numeric|gt:0',
        ]);

        Plan::create($request->all());

        return response()->json([
            'message' => __('Plan created successfully'),
            'redirect' => route('admin.plans.index')
        ]);
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'price' => 'nullable|numeric',
            'status' => 'nullable|integer',
            'duration' => 'required|string',
            'title' => 'required|string|max:255',
            'masking_rate' => 'required|numeric|gt:0',
            'non_masking_rate' => 'required|numeric|gt:0',
        ]);

        $plan->update($request->all());

        return response()->json([
            'message' => __('Plan updated successfully'),
            'redirect' => route('admin.plans.index')
        ]);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->json([
            'message'   => __('Plan deleted successfully'),
            'redirect'  => route('admin.plans.index')
        ]);
    }

    public function status(Request $request, Plan $plan)
    {
        $plan->update(['status' => $request->status]);
        return response()->json(['message' => 'Subscription Plan']);
    }

    public function deleteAll(Request $request)
    {
        Plan::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Subscription plan deleted successfully'),
            'redirect' => route('admin.plans.index')
        ]);
    }
}
