<?php

namespace Genesis\Database;

use Genesis\Database\Database;
use Genesis\Support\ServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('db.capsule', function ($app) {
            return new Capsule($app);
        });
        $this->app->bind('db.connection', function ($app) {

            global $wpdb;

            $instance = $app->make('db.capsule');
            $instance->addConnection([
                'driver'    => 'mysql',
                'prefix'    => $wpdb->prefix,
                'host'      => DB_HOST,
                'database'  => DB_NAME,
                'username'  => DB_USER,
                'password'  => DB_PASSWORD,
                'port'      => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ]);
            $instance->bootEloquent();
        });
    }
}
