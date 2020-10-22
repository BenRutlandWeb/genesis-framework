<?php

namespace Genesis\WordPress;

class Cpt
{
    /**
     * The post type name (singular)
     *
     * @var string
     */
    protected $name = '';

    /**
     * The post type label (plural)
     *
     * @var string
     */
    protected $plural = '';

    /**
     * The post type label (singular)
     *
     * @var string
     */
    protected $singular = '';

    /**
     * Register the post type
     */
    public function __construct()
    {
        $this->register();
    }

    /**
     * Register the post type
     *
     * @return void
     */
    public function register(): void
    {
        register_post_type(
            $this->name,
            [
                'public' => true,
                'label'  => $this->plural,
            ]
        );
    }
}
