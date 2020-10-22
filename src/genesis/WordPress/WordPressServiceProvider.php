<?php

namespace Genesis\WordPress;

use Genesis\Support\ServiceProvider;
use WP_User;

class WordPressServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('wp.user', function () {
            return new WP_User;
        });
    }
}
