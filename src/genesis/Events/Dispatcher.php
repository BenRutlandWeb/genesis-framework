<?php

namespace Genesis\Events;

use ReflectionFunction;
use ReflectionMethod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher as DispatcherInterface;

class Dispatcher implements DispatcherInterface
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
     * Make a listener
     *
     * @param callable|string $listener
     *
     * @return callable
     */
    protected function makeListener($listener): callable
    {
        if (is_string($listener) && class_exists($listener)) {
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

    /**
     * Register an event listener with the dispatcher.
     *
     * @param  string|array  $events
     * @param  \Closure|string  $listener
     * @return void
     */
    public function listen($events, $listener = null)
    {
        $listener = $this->makeListener($listener);

        add_action($events, $listener, 10, $this->getParameterCount($listener));
    }

    /**
     * Determine if a given event has listeners.
     *
     * @param  string  $eventName
     * @return bool
     */
    public function hasListeners($eventName)
    {
        # code...
    }

    /**
     * Register an event subscriber with the dispatcher.
     *
     * @param  object|string  $subscriber
     * @return void
     */
    public function subscribe($subscriber)
    {
        $subscriber = $this->resolveSubscriber($subscriber);

        $subscriber->subscribe($this);
    }

    /**
     * Dispatch an event until the first non-null response is returned.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @return array|null
     */
    public function until($event, $payload = [])
    {
        # code...
    }

    /**
     * Dispatch an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    public function dispatch($event, $payload = [], $halt = false)
    {
        if (is_object($event)) {
            [$payload, $event] = [[$event], get_class($event)];
        }
        if (is_array($payload)) {
            do_action($event, ...$payload);
        } else {
            do_action($event, $payload);
        }
    }

    /**
     * Register an event and payload to be fired later.
     *
     * @param  string  $event
     * @param  array  $payload
     * @return void
     */
    public function push($event, $payload = [])
    {
        # code...
    }

    /**
     * Flush a set of pushed events.
     *
     * @param  string  $event
     * @return void
     */
    public function flush($event)
    {
        # code...
    }

    /**
     * Remove a set of listeners from the dispatcher.
     *
     * @param  string  $event
     * @return void
     */
    public function forget($event)
    {
        remove_all_actions($event, 10);
    }

    /**
     * Forget all of the queued listeners.
     *
     * @return void
     */
    public function forgetPushed()
    {
        # code...
    }
}
