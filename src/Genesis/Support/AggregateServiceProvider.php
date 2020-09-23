<?php

namespace Genesis\Support;

use Genesis\Support\ServiceProvider;

class AggregateServiceProvider extends ServiceProvider
{
    /**
     * The providers.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}
