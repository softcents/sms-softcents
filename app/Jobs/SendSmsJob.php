<?php

namespace App\Jobs;

use App\Models\Sms;
use App\SmsGateways\PanelSms;
use App\SmsGateways\WhiteInfoBDSms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $number;
    public $message;
    public $gatewayConfig;
    public $smsId;

    /**
     * Create a new job instance.
     */
    public function __construct($number, $message, $gatewayConfig, $smsId = null)
    {
        $this->number = $number;
        $this->message = $message;
        $this->gatewayConfig = $gatewayConfig;
        $this->smsId = $smsId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $driver = trim($this->gatewayConfig['driver'] ?? 'panelsms');

        if ($driver === 'whiteinfobd') {
            $sms = new WhiteInfoBDSms();
            $response = $sms->send(
                $this->number,
                $this->message,
                $this->gatewayConfig['senderid'],
                $this->gatewayConfig['apiurl'],
                $this->gatewayConfig['apikey']
            );

            // Update SMS CDR record with gateway response
            try {
                $smsRecord = $this->smsId ? Sms::find($this->smsId) : null;
                if (!$smsRecord) {
                    $smsRecord = Sms::where('number', $this->number)
                        ->where('contents', $this->message)
                        ->latest()
                        ->first();
                }

                if ($smsRecord && is_array($response)) {
                    $updates = [];

                    // 1. Gateway TxnId & MNO TxnId
                    if (isset($response['data']['trans_id'])) {
                        $updates['gateway_txnid'] = (string) $response['data']['trans_id'];
                    } elseif (isset($response['message_id'])) {
                        $updates['gateway_txnid'] = (string) $response['message_id'];
                        $updates['mno_txnid'] = (string) $response['message_id'];
                    } elseif (isset($response['batch_id'])) {
                        $updates['gateway_txnid'] = 'BATCH-' . $response['batch_id'];
                    }

                    // 2. Infozillion Response
                    if (isset($response['data']['infozillion_response']['description'])) {
                        $code = $response['data']['infozillion_response']['status_code'] ?? '9000';
                        $desc = $response['data']['infozillion_response']['description'];
                        $updates['infozillion_response'] = "{$code}: {$desc}";
                    } elseif (isset($response['message'])) {
                        $updates['infozillion_response'] = (string) $response['message'];
                    }

                    // 2. Gateway Response Code
                    if (isset($response['data']['infozillion_response']['status_code'])) {
                        $code = $response['data']['infozillion_response']['status_code'];
                        $desc = $response['data']['infozillion_response']['description'] ?? 'Central Platform';
                        $updates['gateway_response_code'] = "{$code} ({$desc})";
                    } elseif (isset($response['code'])) {
                        $code = $response['code'];
                        $msg = $response['message'] ?? 'Success';
                        $updates['gateway_response_code'] = "{$code} ({$msg})";
                    }

                    // 3. Operator
                    if (isset($response['data']['operator_response']['operator'])) {
                        $updates['operator'] = (string) $response['data']['operator_response']['operator'];
                    }

                    // 4. MNO TxnId
                    if (isset($response['data']['operator_response']['ans_response_code'])) {
                        $updates['mno_txnid'] = (string) $response['data']['operator_response']['ans_response_code'];
                    }

                    if (!empty($updates)) {
                        $smsRecord->update($updates);
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('SendSmsJob: Failed to update CDR with gateway response', [
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            $sms = new PanelSms();
            $response = $sms->send(
                $this->number,
                $this->message,
                $this->gatewayConfig['senderid'],
                $this->gatewayConfig['apiurl'],
                $this->gatewayConfig['apikey'],
                $this->gatewayConfig['clientid'] ?? null
            );
        }
    }
}
