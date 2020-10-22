<?php

namespace Genesis\WordPress;

use Illuminate\Support\Str;

class Cpt
{
    /**
     * The post type name (singular)
     *
     * @var string
     */
    protected $name = '';

    /**
     * Options for post type registration
     *
     * @var array
     */
    protected $options = [];

    /**
     * Register the post type
     */
    public function __construct()
    {
        $this->register();
    }

    /**
     * Return the plural label
     *
     * @return string
     */
    protected function plural(): string
    {
        return Str::plural($this->singular($this->name));
    }

    /**
     * Return the singular label
     *
     * @return string
     */
    protected function singular(): string
    {
        return Str::title($this->name);
    }

    /**
     * Register the post type
     *
     * @return void
     */
    public function register(): void
    {
        $p = $this->plural();
        $s = $this->singular();

        $labels = [
            'name'                  => __($p, '@textdomain'),
            'singular_name'         => __($s, '@textdomain'),
            'all_items'             => __("All {$p}", '@textdomain'),
            'archives'              => __("{$s} Archives", '@textdomain'),
            'attributes'            => __("{$s} Attributes", '@textdomain'),
            'insert_into_item'      => __("Insert into {$s}", '@textdomain'),
            'uploaded_to_this_item' => __("Uploaded to this {$s}", '@textdomain'),
            'filter_items_list'     => __("Filter {$p} list", '@textdomain'),
            'items_list_navigation' => __("{$p} list navigation", '@textdomain'),
            'items_list'            => __("{$p} list", '@textdomain'),
            'new_item'              => __("New {$s}", '@textdomain'),
            'add_new'               => __("Add New", '@textdomain'),
            'add_new_item'          => __("Add New {$s}", '@textdomain'),
            'edit_item'             => __("Edit {$s}", '@textdomain'),
            'view_item'             => __("View {$s}", '@textdomain'),
            'view_items'            => __("View {$p}", '@textdomain'),
            'search_items'          => __("Search {$p}", '@textdomain'),
            'not_found'             => __("No {$p} found", '@textdomain'),
            'not_found_in_trash'    => __("No {$p} found in trash", '@textdomain'),
            'parent_item_colon'     => __("Parent {$s}:", '@textdomain'),
            'menu_name '            => __($p, '@textdomain'),
        ];

        register_post_type($this->name, array_merge(['labels' => $labels], $this->options));
    }
}
