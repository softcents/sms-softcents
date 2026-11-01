<?php

namespace App\Http\Controllers\SP;

use App\Helpers\ResellerMailHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MailSettingController extends Controller
{
    public function index()
    {
        $sp = auth()->user();
        $mailSettings = $sp->mail_settings ?? [];
        return view('sp.mail-settings', compact('sp', 'mailSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'host' => 'required|string|max:191',
            'port' => 'required|numeric',
            'username' => 'required|string|max:191',
            'password' => 'required|string|max:191',
            'encryption' => 'nullable|string|in:tls,ssl,none',
            'from_address' => 'required|email|max:191',
            'from_name' => 'required|string|max:191',
        ]);

        $sp = auth()->user();
        $sp->update([
            'mail_settings' => [
                'driver' => 'smtp',
                'host' => $request->host,
                'port' => $request->port,
                'username' => $request->username,
                'password' => $request->password,
                'encryption' => $request->encryption,
                'from_address' => $request->from_address,
                'from_name' => $request->from_name,
            ]
        ]);

        return redirect()->route('sp.mail-settings.index')->with('success', __('Mail settings updated successfully!'));
    }

    public function sendTestMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $sp = auth()->user();
        if (empty($sp->mail_settings)) {
            return back()->with('error', __('Please configure and save your SMTP settings first!'));
        }

        $result = ResellerMailHelper::sendTestMail($sp->mail_settings, $request->test_email);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }
}
