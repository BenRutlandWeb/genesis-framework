<?php

namespace Genesis\View;

use Illuminate\View\ViewServiceProvider as ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->singleton('view.redirect', function ($app) {
            return new TemplateRedirect($app);
        });
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('view.redirect');
    }
}
