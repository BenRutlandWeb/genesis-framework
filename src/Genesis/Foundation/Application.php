<?php

namespace Genesis\Foundation;

use Genesis\Contracts\Support\ServiceProvider;
use Genesis\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Container\Container;

class Application extends Container implements ApplicationContract
{
    /**
     * The Genesis framework version.
     *
     * @var string
     */
    protected const VERSION = '1.0.0';

    /**
     * The base path for the Genesis installation.
     *
     * @var string
     */
    protected $basePath = '';

    /**
     * Indicates if the application has been "bootstrapped".
     *
     * @var bool
     */
    protected $bootstrapped = false;

    /**
     * Indicates if the application has "booted".
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * All of the registered service providers.
     *
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * Create a new Genesis application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct(?string $basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }
        $this->registerBaseBindings();
        $this->registerBaseServiceProviders();
        $this->registerCoreContainerAliases();
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Set the app base path.
     *
     * @param  string  $basePath
     * @return \Genesis\Foundation\Application
     */
    public function setBasePath(string $basePath): Application
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer(): void
    {
        $this->instance('path.base', $this->basePath());
        $this->instance('path.app', $this->appPath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.resources', $this->resourcesPath());
    }

    /**
     * Get the base path of the Genesis installation.
     *
     * @param  string  $path
     * @return string
     */
    public function basePath(string $path = ''): string
    {
        return $this->basePath . ($path ? '/' . $path : '');
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @param  string  $path
     * @return string
     */
    public function appPath(string $path = ''): string
    {
        return $this->basePath . '/app' . ($path ? '/' . $path : '');
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path
     * @return string
     */
    public function configPath(string $path = ''): string
    {
        return $this->basePath . '/config' . ($path ? '/' . $path : '');
    }

    /**
     * Get the path to the application resources.
     *
     * @param  string  $path
     * @return string
     */
    public function resourcesPath(string $path = ''): string
    {
        return $this->basePath . '/resources' . ($path ? '/' . $path : '');
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('app', $this);
    }

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders(): void
    {
        $this->register(new \Genesis\Console\ConsoleServiceProvider($this));
        $this->register(new \Genesis\Filesystem\FilesystemServiceProvider($this));
        $this->register(new \Genesis\View\ViewServiceProvider($this));
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases(): void
    {
        foreach ([
            'app'    => [
                self::class,
                \Illuminate\Container\Container::class,
                \Genesis\Contracts\Foundation\Application::class,
                \Illuminate\Contracts\Container\Container::class,
                \Psr\Container\ContainerInterface::class,
            ],
            'files'  => [\Genesis\Filesystem\Filesystem::class],
        ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }

    /**
     * Register a service provider with the application.
     *
     * @param  \Genesis\Contracts\Support\ServiceProvider|string  $provider
     * @return \Genesis\Contracts\Support\ServiceProvider
     */
    public function register($provider): ServiceProvider
    {
        if (is_string($provider)) {
            $provider = new $provider($this);
        }

        $provider->register();

        $this->serviceProviders[] = $provider;

        if ($this->isBooted()) {
            $this->bootProvider($provider);
        }

        return $provider;
    }


    /**
     * Determine if the application has booted.
     *
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders(): void
    {
        $providers = $this->make('config')->get('app.providers', []);

        foreach ($providers as $provider) {
            $this->register($provider);
        }
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->isBooted()) {
            return;
        }
        foreach ($this->serviceProviders as $provider) {
            $this->bootProvider($provider);
        }
        $this->booted = true;
    }

    /**
     * Bootstrap the application.
     */
    public function bootstrap()
    {
        $bootstrappers = [
            \Genesis\Foundation\Bootstrap\LoadConfiguration::class,
            \Genesis\Foundation\Bootstrap\RegisterFacades::class,
            \Genesis\Foundation\Bootstrap\RegisterProviders::class,
            \Genesis\Foundation\Bootstrap\BootProviders::class,
        ];

        foreach ($bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * Boot the given service provider.
     *
     * @param  \Genesis\Contracts\Support\ServiceProvider  $provider
     * @return mixed
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return $this->call([$provider, 'boot']);
        }
    }
}
