<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class AcnooNewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:newsletters-read')->only('index');
        $this->middleware('permission:newsletters-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $newsletters = Newsletter::when($request->has('search'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->search . '%');
            });
        })
            ->latest();

        if ($request->ajax()) {
            $newsletters = $newsletters->get();

            return response()->json([
                'data' => view('admin.website-setting.newsletters.datas', compact('newsletters'))->render()
            ]);
        }

        $newsletters = $newsletters->paginate(10);
        return view('admin.website-setting.newsletters.index', compact('newsletters'));
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        return response()->json([
            'message'   => __('Newsletter deleted successfully'),
            'redirect'  => route('admin.newsletters.index')
        ]);
    }

    public function deleteAll(Request $request)
    {
        Newsletter::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message'   => __('Newsletter deleted successfully'),
            'redirect'  => route('admin.newsletters.index')
        ]);
    }

}
