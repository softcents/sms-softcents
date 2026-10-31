<?php

namespace App\Http\Controllers;

use App\Models\Sms;
use App\Models\User;
use App\Models\Smsgateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CronSmsStatusController extends Controller
{
    public function index()
    {
        $messages = Sms::select('id', 'user_id', 'message_id', 'charge', 'smsgateway_id')->whereNotNull('message_id')->whereStatus('pending')->limit(100)->get();

        $gateway_ids = $messages->groupBy('smsgateway_id')->keys();
        $gateways = Smsgateway::select('id', 'status_api', 'status_params')->find($gateway_ids);

        $updateData = [];

        foreach ($gateways as $key => $gateway) {

            $ids = $messages->where('smsgateway_id', $gateway->id)->pluck('id')->toArray();
            $message_ids = $messages->where('smsgateway_id', $gateway->id)->pluck('message_id')->toArray();
            $message_ids = implode(',', $message_ids);

            $response = Http::asForm()->{$gateway->status_params['status_method'] ?? 'GET'}($gateway->status_api, array_merge(json_decode($gateway->status_params['others_params'] ?? [], true), [
                $gateway->status_params['message_ids_key'] ?? 'messageids' => $message_ids,
            ]));

            $status_value = $gateway->status_params['status_value'] ?? '4';
            $status_key = $gateway->status_params['status_key'] ?? 'Status';

            foreach ($ids as $key => $id) {
                $status = $response[$key][$status_key] == $status_value ? 'success' : 'failed';
                $updateData[] = ['id' => $id, 'status' => $status];
            }
        }

        $failedIds = collect($updateData)->where('status', 'failed')->pluck('id');

        foreach ($messages->find($failedIds->all()) as $sms) {
            User::find($sms->user_id)->increment('balance', $sms->charge);
        }

        DB::table('sms')->upsert($updateData, ['id'], ['status']);

        return true;
    }
}
