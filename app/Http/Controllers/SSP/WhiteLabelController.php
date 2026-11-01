<?php

namespace App\Http\Controllers\SSP;

use App\Http\Controllers\Controller;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;

class WhiteLabelController extends Controller
{
    use HasUploader;

    public function index()
    {
        $ssp = auth()->user();
        return view('ssp.white-label', compact('ssp'));
    }

    public function update(Request $request)
    {
        $ssp = auth()->user();

        $request->validate([
            'company_name' => 'nullable|string|max:191',
            'white_label_title' => 'nullable|string|max:191',
            'white_label_phone' => 'nullable|string|max:50',
            'white_label_email' => 'nullable|email|max:100',
            'white_label_footer' => 'nullable|string|max:500',
            'custom_domain' => 'nullable|string|max:191|unique:users,custom_domain,' . $ssp->id,
            'white_label_logo' => 'nullable|image|max:2048',
            'white_label_favicon' => 'nullable|image|max:1024',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'white_label_title' => $request->white_label_title,
            'white_label_phone' => $request->white_label_phone,
            'white_label_email' => $request->white_label_email,
            'white_label_footer' => $request->white_label_footer,
            'custom_domain' => $request->custom_domain ? strtolower(trim(str_replace(['http://', 'https://', '/'], '', $request->custom_domain))) : null,
        ];

        if ($request->hasFile('white_label_logo')) {
            $data['white_label_logo'] = $this->upload($request, 'white_label_logo', $ssp->white_label_logo);
        }

        if ($request->hasFile('white_label_favicon')) {
            $data['white_label_favicon'] = $this->upload($request, 'white_label_favicon', $ssp->white_label_favicon);
        }

        $ssp->update($data);

        return back()->with('success', __('White Label branding updated successfully.'));
    }
}
