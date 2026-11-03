<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sms;
use App\Models\User;
use App\Models\Senderid;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CronScheduleSmsController extends Controller
{
    public function scheduleMessage()
    {
        $messages = Sms::whereStatus('pending')->whereDate('schedule', Carbon::now()->toDateString())
                    ->whereTime('schedule', Carbon::now()->format('H:i'))
                    ->get();

        foreach ($messages as $message) {

            $user = User::findOrFail($message->user_id);
            $isUnicode = $message->is_unicode;
            $total_sms = wordCountDown($isUnicode, $message->contents);

            $sender_id_info = Senderid::where('user_id', $user->id)->findOrFail($message->senderid_id);
            $total_numbers = count(explode(',', $message->numbers));
            $grand_total_sms = $total_numbers * $total_sms;
            $total_charges = calculateCharge($grand_total_sms, $sender_id_info, $user);

            if ($total_charges <= $user->balance) {
                DB::beginTransaction();
                try {

                    $response = sendMessage($message->numbers, $message->contents, $sender_id_info);

                    if (!$response) {
                        return response()->json(__('Please enter a valid phone number or contact with admin!'), 406);
                    }

                    $user->update([
                        'balance' => $user->balance - $total_charges
                    ]);

                    DB::commit();
                    return response()->json([
                        'message' => "Message sent successfully",
                        'redirect' => route('users.quick-sms.index')
                    ]);

                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(__('Invalid phone number or credentials.'), 404);
                }
            } else {
                return response()->json([
                    'message' => __("You don't have enough balance. Please recharge now.")
                ], 406);
            }
        }
    }

    public function lowBalanceNotify()
    {
        $users = User::where('role', 'user')
                    ->where('balance', '<', DB::raw('low_blnc_alrt'))
                    ->get();

        foreach ($users as $user) {
            sendNotification($user->id, route('admin.users.index', ['users' => 'user', 'id' => $user->id]), __('Low balance user.'));
        }

        return true;
    }
}
