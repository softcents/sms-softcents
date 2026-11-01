<?php

namespace App\Http\Controllers\SP;

use App\Helpers\HasUploader;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Gateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    use HasUploader;

    public function index()
    {
        $gateways = Gateway::where('user_id', auth()->id())->latest()->get();
        return view('sp.gateways.index', compact('gateways'));
    }

    public function create()
    {
        $currencies = Currency::all();
        return view('sp.gateways.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'charge' => 'nullable|numeric|min:0',
            'instructions' => 'nullable|string',
            'is_manual' => 'required|boolean',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'charge' => $request->charge ?? 0,
            'instructions' => $request->instructions,
            'is_manual' => $request->is_manual,
            'status' => $request->status,
            'accept_img' => $request->accept_img ?? 1,
            'phone_required' => $request->phone_required ?? 1,
            'currency_id' => $request->currency_id ?? Currency::first()->id ?? 1,
            'user_id' => auth()->id(),
            'namespace' => $request->namespace ?? 'App\Library\CustomGateway',
            'data' => $request->gateway_data ?? [],
            'manual_data' => $request->manual_data ?? [],
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->upload($request, 'image');
        }

        Gateway::create($data);

        return redirect()->route('sp.gateways.index')->with('success', __('Payment gateway added successfully.'));
    }

    public function edit(Gateway $gateway)
    {
        abort_if($gateway->user_id !== auth()->id(), 403);
        $currencies = Currency::all();
        return view('sp.gateways.edit', compact('gateway', 'currencies'));
    }

    public function update(Request $request, Gateway $gateway)
    {
        abort_if($gateway->user_id !== auth()->id(), 403);

        $request->validate([
            'name' => 'required|string|max:191',
            'charge' => 'nullable|numeric|min:0',
            'instructions' => 'nullable|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'charge' => $request->charge ?? 0,
            'instructions' => $request->instructions,
            'status' => $request->status,
            'phone_required' => $request->phone_required ?? 1,
            'currency_id' => $request->currency_id ?? $gateway->currency_id,
            'data' => $request->gateway_data ?? $gateway->data,
            'manual_data' => $request->manual_data ?? $gateway->manual_data,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->upload($request, 'image', $gateway->image);
        }

        $gateway->update($data);

        return redirect()->route('sp.gateways.index')->with('success', __('Payment gateway updated successfully.'));
    }

    public function destroy(Gateway $gateway)
    {
        abort_if($gateway->user_id !== auth()->id(), 403);
        $gateway->delete();

        return redirect()->route('sp.gateways.index')->with('success', __('Payment gateway deleted successfully.'));
    }
}
