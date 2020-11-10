<?php

namespace Genesis\Support\Facades;

use Genesis\Routing\RestRouter;
use Illuminate\Support\Facades\Facade;

class Route extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return RestRouter::class;
    }
}
