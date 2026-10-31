<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HasUploader;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AcnooWebSettingController extends Controller
{
    use HasUploader;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page_data = get_option('manage-pages');
        return view('admin.website-setting.manage-pages', compact('page_data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $key)
    {
        $option = Option::where('key', 'manage-pages')->first();
        Option::updateOrCreate(
            ['key' => 'manage-pages'],
            ['value' => [
                'headings' => $request->except('_token', '_method', 'card_icons','slider_image','faqs_image', 'access-section_image','feature_section_logo','faq_image', 'contact_section_logo', 'about_icon_1', 'about_icon_2', 'about_icon_3', 'contact_icons', 'branch_icons', 'contact_img', 'contact_section_img', 'footer_socials_icons', 'sidebar_socials_icons','contact_us_image','contact_us_option_icon','contact_us_option_icon2','contact_us_branch_icon','our_company_image','our_mission_image','our_vision_image','body_image'),

                'contact_img' => $request->contact_img ? $this->upload($request, 'contact_img') : $option->value['contact_img'] ?? null,
                'contact_us_option_icon' => $request->contact_us_option_icon ? $this->upload($request, 'contact_us_option_icon') : $option->value['contact_us_option_icon'] ?? null,
                'contact_us_option_icon2' => $request->contact_us_option_icon2 ? $this->upload($request, 'contact_us_option_icon2') : $option->value['contact_us_option_icon2'] ?? null,
                'contact_us_option_icon3' => $request->contact_us_option_icon3 ? $this->upload($request, 'contact_us_option_icon3') : $option->value['contact_us_option_icon3'] ?? null,
                'contact_us_image' => $request->contact_us_image ? $this->upload($request, 'contact_us_image') : $option->value['contact_us_image'] ?? null,
                'contact_us_branch_icon' => $request->contact_us_branch_icon ? $this->upload($request, 'contact_us_branch_icon') : $option->value['contact_us_branch_icon'] ?? null,
                'slider_image' => $request->slider_image ? $this->upload($request, 'slider_image') : $option->value['slider_image'] ?? null,
                'faq_image' => $request->faq_image ? $this->upload($request, 'faq_image') : $option->value['faq_image'] ?? null,
                'access_section_image' => $request->access_section_image ? $this->upload($request, 'access_section_image') : $option->value['access_section_image'] ?? null,
                'about_icon_1' => $request->about_icon_1 ? $this->upload($request, 'about_icon_1') : $option->value['about_icon_1'] ?? null,
                'about_icon_2' => $request->about_icon_2 ? $this->upload($request, 'about_icon_2') : $option->value['about_icon_2'] ?? null,
                'about_icon_3' => $request->about_icon_3 ? $this->upload($request, 'about_icon_3') : $option->value['about_icon_3'] ?? null,
                'contact_section_img' => $request->contact_section_img ? $this->upload($request, 'contact_section_img') : $option->value['contact_section_img'] ?? null,
                'about_section_img' => $request->about_section_img ? $this->upload($request, 'about_section_img') : $option->value['about_section_img'] ?? null,
                'faqs_image' => $request->faqs_image ? $this->upload($request, 'faqs_image') : $option->value['faqs_image'] ?? null,
                'our_company_image' => $request->our_company_image ? $this->upload($request, 'our_company_image') : $option->value['our_company_image'] ?? null,
                'our_mission_image' => $request->our_mission_image ? $this->upload($request, 'our_mission_image') : $option->value['our_mission_image'] ?? null,
                'our_vision_image' => $request->our_vision_image ? $this->upload($request, 'our_vision_image') : $option->value['our_vision_image'] ?? null,

                'card_icons' => $request->card_icons ? $this->multipleUpload($request, 'card_icons') : $option->value['card_icons'] ?? null,
                'branch_icons' => $request->branch_icons ? $this->multipleUpload($request, 'branch_icons') : $option->value['branch_icons'] ?? null,
                'contact_icons' => $request->contact_icons ? $this->multipleUpload($request, 'contact_icons') : $option->value['contact_icons'] ?? null,
                'footer_socials_icons' => $request->footer_socials_icons ? $this->multipleUpload($request, 'footer_socials_icons') : $option->value['footer_socials_icons'] ?? null,
                'sidebar_socials_icons' => $request->sidebar_socials_icons ? $this->multipleUpload($request, 'sidebar_socials_icons') : $option->value['sidebar_socials_icons'] ?? null,
                'feature_section_logo' => $request->feature_section_logo ? $this->multipleUpload($request, 'feature_section_logo') : $option->value['feature_section_logo'] ?? null,
                'contact_section_logo' => $request->contact_section_logo ? $this->multipleUpload($request, 'contact_section_logo') : $option->value['contact_section_logo'] ?? null,
                'card_image' => $request->card_image ? $this->multipleUpload($request, 'card_image') : $option->value['card_image'] ?? null,
                'body_image' => $request->body_image ? $this->upload($request, 'body_image') : $option->value['body_image'] ?? null,

            ]
        ]);

        Cache::forget('manage-pages');
        return response()->json(__('Pages updated successfully.'));
    }

}
