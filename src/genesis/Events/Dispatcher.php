<?php

namespace Genesis\Events;

use Closure;

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
        add_action($event, $this->resolveListener($listener), $priority);
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
        do_action($event, array_merge($payload, [1]));
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
        remove_action($event, $this->resolveListener($listener), $priority);
    }

    /**
     * Wrap the callable in a closure to pass through the arguments correctly.
     *
     * @param callable $listener
     *
     * @return Closure
     */
    protected function resolveListener(callable $listener): Closure
    {
        return function (array $args) use ($listener) {
            array_pop($args);
            return call_user_func($listener, ...$args);
        };
    }
}
