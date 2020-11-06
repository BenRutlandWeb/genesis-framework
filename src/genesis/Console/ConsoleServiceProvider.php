<?php

namespace Genesis\Console;

use Genesis\Console\Application;
use Genesis\Foundation\Console\Commands\MigrateInstall;
use Genesis\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The pre-built commands
     *
     * @var array
     */
    protected $commands = [
        'command.make.command',
        'command.make.controller',
        'command.make.cpt',
        'command.make.event',
        'command.make.listener',
        'command.make.mail',
        'command.make.middleware',
        'command.make.model',
        'command.make.provider',
        'command.make.subscriber',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('console', function ($app) {
            return new Application($app);
        });

        $this->app->singleton('command.make.command', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeCommand($app['files']);
        });
        $this->app->singleton('command.make.controller', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeController($app['files']);
        });
        $this->app->singleton('command.make.cpt', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeCpt($app['files']);
        });
        $this->app->singleton('command.make.event', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeEvent($app['files']);
        });
        $this->app->singleton('command.make.listener', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeListener($app['files']);
        });
        $this->app->singleton('command.make.mail', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeMail($app['files']);
        });
        $this->app->singleton('command.make.middleware', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeMiddleware($app['files']);
        });
        $this->app->singleton('command.make.model', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeModel($app['files']);
        });
        $this->app->singleton('command.make.provider', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeProvider($app['files']);
        });
        $this->app->singleton('command.make.subscriber', function ($app) {
            return new \Genesis\Foundation\Console\Commands\MakeSubscriber($app['files']);
        });

        #\Genesis\Foundation\Console\Commands\MigrateInstall::class,
        #\Genesis\Foundation\Console\Commands\MakeMigration::class,
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }
        $console = $this->app->make('console');

        foreach ($this->commands as $command) {
            $console->add($this->app->make($command));
        }

        $console->load(app_path('Console/Commands'));

        require base_path('routes/console.php');

        $console->boot();
    }
}
