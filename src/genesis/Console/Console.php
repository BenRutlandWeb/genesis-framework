<?php

namespace Genesis\Console;

use Closure;
use Genesis\Foundation\Console\Commands\ClosureCommand;

class Console
{
    /**
     * Register a closure command.
     *
     * @param  string  $signature The command signature
     * @param  Closure $callback  The callback to run
     * @return mixed
     */
    public function command(string $signature, Closure $callback)
    {
        return new ClosureCommand($signature, $callback);
    }
}
