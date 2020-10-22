<?php

namespace Genesis\Database;

use Genesis\Support\ServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

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

        $this->app->singleton('db.connection', function ($app) {
            return $app['db']->connection();
        });

        $this->app->singleton('db.schema', function ($app) {
            return $app['db.connection']->getSchemaBuilder();
        });

        $this->app->singleton('migration.repository', function ($app) {
            return new DatabaseMigrationRepository($app['db'], $app['config']->get('migrations'));
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
