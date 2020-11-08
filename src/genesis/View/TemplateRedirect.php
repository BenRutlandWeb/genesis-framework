<?php

namespace Genesis\View;

use Illuminate\Contracts\Foundation\Application;

class TemplateRedirect
{
    /**
     * The app instance
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The templates that WordPress looks for in the root of the theme.
     *
     * @var array
     */
    protected $templateHierarchy = [
        'index',
        '404',
        'archive',
        'author',
        'category',
        'tag',
        'taxonomy',
        'date',
        'embed',
        'home',
        'frontpage',
        'privacypolicy',
        'page',
        'paged',
        'search',
        'single',
        'singular',
        'attachment'
    ];

    /**
     * Filter the template heirarchy.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        foreach ($this->templateHierarchy as $type) {
            add_filter("{$type}_template_hierarchy", [$this, 'filterTemplates']);
        };
        add_filter('template_include', [$this, 'proxyBladeTemplate'], 3090);
        add_filter('wc_get_template', [$this, 'proxyBladeTemplate'], 3090);
        add_filter('wc_get_template_part', [$this, 'proxyBladeTemplate'], 3090);
    }

    /**
     * Filter the WordPress hierarchy to look for templates in resources before
     * looking in the root of the theme.
     *
     * @param array $templates
     *
     * @return array
     */
    public function filterTemplates(array $templates): array
    {
        return collect($templates)
            ->map(function ($template) {
                $blade = str_replace('.php', '.blade.php', $template);

                $path = str_replace($this->app->basePath() . '/', '', $this->app['config']['view.paths'][0]);
                return [
                    "$path/$blade",
                    "$path/$template",
                    $blade,
                    $template,
                ];
            })
            ->flatten()
            ->toArray();
    }

    public function proxyBladeTemplate(string $template)
    {
        if (\Illuminate\Support\Str::endsWith($template, '.blade.php')) {

            if (file_exists($template)) {
                return $this->proxy($template);
            }
        }
        return $template;
    }

    public function proxy($template)
    {
        add_filter('genesis.proxy', function () use ($template) {
            return str_replace('.blade.php', '', basename($template));
        });

        return __DIR__ . '/proxy.php';
    }
}
