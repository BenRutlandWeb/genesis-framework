<?php

namespace Genesis\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Mail extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mailer';
    }
}
