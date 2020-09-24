<?php

namespace Genesis\View;

use Genesis\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->instance('view.redirect', new TemplateRedirect());
    }
}
