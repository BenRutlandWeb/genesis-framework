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
        $blade->directive('loop', function ($view = '') {
            if ($view) {
                return "<?php if(have_posts()) : while(have_posts()) : the_post(); echo \$__env->make($view)->render(); endwhile; endif; ?>";
            }
            return '<?php if(have_posts()) : while(have_posts()) : the_post(); ?>';
        });
        $blade->directive('endloop', function () {
            return '<?php endwhile; endif; ?>';
        });
    }
}
