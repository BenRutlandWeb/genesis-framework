<?php

namespace Genesis\Database;

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
        $this->app->singleton('wpdb', function () {
            global $wpdb;
            return $wpdb;
        });

        $this->app->singleton('db.capsule', function () {
            return new Capsule();
        });

        $this->app->singleton('db', function ($app) {

            $app['db.capsule']->addConnection([
                'driver'    => 'mysql',
                'prefix'    => $app->make('wpdb')->prefix,
                'host'      => DB_HOST,
                'database'  => DB_NAME,
                'username'  => DB_USER,
                'password'  => DB_PASSWORD,
                'port'      => '3306',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ], 'default');
            $app['db.capsule']->bootEloquent();

            return $app['db.capsule']->getDatabaseManager();
        });
    }
}
