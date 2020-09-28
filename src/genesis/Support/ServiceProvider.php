<?php

namespace Genesis\Support;

use Genesis\Contracts\Foundation\Application;
use Genesis\Contracts\Support\ServiceProvider as ServiceProviderContract;

abstract class ServiceProvider implements ServiceProviderContract
{
    /**
     * The Application instance.
     *
     * @var \Genesis\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new service provider instance.
     *
     * @param \Genesis\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public abstract function register(): void;
}
