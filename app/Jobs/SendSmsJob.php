<?php

namespace App\Jobs;

use App\Models\Sms;
use App\Models\Smsgateway;
use App\SmsGateways\PanelSms;
use App\SmsGateways\WhiteInfoBDSms;
use App\SmsGateways\AnbernetSms;
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
        $driver = trim($this->gatewayConfig['driver'] ?? 'whiteinfobd');

        // Normalize destination phone number to 8801XXXXXXXXX for Bangladeshi operators
        $cleanNumber = preg_replace('/[^0-9]/', '', (string) $this->number);
        if (str_starts_with($cleanNumber, '01') && strlen($cleanNumber) === 11) {
            $normalizedNumber = '88' . $cleanNumber;
        } elseif (preg_match('/^1[3-9]\d{8}$/', $cleanNumber)) {
            $normalizedNumber = '880' . $cleanNumber;
        } else {
            $normalizedNumber = $cleanNumber;
        }

        $sent = false;
        $response = null;
        $senderId = trim((string) ($this->gatewayConfig['senderid'] ?? $this->gatewayConfig['sender'] ?? ''));

        if ($driver === 'anbernet') {
            $sms = new AnbernetSms();
            $apiUrl = $this->gatewayConfig['api_url'] ?? $this->gatewayConfig['apiurl'] ?? 'https://wapi.anbernet.com:9956';
            $response = $sms->send(
                $normalizedNumber,
                $this->message,
                $senderId,
                $apiUrl,
                $this->gatewayConfig
            );

            if (is_array($response) && (
                ($response['status'] ?? '') === 'success' ||
                ($response['code'] ?? null) == '9000' ||
                !empty($response['message_id'])
            )) {
                $sent = true;
            } else {
                Log::warning('SendSmsJob: Anbernet send returned non-success response', [
                    'number' => $normalizedNumber,
                    'response' => $response,
                ]);
            }
        } elseif ($driver === 'whiteinfobd') {
            $whiteInfoGw = Smsgateway::find(31);
            $gwParams = $whiteInfoGw && is_array($whiteInfoGw->params) ? $whiteInfoGw->params : (json_decode($whiteInfoGw->params ?? '{}', true) ?: []);

            $apiUrl = $this->gatewayConfig['apiurl'] ?? $gwParams['api_url'] ?? 'https://sms.whiteinfobd.com/api/v1/messages';
            $apiKey = $this->gatewayConfig['apikey'] ?? $gwParams['api_key'] ?? '';

            $sms = new WhiteInfoBDSms();
            $response = $sms->send(
                $normalizedNumber,
                $this->message,
                $senderId,
                $apiUrl,
                $apiKey
            );

            if (is_array($response) && (
                ($response['code'] ?? null) == 1000 ||
                in_array(strtolower($response['status'] ?? ''), ['processing', 'success', 'sent'])
            )) {
                $sent = true;
            } else {
                Log::warning('SendSmsJob: WhiteInfoBD send returned non-success response', [
                    'number' => $normalizedNumber,
                    'response' => $response,
                ]);
            }
        } else {
            // For other drivers (panelsms, syssms, etc.)
            try {
                $sms = new PanelSms();
                $response = $sms->send(
                    $normalizedNumber,
                    $this->message,
                    $this->gatewayConfig['senderid'] ?? '',
                    $this->gatewayConfig['apiurl'] ?? '',
                    $this->gatewayConfig['apikey'] ?? '',
                    $this->gatewayConfig['clientid'] ?? null
                );
                $sent = true;
            } catch (\Throwable $e) {
                Log::warning('SendSmsJob: Primary driver ' . $driver . ' threw exception: ' . $e->getMessage() . '. Attempting failover to White Info BD.');
            }
        }

        // Automatic Failover: If primary dispatch did not succeed, fallback to White Info BD
        if (!$sent && !empty($senderId)) {
            try {
                $whiteInfoGw = Smsgateway::find(31);
                if ($whiteInfoGw && $whiteInfoGw->status) {
                    $gwParams = is_array($whiteInfoGw->params) ? $whiteInfoGw->params : (json_decode($whiteInfoGw->params ?? '{}', true) ?: []);
                    $sms = new WhiteInfoBDSms();
                    $response = $sms->send(
                        $normalizedNumber,
                        $this->message,
                        $senderId,
                        $gwParams['api_url'] ?? 'https://sms.whiteinfobd.com/api/v1/messages',
                        $gwParams['api_key'] ?? ''
                    );
                    Log::info('SendSmsJob: Failover to White Info BD executed', [
                        'number' => $normalizedNumber,
                        'response' => $response,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('SendSmsJob: Failover to White Info BD failed: ' . $e->getMessage());
            }
        }

        // Update CDR record in sms table
        $this->updateCdr($normalizedNumber, $response);
    }

    /**
     * Update SMS CDR record with gateway response
     */
    private function updateCdr($normalizedNumber, $response): void
    {
        try {
            $smsRecord = $this->smsId ? Sms::find($this->smsId) : null;
            if (!$smsRecord) {
                $smsRecord = Sms::where(function ($q) use ($normalizedNumber) {
                    $q->where('number', $normalizedNumber)
                      ->orWhere('number', $this->number);
                })
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

                // 3. Gateway Response Code
                if (isset($response['data']['infozillion_response']['status_code'])) {
                    $code = $response['data']['infozillion_response']['status_code'];
                    $desc = $response['data']['infozillion_response']['description'] ?? 'Central Platform';
                    $updates['gateway_response_code'] = "{$code} ({$desc})";
                } elseif (isset($response['code'])) {
                    $code = $response['code'];
                    $msg = $response['message'] ?? 'Success';
                    $updates['gateway_response_code'] = "{$code} ({$msg})";
                }

                // 4. Operator
                if (isset($response['data']['operator_response']['operator'])) {
                    $updates['operator'] = (string) $response['data']['operator_response']['operator'];
                }

                // 5. MNO TxnId
                if (isset($response['data']['operator_response']['ans_response_code'])) {
                    $updates['mno_txnid'] = (string) $response['data']['operator_response']['ans_response_code'];
                }

                // 6. Update gateway_id to 31 if White Info BD processed it
                if (isset($response['batch_id']) || (isset($response['message_id']) && str_starts_with($response['message_id'], 'SMS'))) {
                    $updates['smsgateway_id'] = 31;
                }

                if (!empty($updates)) {
                    $smsRecord->update($updates);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('SendSmsJob: Failed to update CDR with gateway response: ' . $e->getMessage());
        }
    }
}
