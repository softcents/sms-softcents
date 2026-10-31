<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:notice-read')->only('index');
        $this->middleware('permission:notice-update')->only('update');
    }

    public function index()
    {
        $notice = Option::where('key', 'notice')->first();
        return view('admin.settings.notice.index', compact('notice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'status' => 'required|integer',
            'description' => 'required|string|max:2000',
        ]);

        Option::updateOrCreate(['key' => 'notice'], [
            'value' => $request->except('_token', '_method')
        ]);

        Cache::forget('notice');

        return response()->json(__('Notice alert updated successfully'));
    }
}
