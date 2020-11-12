<?php

namespace Genesis\Mail;

use Genesis\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Parsedown as Markdown;
use PHPMailer\PHPMailer\PHPMailer;

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

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('phpmailer_init', function (PHPMailer $mailer) {
            $mailer->CharSet = 'UTF-8';
            $mailer->Encoding = 'base64';
        });
    }
}
