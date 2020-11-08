<?php

namespace Genesis\Events;

use Genesis\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('events', function ($app) {
            return new Dispatcher($app);
        });
    }
}
