<?php

namespace Genesis\Http;

use Genesis\Http\Request;
use Genesis\Http\Response;
use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('request', function () {
            return new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        });

        $this->app->singleton('response', function () {
            return new Response();
        });
    }
}
