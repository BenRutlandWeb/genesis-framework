<?php

namespace Genesis\Console;

use Genesis\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('command.royalmail', function () {
            return new \Genesis\Console\Commands\RoyalMail();
        });
    }
}
