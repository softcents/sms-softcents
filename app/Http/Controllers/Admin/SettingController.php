<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HasUploader;
use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:settings-read')->only('index');
        $this->middleware('permission:settings-update')->only('update');
    }

    public function index()
    {
        $general = Option::where('key','general')->first();
        return view ('admin.settings.general.index',compact('general'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'email' => 'nullable|email|max:50',
            'phone' => 'nullable|string|max:20',
            'copyright' => 'nullable|string|max:50',
            'develop_by' => 'nullable|string|max:50',
            'footer_desc' => 'nullable|string|max:250',
            'favicon' => 'nullable|image',
            'logo' => 'nullable|image',
            'front_logo' => 'nullable|image',
        ]);

        $general = Option::findOrFail($id);
        Cache::forget($general->key);
        $general->update([
            'value' => $request->except('_token','_method','logo','favicon', 'front_logo') + [
                    'logo' => $request->logo ? $this->upload($request, 'logo', $general->logo) : $general->value['logo'],
                    'favicon' => $request->favicon ? $this->upload($request, 'favicon', $general->favicon) : $general->value['favicon'],
                    'front_logo' => $request->front_logo ? $this->upload($request, 'front_logo', $general->front_logo) : $general->value['front_logo']
                ]
        ]);

        return response()->json([
            'message'   => __('General Setting updated successfully'),
            'redirect'  => route('admin.settings.index')
        ]);
    }
}
