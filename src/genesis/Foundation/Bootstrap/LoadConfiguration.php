<?php

namespace Genesis\Foundation\Bootstrap;

use Genesis\Contracts\Foundation\Application;
use Genesis\Config\Repository;

class LoadConfiguration
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Genesis\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $app->singleton('config', function ($app) {
            return new Repository(require $app->configPath('app.php'));
        });

        $app->detectEnvironment(function () {
            return wp_get_environment_type();
        });
    }
}
