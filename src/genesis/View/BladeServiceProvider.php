<?php

namespace Genesis\View;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * The blade compiler
     *
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $blade;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->blade = $this->app->make('blade.compiler');
    }

    /**
     * Define the blade directives
     *
     * @return void
     */
    public function boot()
    {
        $this->blade->directive('wp_head', function () {
            return '<?php wp_head(); ?>';
        });
        $this->blade->directive('wp_footer', function () {
            return '<?php wp_footer(); ?>';
        });
        $this->blade->directive('body_open', function () {
            return '<?php wp_body_open(); ?>';
        });
    }
}
