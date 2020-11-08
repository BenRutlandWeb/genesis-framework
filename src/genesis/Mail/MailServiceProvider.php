<?php

namespace Genesis\Mail;

use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('mailer', function () {
            return new Mailer;
        });
    }
}
