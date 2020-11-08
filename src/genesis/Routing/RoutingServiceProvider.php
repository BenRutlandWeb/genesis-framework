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
            return new \Genesis\Routing\UrlGenerator($app['request']);
        });
        $this->app->singleton('router.ajax', function ($app) {
            return new \Genesis\Routing\AjaxRouter($app);
        });
        $this->app->singleton('router.api', function ($app) {
            return new \Genesis\Routing\ApiRouter($app);
        });
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('router.ajax')
            ->middleware('ajax')
            ->group($this->app->basePath('routes/ajax.php'));

        $this->app['events']->listen('rest_api_init', function () {
            $this->app->make('router.api')
                ->middleware('api')
                ->group($this->app->basePath('routes/api.php'));
        });
    }
}
