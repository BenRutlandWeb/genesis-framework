<?php

namespace Genesis\Foundation\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Genesis\Foundation\AliasLoader;
use Genesis\Support\Facades\Facade;

class RegisterFacades
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($app);

        AliasLoader::getInstance(
            $app->make('config')->get('aliases', [])
        )->register();
    }
}
