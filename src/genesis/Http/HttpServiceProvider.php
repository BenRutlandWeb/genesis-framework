<?php

namespace Genesis\Http;

use Genesis\Http\Request;
use Genesis\Support\ServiceProvider;

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
            return new Request($_GET, $_POST, $_FILES, $_COOKIE, $_SERVER, getallheaders(), file_get_contents('php://input'));
        });
    }
}
