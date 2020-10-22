<?php

namespace Genesis\Foundation\Support\Providers;

use Genesis\Support\Facades\Event;
use Genesis\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        # code...
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
