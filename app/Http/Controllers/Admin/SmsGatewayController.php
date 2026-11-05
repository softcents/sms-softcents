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
        $gateways = Smsgateway::with('type:id,name')->orderByRaw("CAST(NULLIF(priority, '') AS INTEGER) ASC NULLS LAST")
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
            'params.*' => 'nullable|string',
            'priority' => 'nullable|integer',
            'name' => 'required|string|unique:smsgateways,name',
            'type_id' => 'required|exists:gateway_types,id',
            'non_masking_rate' => 'nullable|numeric|min:0',
            'masking_rate' => 'nullable|numeric|min:0',
        ]);

        $type = GatewayType::findOrFail($request->type_id);

        Smsgateway::create($request->all() + [
            'driver' => $type->driver,
            'namespace' => $type->namespace,
            'non_masking_rate' => $request->filled('non_masking_rate') ? $request->non_masking_rate : 0.2400,
            'masking_rate' => $request->filled('masking_rate') ? $request->masking_rate : 0.4500,
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
            'params.*' => 'nullable|string',
            'priority' => 'nullable|integer',
            'type_id' => 'required|exists:gateway_types,id',
            'name' => 'required|string|unique:smsgateways,name,'.$id,
            'non_masking_rate' => 'nullable|numeric|min:0',
            'masking_rate' => 'nullable|numeric|min:0',
        ]);

        $gateway = Smsgateway::findOrFail($id);
        $type = GatewayType::findOrFail($request->type_id);

        $gateway->update($request->all() + [
            'driver' => $type->driver,
            'namespace' => $type->namespace,
            'non_masking_rate' => $request->filled('non_masking_rate') ? $request->non_masking_rate : ($gateway->non_masking_rate ?? 0.2400),
            'masking_rate' => $request->filled('masking_rate') ? $request->masking_rate : ($gateway->masking_rate ?? 0.4500),
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

    /**
     * Check real-time balance of a specific SMS gateway
     */
    public function checkBalance(string $id)
    {
        $gateway = Smsgateway::findOrFail($id);
        $result = $this->resolveGatewayBalance($gateway);

        return response()->json(array_merge($result, [
            'id' => $gateway->id,
            'name' => $gateway->name,
        ]));
    }

    /**
     * Check real-time balance of all SMS gateways
     */
    public function checkAllBalances()
    {
        $gateways = Smsgateway::where('status', 1)->get();
        $results = [];

        foreach ($gateways as $gateway) {
            $results[$gateway->id] = array_merge($this->resolveGatewayBalance($gateway), [
                'id' => $gateway->id,
                'name' => $gateway->name,
            ]);
        }

        return response()->json([
            'success' => true,
            'gateways' => $results,
        ]);
    }

    /**
     * Resolve balance using the matching SMS Gateway driver
     */
    private function resolveGatewayBalance(Smsgateway $gateway): array
    {
        $driver = strtolower(trim($gateway->driver ?? ''));
        $namespace = trim($gateway->namespace ?? '');

        if ($driver === 'whiteinfobd' || str_contains($namespace, 'WhiteInfoBD')) {
            return \App\SmsGateways\WhiteInfoBDSms::check_balance($gateway);
        }

        if ($driver === 'panelsms' || str_contains($namespace, 'PanelSms')) {
            return \App\SmsGateways\PanelSms::check_balance($gateway);
        }

        if ($driver === 'syssms' || str_contains($namespace, 'SysSms')) {
            return \App\SmsGateways\SysSms::check_balance($gateway);
        }

        if ($driver === 'anbernet' || str_contains($namespace, 'Anbernet')) {
            return \App\SmsGateways\AnbernetSms::check_balance($gateway);
        }

        if (class_exists($namespace) && method_exists($namespace, 'check_balance')) {
            return $namespace::check_balance($gateway);
        }

        return [
            'success'   => false,
            'balance'   => null,
            'formatted' => 'Unsupported',
            'message'   => "Balance check is not supported for driver: {$driver}",
        ];
    }
}
