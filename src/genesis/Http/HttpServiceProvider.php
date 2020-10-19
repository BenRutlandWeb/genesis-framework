<?php

namespace Genesis\Http;

use Genesis\Http\Request;
use Genesis\Http\Response;
use Genesis\Http\VerifyCsrfToken;
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
            $headers = function_exists('getallheaders') ? getallheaders() : [];
            return new Request($_GET, $_POST, $_FILES, $_COOKIE, $_SERVER, $headers, file_get_contents('php://input'));
        });

        $this->app->singleton('response', function () {
            return new Response();
        });

        $this->app->singleton('csrf', function ($app) {
            return new VerifyCsrfToken($app['request']);
        });
    }
}
