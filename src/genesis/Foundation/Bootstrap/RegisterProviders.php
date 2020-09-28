<?php

namespace Genesis\Foundation\Bootstrap;

use Genesis\Contracts\Foundation\Application;

class RegisterProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Genesis\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $app->registerConfiguredProviders();
    }
}
