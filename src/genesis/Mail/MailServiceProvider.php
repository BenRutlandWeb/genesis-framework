<?php

namespace Genesis\Mail;

use Illuminate\Support\ServiceProvider;
use Parsedown as Markdown;

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

        $this->app->singleton('markdown', function ($app) {
            return new Markdown();
        });
    }
}
