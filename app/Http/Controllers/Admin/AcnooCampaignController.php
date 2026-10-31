<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class AcnooCampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Campaign::when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('total_numbers', 'like', '%' . $request->search . '%')
                        ->orWhere('numbers', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->latest();

        if ($request->ajax()) {
            $campaigns = $campaigns->get();
            return response()->json([
                'data' => view('admin.campaigns.datas', compact('campaigns'))->render()
            ]);
        }

        $campaigns = $campaigns->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }


    public function create()
    {
        return view('admin.campaigns.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'status' => 'required|integer|in:0,1',
            'numbers' => 'required|string|min:5',
            'description' => 'required|string|max:500',
        ]);

        $remove_space = str_replace(' ', '', $request->numbers); // Numbers without spaces
        $numbers_array = explode(',', $remove_space);
        $unique_numbers = array_unique($numbers_array);

        Campaign::create($request->except('numbers') + [
            'total_numbers' => count($unique_numbers),
            'numbers' => implode(',', $unique_numbers),
        ]);

        return response()->json([
            'message' => __('Campaign created successfully'),
            'redirect' => route('admin.campaigns.index')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'status' => 'required|integer|in:0,1',
            'numbers' => 'required|string|min:5',
            'description' => 'required|string|max:500',
        ]);

        $remove_space = str_replace(' ', '', $request->numbers); // Numbers without spaces
        $numbers_array = explode(',', $remove_space);
        $unique_numbers = array_unique($numbers_array);

        $campaign->update($request->except('numbers') + [
            'total_numbers' => count($unique_numbers),
            'numbers' => implode(',', $unique_numbers),
        ]);

        return response()->json([
            'message' => __('Campaign updated successfully'),
            'redirect' => route('admin.campaigns.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return response()->json([
            'message' => __('Campaign deleted successfully'),
            'redirect' => route('admin.campaigns.index')
        ]);
    }


    public function status(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update(['status' => $request->status]);
        return response()->json(['message' => 'Campaign ']);
    }

    public function deleteAll(Request $request)
    {
        Campaign::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Campaign deleted successfully'),
            'redirect' => route('admin.campaigns.index')
        ]);
    }
}
