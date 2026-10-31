<?php

namespace App\Http\Controllers\Admin;

use App\Models\Smsgateway;
use App\Models\GatewayType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsGatewayController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:smsgateways-create')->only('create', 'store');
        $this->middleware('permission:smsgateways-read')->only('index', 'show');
        $this->middleware('permission:smsgateways-update')->only('edit', 'update');
        $this->middleware('permission:smsgateways-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $gateways = Smsgateway::with('type:id,name')->orderByRaw('CAST(priority AS SIGNED)')
                    ->when($request->has('search'), function ($q) use ($request) {
                        $q->where(function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('priority', 'like', '%' . $request->search . '%');
                        });
                    })
                    ->latest();

        if ($request->ajax()) {
            $gateways = $gateways->get();

            return response()->json([
                'data' => view('admin.sms-gateways.datas', compact('gateways'))->render()
            ]);
        }

        $gateways = $gateways->paginate(10);
        return view('admin.sms-gateways.index', compact('gateways'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = GatewayType::latest()->get();
        return view('admin.sms-gateways.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|integer',
            'params' => 'required|array',
            'params.*' => 'required|string',
            'priority' => 'nullable|integer',
            'name' => 'required|string|unique:smsgateways,name',
            'type_id' => 'required|exists:gateway_types,id',
        ]);

        $type = GatewayType::findOrFail($request->type_id);

        Smsgateway::create($request->all() + [
            'driver' => $type->driver,
            'namespace' => $type->namespace,
        ]);

        return response()->json([
            'message' => __('Sms gateway created successfully.'),
            'redirect' => route('admin.sms-gateways.index')
        ]);
    }

    public function show(string $id)
    {
        $gateway = Smsgateway::findOrFail($id);
        $gateway->update(['status' => !$gateway->status]);
        return response()->json(['message' => 'Sms gateway']);
    }

    public function edit(string $id)
    {
        $gateway = Smsgateway::findOrFail($id);
        $types = GatewayType::latest()->get();
        return view('admin.sms-gateways.edit', compact('gateway', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|integer',
            'params' => 'required|array',
            'params.*' => 'required|string',
            'priority' => 'nullable|integer',
            'type_id' => 'required|exists:gateway_types,id',
            'name' => 'required|string|unique:smsgateways,name,'.$id
        ]);

        $gateway = Smsgateway::findOrFail($id);
        $type = GatewayType::findOrFail($request->type_id);

        $gateway->update($request->all() + [
            'driver' => $type->driver,
            'namespace' => $type->namespace,
        ]);

        return response()->json([
            'message' => __('Sms gateway updated successfully.'),
            'redirect' => route('admin.sms-gateways.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gateway = Smsgateway::findOrFail($id);
        $gateway->delete();

        return response()->json([
            'message' => __('Sms gateway deleted successfully.'),
            'redirect' => route('admin.sms-gateways.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Smsgateway::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('SMS gateway deleted successfully.'),
            'redirect' => route('admin.sms-gateways.index')
        ]);
    }
}
