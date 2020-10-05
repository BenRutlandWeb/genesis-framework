<?php

namespace Genesis\Events;

use ReflectionFunction;
use ReflectionMethod;

class Dispatcher
{
    /**
     * Add an event listener
     *
     * @param string   $event
     * @param callable $listener
     * @param integer  $priority
     *
     * @return void
     */
    public function listen(string $event, callable $listener, int $priority = 10): void
    {
        add_action($event, $listener, $priority, $this->getParameterCount($listener));
    }

    /**
     * Dispatch the event with the given arguments.
     *
     * @param string|object $event
     * @param mixed         ...$payload
     *
     * @return void
     */
    public function dispatch($event, ...$payload): void
    {
        if (is_object($event)) {
            [$payload, $event] = [[$event], get_class($event)];
        }
        do_action($event, ...$payload);
    }

    /**
     * Forget an event listener. The callable and the priority needs to match
     * the registered event.
     *
     * @param string   $event
     * @param callable $listener
     * @param integer  $priority
     *
     * @return void
     */
    public function forget(string $event, callable $listener, int $priority = 10): void
    {
        remove_action($event, $listener, $priority, $this->getParameterCount($listener));
    }

    /**
     * Return the callable argument count.
     *
     * @param callable $listener
     *
     * @return int
     */
    protected function getParameterCount(callable $listener): int
    {
        $reflect = is_array($listener)
            ? new ReflectionMethod($listener[0], $listener[1])
            : new ReflectionFunction($listener);

        return $reflect->getNumberOfParameters();
    }
}
