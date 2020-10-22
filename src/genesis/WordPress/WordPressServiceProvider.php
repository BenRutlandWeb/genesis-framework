<?php

namespace Genesis\WordPress;

use ReflectionClass;
use Genesis\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use WP_User;

class WordPressServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('wp.user', function ($app, $parameters) {
            return new WP_User($parameters['id'] ?? $app['auth']->id());
        });
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->load(app_path('Cpts'));
    }

    /**
     * Load the post types automatically without needing to be registered.
     *
     * @param string|array $paths
     *
     * @return void
     */
    protected function load($paths): void
    {
        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        $namespace = $this->app->getNamespace();

        foreach ((new Finder)->in($paths)->files() as $postType) {
            $postType = $namespace . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($postType->getPathname(), realpath(app_path()) . DIRECTORY_SEPARATOR)
            );

            if (
                is_subclass_of($postType, \Genesis\WordPress\Cpt::class) &&
                !(new ReflectionClass($postType))->isAbstract()
            ) {
                $this->app->make($postType);
            }
        }
    }
}
