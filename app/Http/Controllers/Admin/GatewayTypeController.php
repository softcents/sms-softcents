<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\GatewayType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GatewayTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = GatewayType::latest()->paginate(10);
        return view('admin.gateways-types.index', compact('types'));
    }

    public function acnooFilter(Request $request)
    {
        $contacts = GatewayType::when($request->has('search'), function ($q) use ($request) {
                        $q->where(function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%');
                        });
                    })
                    ->latest()
                    ->paginate(10);

        return response()->json([
            'data' => view('admin.gateways-types.datas', compact('contacts'))->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'inputs' => 'required|array',
            'status' => 'required|boolean',
            'name' => 'nullable|string|max:250',
            'driver' => 'nullable|string|max:250',
        ]);

        GatewayType::create($request->except('inputs') + [
            'inputs' => [
                'names' => $request->inputs,
                'labels' => $request->labels,
            ]
        ]);

        return response()->json([
            'message' => __('Gateway type created successfully'),
            'redirect' => route('admin.gateways-types.index')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $type = GatewayType::findOrFail($id);
        return view('admin.gateways-types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
    {

        $request->validate([
            'inputs' => 'required|array',
            'status' => 'required|boolean',
            'name' => 'nullable|string|max:250',
            'driver' => 'nullable|string|max:250',
        ]);

        $gatewayType = GatewayType::findOrFail($id);


        $gatewayType->update($request->except('inputs') + [
            'inputs' => [
                'names' => $request->inputs,
                'labels' => $request->labels,
            ]
        ]);

        return response()->json([
            'message'   => __('Gateway Type updated successfully'),
            'redirect'  => route('admin.gateways-types.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gatewayType = GatewayType::findOrFail($id);

        $gatewayType->delete();

        return response()->json([
            'message'   => __('Contact deleted successfully'),
            'redirect'  => route('admin.gateways-types.index')
        ]);
    }



   
}
