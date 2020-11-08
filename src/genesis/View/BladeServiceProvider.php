<?php

namespace Genesis\View;

use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Define the blade directives
     *
     * @return void
     */
    public function boot()
    {
        $blade = $this->app->make('blade.compiler');

        $blade->directive('wp_head', function () {
            return '<?php wp_head(); ?>';
        });
        $blade->directive('wp_footer', function () {
            return '<?php wp_footer(); ?>';
        });
        $blade->directive('body_open', function () {
            return '<?php wp_body_open(); ?>';
        });
    }
}
