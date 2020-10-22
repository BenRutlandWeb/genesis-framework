<?php

namespace Genesis\Console;

use Genesis\Console\Application;
use Genesis\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The pre-built commands
     *
     * @var array
     */
    protected $commands = [
        \Genesis\Foundation\Console\Commands\MakeCommand::class,
        \Genesis\Foundation\Console\Commands\MakeController::class,
        \Genesis\Foundation\Console\Commands\MakeEvent::class,
        \Genesis\Foundation\Console\Commands\MakeModel::class,
        \Genesis\Foundation\Console\Commands\MakeListener::class,
        \Genesis\Foundation\Console\Commands\MakeSubscriber::class,
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
        $files = $this->app->make('files');

        foreach ($this->commands as $command) {
            $console->add(new $command($files));
        }

        $console->load(app_path('Console/Commands'));

        require base_path('routes/console.php');

        $console->boot();
    }
}
