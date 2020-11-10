<?php

namespace Genesis\Routing;

use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('url', function ($app) {
            return new UrlGenerator($app['request']);
        });
        $this->app->singleton(AjaxRouter::class);

        $this->app->singleton('router.api', function ($app) {
            return new ApiRouter($app);
        });
    }
}
