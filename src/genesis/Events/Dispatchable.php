<?php

namespace Genesis\Events;

trait Dispatchable
{
    /**
     * Dispatch an event.
     *
     * @param mixed ...$args
     *
     * @return void
     */
    public static function dispatch(...$args): void
    {
        event(new static(...$args));
    }
}
