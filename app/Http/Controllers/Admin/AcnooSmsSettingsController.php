<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AcnooSmsSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sms-settings-read')->only('index');
        $this->middleware('permission:sms-settings-update')->only('update');
    }

    public function index()
    {
        return view ('admin.settings.sms-settings.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unicode_char_per_sms' => 'required|numeric',
            'long_unicode_char_per_sms' => 'required|numeric',
            'text_char_per_sms' => 'required|numeric',
            'long_text_char_per_sms' => 'required|numeric',
        ]);

        Option::updateOrCreate(['key' => 'sms-settings'], [
            'value' => $request->except('_token', '_method')
        ]);

        Cache::forget('sms-settings');

        return response()->json(__('Sms setting updated successfully'));
    }
}
