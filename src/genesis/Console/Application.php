<?php

namespace Genesis\Console;

use Closure;
use Genesis\Console\Command;
use Genesis\Foundation\Console\Commands\ClosureCommand;

class Application
{
    /**
     * The registered commands
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Register a closure command.
     *
     * @param  string  $signature The command signature
     * @param  Closure $callback  The callback to run
     * @return mixed
     */
    public function command(string $signature, Closure $callback)
    {
        $command = new ClosureCommand($signature, $callback);

        $this->add($command);

        return $command;
    }

    /**
     * Add a command to the application.
     *
     * @param \Genesis\Console\Command $command
     * @return void
     */
    public function add(Command $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * Register each of the commands
     *
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->commands as $command) {
            $command->boot();
        }
    }
}
