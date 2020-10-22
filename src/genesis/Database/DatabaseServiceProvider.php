<?php

namespace Genesis\Database;

use Genesis\Support\ServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;

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

            $app['db.capsule']->addConnection($app['config']->get('db'));

            $app['db.capsule']->bootEloquent();

            return $app['db.capsule']->getDatabaseManager();
        });

        $this->app->bind('db.connection', function ($app) {
            return $app['db']->connection();
        });

        $this->app->bind('db.schema', function ($app) {
            return $app['db.connection']->getSchemaBuilder();
        });
    }

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);
    }
}
