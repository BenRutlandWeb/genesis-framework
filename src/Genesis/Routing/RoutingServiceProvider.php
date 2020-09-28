<?php

namespace Genesis\Routing;

use Genesis\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('url', function () {
            return new \Genesis\Routing\URL();
        });
    }
}
