<?php

namespace App\Providers;

use App\Models\EmailConfig;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class CustomMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        // Retrieve mail settings from the database
        // $mailSettings = EmailConfig::first();

        // Set mailer configuration dynamically
        // if ($mailSettings) {
        //     Config::set('mail.mailer', $mailSettings->MAIL_MAILER);
        //     Config::set('mail.host', $mailSettings->MAIL_HOST);
        //     Config::set('mail.port', $mailSettings->MAIL_PORT);
        //     Config::set('mail.username', $mailSettings->MAIL_USERNAME);
        //     Config::set('mail.password', $mailSettings->MAIL_PASSWORD);
        //     Config::set('mail.encryption', $mailSettings->MAIL_ENCRYPTION);
        //     Config::set('mail.from.address', $mailSettings->MAIL_FROM_ADDRESS);
        //     Config::set('mail.from.name', $mailSettings->MAIL_FROM_NAME);
        // }
    }
}
