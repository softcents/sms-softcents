<?php

use App\Models\Currency;
use App\Models\Gateway;
use App\Models\Option;
use App\Models\Senderid;
use App\Models\Smsgateway;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;

function cache_remember(string $key, callable $callback, int $ttl = 1800): mixed
{
    return cache()->remember($key, env('CACHE_LIFETIME', $ttl), $callback);
}

function get_option($key)
{
    $val = cache_remember($key, function () use ($key) {
        return Option::where('key', $key)->first()->value ?? [];
    });

    if ($key === 'general') {
        $target = null;
        if (auth()->check()) {
            $user = auth()->user();
            if (!empty($user->white_label_title) || !empty($user->white_label_logo)) {
                $target = $user;
            } else {
                $curr = $user->parent;
                while ($curr) {
                    if (!empty($curr->white_label_title) || !empty($curr->white_label_logo)) {
                        $target = $curr;
                        break;
                    }
                    $curr = $curr->parent;
                }
            }
        } else {
            $brand = request('brand') ?? request('portal');
            $host = request()->getHost();
            if ($brand) {
                $target = User::where('username', $brand)->first();
            } elseif ($host && !in_array($host, ['sms.softcents.com', 'localhost', '127.0.0.1'])) {
                $target = User::where('custom_domain', $host)->first();
            }
        }

        if ($target) {
            if (!empty($target->white_label_title)) {
                $val['title'] = $target->white_label_title;
            }
            if (!empty($target->white_label_logo)) {
                $val['logo'] = $target->white_label_logo;
                $val['front_logo'] = $target->white_label_logo;
            }
            if (!empty($target->white_label_favicon)) {
                $val['favicon'] = $target->white_label_favicon;
            }
            if (!empty($target->company_name)) {
                $val['develop_by'] = $target->company_name;
            }
            if (!empty($target->white_label_phone)) {
                $val['phone'] = $target->white_label_phone;
            }
            if (!empty($target->white_label_email)) {
                $val['email'] = $target->white_label_email;
            }
            if (!empty($target->white_label_footer)) {
                $val['copyright'] = $target->white_label_footer;
                $val['footer_desc'] = $target->white_label_footer;
            }
        }
    }

    return $val;
}

function payable(float | int $amount, Gateway $gateway)
{
    if ($gateway->currency->code == default_currency('code')) {
        return $amount + $gateway->charge;
    } else {
        return (convert_to_default_amount($gateway->charge, $gateway->currency) * $gateway->currency->rate) + $gateway->charge;
    }
}

function convert_to_default_amount($amount, $currency)
{
    return $amount * $currency->rate;
}

function formatted_date(string $date = null, string $format = 'd M, Y'): ?string
{
    return !empty($date) ? Date::parse($date)->format($format) : null;
}

function sendNotification($id, $url, $message)
{
    $notify = [
        'id' => $id,
        'url' => $url,
        'message' => $message,
    ];

    $notify_user = User::where('role', 'superadmin')->first();
    Notification::send($notify_user, new SendNotification($notify));
}

function currency_format($amount, $type = "icon", $decimals = 2, $currency = null)
{
    $amount = number_format($amount, $decimals);
    $currency = $currency ?? default_currency();

    if ($type == "icon" || $type == "symbol") {
        if ($currency->position == "right") {
            return $amount . $currency->symbol;
        } else {
            return $currency->symbol . $amount;
        }
    } else {
        if ($currency->position == "right") {
            return $amount . ' ' . $currency->code;
        } else {
            return $currency->code . ' ' . $amount;
        }
    }
}

function default_currency($key = null, Currency $currency = null): object | int | string
{
    $currency = $currency ?? cache_remember('default_currency', function () {
        $currency = Currency::whereIsDefault(1)->first();

        if (!$currency) {
            $currency = (object) ['name' => 'US Dollar', 'code' => 'USD', 'rate' => 1, 'symbol' => '$', 'position' => 'left', 'status' => true, 'is_default' => true];
        }

        return $currency;
    });

    return $key ? $currency->$key : $currency;
}

function wordCountDown($isUnicode, $contents)
{

    if ($isUnicode) {
        $total_char = mb_strlen($contents, 'UTF-8');
        $sms_char_countdown = get_option('sms-settings')['unicode_char_per_sms'] < $total_char ? get_option('sms-settings')['long_unicode_char_per_sms'] : get_option('sms-settings')['unicode_char_per_sms'];
        return ceil($total_char / $sms_char_countdown); // total sms counted.
    } else {
        $total_char = strlen($contents);
        $sms_char_countdown = get_option('sms-settings')['text_char_per_sms'] < $total_char ? get_option('sms-settings')['long_text_char_per_sms'] : get_option('sms-settings')['text_char_per_sms'];
        return ceil($total_char / $sms_char_countdown); // total sms counted.
    }
}

function didITypeUnicode($text)
{
    return mb_detect_encoding($text, 'ASCII', true) === false;
}

function sendMessage($numbers, $contents, Senderid $sender)
{

    $isValid = $sender->gateway->namespace
        && class_exists($sender->gateway->namespace)
        && method_exists(app($sender->gateway->namespace), 'send_message');

    if ($isValid) {
        $response = app($sender->gateway->namespace)->send_message($sender->gateway, $numbers, $contents, $sender->sender);
        session()->put('gateway_id', $sender->gateway->id);
        return $response;
    }
}

function calculateCharge($grand_total_sms, $sender_id_info, $user)
{
    if($sender_id_info->rate){
        return $grand_total_sms * $sender_id_info->rate;
    }else{
        $sms_price = $sender_id_info->type == 'Non-Masking' ? $user->non_masking_rate : $user->masking_rate;
        return $grand_total_sms * $sms_price;
    }
}

function makeCampaignSms($campaign_sms, $sender_id_info, $user, $total_sms, $response)
{
    $message_details = [];
    foreach (explode(',', $campaign_sms->numbers) as $key => $number) {
        $message_details[$key] = $campaign_sms->only('campaign_id', 'contents', 'schedule', 'is_unicode', 'ip_address', 'user_id', 'senderid_id', 'status', 'total_sms') + [
            'number' => $number,
            'created_at' => now(),
            'updated_at' => now(),
            'smsgateway_id' => session('gateway_id'),
            'charge' => $sender_id_info->type == 'Non-Masking' ? ($user->non_masking_rate * $total_sms) : ($user->masking_rate * $total_sms),
        ];
    }

    return $message_details;
}

function makeSms($request, $sender_id_info, $isUnicode, $user, $total_sms)
{
    $message_details = [];
    foreach (explode(',', $request->numbers) as $key => $number) {
        if($sender_id_info->rate){
            $charge = $sender_id_info->rate * $total_sms;
        }else{
            $charge = $sender_id_info->type == 'Non-Masking' ? ($user->non_masking_rate * $total_sms) : ($user->masking_rate * $total_sms);
        }
        $message_details[$key] = $request->only('group_id', 'campaign_id', 'range', 'contents', 'schedule') + [
            'number' => $number,
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $user->id,
            'total_sms' => $total_sms,
            'ip_address' => $request->ip(),
            'is_unicode' => $isUnicode ?? 0,
            'senderid_id' => $sender_id_info->id,
            'smsgateway_id' => session('gateway_id'),
            'charge' => $charge,
            'status' => 'success',
            'operator' => class_exists(\App\Services\ReportAnalyticsService::class) ? \App\Services\ReportAnalyticsService::detectOperator($number) : null,
            'gateway_response_code' => '200 (Success)',
        ];
    }

    return $message_details;
}

function languages()
{
    return [
        'en' => 'English',
        'bn' => 'Bengali',
    ];
}

function convert_money($amount, $currency) {
    $amount = (float)($amount ?? 0);
    if (!$currency || $currency->code == default_currency('code') || $amount == 0) {
        return round($amount, 2);
    } else {
        return $amount * ($currency->rate ?? 1) / (default_currency()->rate ?? 1);
    }
}

function restorePublicImages()
{
    if (!env('DEMO_MODE')) {
        return true;
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('plans')->truncate();
    DB::table('banners')->truncate();
    DB::table('comments')->truncate();
    DB::table('blogs')->truncate();
    DB::table('gateways')->truncate();
    DB::table('currencies')->truncate();
    DB::table('features')->truncate();
    DB::table('options')->truncate();
    DB::table('pos_app_interfaces')->truncate();
    DB::table('testimonials')->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    Artisan::call('db:seed', ['--class' => 'PlanSeeder']);
    Artisan::call('db:seed', ['--class' => 'OptionTableSeeder']);
    Artisan::call('db:seed', ['--class' => 'AdvertiseSeeder']);
    Artisan::call('db:seed', ['--class' => 'BlogSeeder']);
    Artisan::call('db:seed', ['--class' => 'CurrencySeeder']);
    Artisan::call('db:seed', ['--class' => 'FeatureSeeder']);
    Artisan::call('db:seed', ['--class' => 'GatewaySeeder']);
    Artisan::call('db:seed', ['--class' => 'InterfaceSeeder']);
    Artisan::call('db:seed', ['--class' => 'TestimonialSeeder']);

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    $sourcePath = public_path('demo_images');
    $destinationPath = public_path('uploads');

    if (File::exists($sourcePath)) {
        // File::cleanDirectory($destinationPath);
        File::copyDirectory($sourcePath, $destinationPath);
    }
}
