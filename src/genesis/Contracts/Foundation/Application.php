<?php

namespace Genesis\Contracts\Foundation;

use Closure;
use Genesis\Contracts\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;

interface Application extends Container
{
    /**
     * Register a service provider with the application.
     *
     * @param  \Genesis\Contracts\Support\ServiceProvider|string  $provider
     * @return \Genesis\Contracts\Support\ServiceProvider
     */
    public function register($provider): ServiceProvider;

    /**
     * Get the base path of the Genesis installation.
     *
     * @param  string  $path
     * @return string
     */
    public function basePath(string $path = ''): string;

    /**
     * Get the path to the application "app" directory.
     *
     * @param  string  $path
     * @return string
     */
    public function appPath(string $path = ''): string;

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path
     * @return string
     */
    public function configPath(string $path = ''): string;

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders(): void;

    /**
     * Boot the application.
     *
     * @return void
     */
    public function boot(): void;

    /**
     * Detect if running the console.
     *
     * @return bool
     */
    public function runningInConsole(): bool;

    /**
     * Detect the environment.
     *
     * @param \Closure $callback
     *
     * @return string
     */
    public function detectEnvironment(Closure $callback): string;

    /**
     * Get the application namespace
     *
     * @return string
     */
    public function getNamespace(): string;
}
