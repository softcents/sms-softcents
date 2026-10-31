<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AcnooFaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $faqs = Faq::when($request->has('search'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where('question', 'like', '%' . $request->search . '%')
                    ->orWhere('answer', 'like', '%' . $request->search . '%');
            });
        })
            ->latest();

        if ($request->ajax()) {
            $faqs = $faqs->get();

            return response()->json([
                'data' => view('admin.website-setting.faqs.datas', compact('faqs'))->render()
            ]);
        }

        $faqs = $faqs->paginate(10);
        return view('admin.website-setting.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.website-setting.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'status' => 'numeric',
        ]);

        $data = $request->all();
        $data['status'] = $request->filled('status') ? $request->input('status') : 1;
        Faq::create($data);

        return response()->json([
            'message' => __('Faqs created successfully'),
            'redirect' => route('admin.faqs.index')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        return view('admin.website-setting.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'status' => 'integer',
        ]);

        $data = $request->all();
        $data['status'] = $request->filled('status') ? $request->input('status') : 1;
        $faq->update($data);

        return response()->json([
            'message' => __('Faq updated successfully'),
            'redirect' => route('admin.faqs.index')
        ]);
    }

    public function status(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->update(['status' => $request->status]);
        return response()->json(['message' => 'Faq ']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json([
            'message' => __('Faq deleted successfully.'),
            'redirect' => route('admin.faqs.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Faq::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Faqs deleted successfully'),
            'redirect' => route('admin.faqs.index')
        ]);
    }
}
