<?php

namespace App\Jobs;

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

    /**
     * Create a new job instance.
     */
    public function __construct($number, $message, $gatewayConfig)
    {
        $this->number = $number;
        $this->message = $message;
        $this->gatewayConfig = $gatewayConfig;
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
