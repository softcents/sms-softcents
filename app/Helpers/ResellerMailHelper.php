<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailTransportFactory;

class ResellerMailHelper
{
    /**
     * Resolve mail settings for the given user or current authenticated user.
     */
    public static function getResellerMailSettings(?User $user = null): ?array
    {
        $target = $user ?? (auth()->check() ? auth()->user() : null);

        while ($target) {
            if (!empty($target->mail_settings) && !empty($target->mail_settings['host']) && !empty($target->mail_settings['username'])) {
                return [
                    'user' => $target,
                    'settings' => $target->mail_settings
                ];
            }
            $target = $target->parent;
        }

        return null;
    }

    /**
     * Configure runtime mailer with reseller's SMTP credentials.
     */
    public static function configureMailer(?User $user = null): bool
    {
        $resellerData = self::getResellerMailSettings($user);

        if (!$resellerData) {
            return false;
        }

        $settings = $resellerData['settings'];
        $reseller = $resellerData['user'];

        $host = $settings['host'] ?? null;
        $port = (int) ($settings['port'] ?? 587);
        $username = $settings['username'] ?? null;
        $password = $settings['password'] ?? null;
        $encryption = $settings['encryption'] ?? 'tls';
        $fromAddress = $settings['from_address'] ?? $username;
        $fromName = $settings['from_name'] ?? ($reseller->white_label_title ?: ($reseller->company_name ?: config('mail.from.name')));

        if ($encryption === 'none' || empty($encryption)) {
            $encryption = null;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => $port,
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.mailers.smtp.username' => $username,
            'mail.mailers.smtp.password' => $password,
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);

        if (app()->bound('mail.manager')) {
            app('mail.manager')->forgetMailers();
        }

        return true;
    }

    /**
     * Test sending an email with the given settings.
     */
    public static function sendTestMail(array $settings, string $toEmail): array
    {
        try {
            $host = $settings['host'] ?? null;
            $port = (int) ($settings['port'] ?? 587);
            $username = $settings['username'] ?? null;
            $password = $settings['password'] ?? null;
            $encryption = $settings['encryption'] ?? 'tls';
            $fromAddress = $settings['from_address'] ?? $username;
            $fromName = $settings['from_name'] ?? 'Reseller Test Mail';

            if ($encryption === 'none' || empty($encryption)) {
                $encryption = null;
            }

            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => $port,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.from.address' => $fromAddress,
                'mail.from.name' => $fromName,
            ]);

            if (app()->bound('mail.manager')) {
                app('mail.manager')->forgetMailers();
            }

            Mail::raw('Congratulations! Your Reseller White-Label SMTP Mail Configuration is working perfectly!', function ($message) use ($toEmail, $fromName, $fromAddress) {
                $message->to($toEmail)
                        ->from($fromAddress, $fromName)
                        ->subject('Reseller SMTP Configuration Test - Successful');
            });

            return [
                'success' => true,
                'message' => __('Test email sent successfully to :email', ['email' => $toEmail]),
            ];
        } catch (\Exception $e) {
            Log::error('Reseller SMTP test failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => __('Failed to send test email: ') . $e->getMessage(),
            ];
        }
    }
}
