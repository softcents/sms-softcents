<?php

namespace App\Http\Controllers\SSP;

use App\Helpers\ResellerMailHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MailSettingController extends Controller
{
    public function index()
    {
        $ssp = auth()->user();
        $mailSettings = $ssp->mail_settings ?? [];
        return view('ssp.mail-settings', compact('ssp', 'mailSettings'));
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

        $ssp = auth()->user();
        $ssp->update([
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

        return redirect()->route('ssp.mail-settings.index')->with('success', __('Mail settings updated successfully!'));
    }

    public function sendTestMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $ssp = auth()->user();
        if (empty($ssp->mail_settings)) {
            return back()->with('error', __('Please configure and save your SMTP settings first!'));
        }

        $result = ResellerMailHelper::sendTestMail($ssp->mail_settings, $request->test_email);

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }
}
