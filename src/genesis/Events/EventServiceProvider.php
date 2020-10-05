<?php

namespace Genesis\Events;

use Genesis\Events\Dispatcher;
use Genesis\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('events', function () {
            return new Dispatcher();
        });
    }
}