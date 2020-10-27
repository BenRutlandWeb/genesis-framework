<?php

namespace Genesis\Support;

use Closure;
use Illuminate\Contracts\Container\Container;

class Pipeline
{
    /**
     * The container implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * The object being passed through the pipeline.
     *
     * @var mixed
     */
    protected $passable;

    /**
     * The array of class pipes.
     *
     * @var array
     */
    protected $pipes = [];

    /**
     * The method to call on each pipe.
     *
     * @var string
     */
    protected $method = 'handle';

    /**
     * Create a new class instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Set the object being sent through the pipeline.
     *
     * @param mixed $passable
     *
     * @return \Genesis\Support\Pipeline
     */
    public function send($passable): self
    {
        $this->passable = $passable;

        return $this;
    }

    /**
     * Set the array of pipes.
     *
     * @param array $pipes
     *
     * @return \Genesis\Support\Pipeline
     */
    public function through(array $pipes): self
    {
        $this->pipes = $pipes;

        return $this;
    }

    /**
     * Set the method to call on the pipes.
     *
     * @param string $method
     *
     * @return \Genesis\Support\Pipeline
     */
    public function via(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Run the pipeline with a final destination callback.
     *
     * @param  \Closure  $destination
     *
     * @return mixed
     */
    public function then(Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            $destination
        );
        return $pipeline($this->passable);
    }

    /**
     * Get a Closure that represents a slice of the application onion.
     *
     * @return \Closure
     */
    protected function carry(): Closure
    {
        return function (Closure $stack, $pipe) {
            return function ($passable) use ($stack, $pipe) {

                if (is_callable($pipe)) {
                    return $pipe($passable, $stack);
                }
                if (!is_object($pipe)) {
                    $pipe = $this->app->make($pipe);
                }
                return method_exists($pipe, $this->method)
                    ? $pipe->{$this->method}($passable, $stack)
                    : $pipe($passable, $stack);
            };
        };
    }
}
