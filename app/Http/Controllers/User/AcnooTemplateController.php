<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;

class AcnooTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templatesQuery = Template::where('user_id', auth()->id())
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                   ->orwhere('text', 'like', '%' . $request->search . '%');
            })
            ->latest();

        if ($request->ajax()) {
            $templates = $templatesQuery->paginate(10);
            return response()->json([
                'data' => view('users.templates.datas', compact('templates'))->render()
            ]);
        }

        $templates = $templatesQuery->paginate(10);
        return view('users.templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'status' => 'required|boolean',
            'name' => 'required|string|max:250',
        ]);

        Template::create($request->all() + [
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => __('Template created successfully'),
            'redirect' => route('users.templates.index')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        abort_if($template->user_id != auth()->id(), 404);

        $request->validate([
            'name' => 'required|string|max:250',
            'text' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $template->update($request->all());

        return response()->json([
            'message' => __('Template updated successfully'),
            'redirect' => route('users.templates.index')
        ]);
    }

    public function destroy(Template $template)
    {
        abort_if($template->user_id != auth()->id(), 404);
        $template->delete();
        return response()->json([
            'message'   => __('Template deleted successfully'),
            'redirect'  => route('users.templates.index')
        ]);
    }

    public function status(Request $request, Template $template)
    {
        abort_if($template->user_id != auth()->id(), 404);
        $template->update(['status' => $request->status]);
        return response()->json(['message' => 'Template status updated.']);
    }

    public function deleteAll(Request $request)
    {
        Template::where('user_id', auth()->id())->whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Templates deleted successfully'),
            'redirect' => route('users.templates.index')
        ]);
    }

    public function getTemplates()
    {
        $templates = Template::where('user_id', auth()->id())->latest()->get();
        return response()->json([
            'data' => view('users.templates.popup-list', compact('templates'))->render()
        ]);
    }
}
