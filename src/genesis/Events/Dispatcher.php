<?php

namespace Genesis\Events;

use Genesis\Contracts\Foundation\Application;
use ReflectionFunction;
use ReflectionMethod;

class Dispatcher
{
    /**
     * The application
     *
     * @var \Genesis\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Assign the application
     *
     * @param Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Add an event listener
     *
     * @param string          $event
     * @param callable|string $listener
     * @param integer         $priority
     *
     * @return void
     */
    public function listen(string $event, $listener, int $priority = 10): void
    {
        $listener = $this->makeListener($listener);

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
     * @param string          $event
     * @param callable|string $listener
     * @param integer         $priority
     *
     * @return void
     */
    public function forget(string $event, $listener, int $priority = 10): void
    {
        $listener = $this->makeListener($listener);

        remove_action($event, $listener, $priority, $this->getParameterCount($listener));
    }

    /**
     * Make a listener
     *
     * @param callable|string $listener
     *
     * @return callable
     */
    protected function makeListener($listener): callable
    {
        if (is_string($listener)) {
            return [$this->app->make($listener), 'handle'];
        }
        return $listener;
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

    /**
     * Register an event subscriber with the dispatcher.
     *
     * @param  object|string  $subscriber
     * @return void
     */
    public function subscribe($subscriber): void
    {
        $subscriber = $this->resolveSubscriber($subscriber);

        $subscriber->subscribe($this);
    }

    /**
     * Resolve the subscriber instance.
     *
     * @param  object|string  $subscriber
     * @return mixed
     */
    protected function resolveSubscriber($subscriber)
    {
        if (is_string($subscriber)) {
            return $this->app->make($subscriber);
        }

        return $subscriber;
    }
}
