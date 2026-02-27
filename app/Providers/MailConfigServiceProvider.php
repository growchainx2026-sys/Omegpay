<?php
namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Models\SmtpSetting;

class MailConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $smtp = Setting::first();
            if ($smtp) {
                Config::set('mail.mailers.smtp', [
                    'transport' => $smtp->mailer ?? 'smtp',
                    'host' => $smtp->mail_host,
                    'port' => $smtp->mail_port,
                    'username' => $smtp->mail_username,
                    'password' => $smtp->mail_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ]);

                Config::set('mail.from', [
                    'address' => $smtp->mail_username,
                    'name' => $smtp->software_name,
                ]);
            }
        }
    }
}

