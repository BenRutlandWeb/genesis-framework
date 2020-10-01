<?php

namespace Genesis\Support\Facades;

use Genesis\Support\Facades\Facade;

class Console extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'console';
    }
}
