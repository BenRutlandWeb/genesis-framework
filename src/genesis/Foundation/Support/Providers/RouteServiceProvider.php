<?php

namespace Genesis\Foundation\Support\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        if (method_exists($this, 'map')) {
            $this->app->call([$this, 'map']);
        }
    }
}
