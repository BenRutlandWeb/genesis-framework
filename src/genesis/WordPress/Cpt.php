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
            'name'                  => __($p, 'genesis'),
            'singular_name'         => __($s, 'genesis'),
            'all_items'             => __("All {$p}", 'genesis'),
            'archives'              => __("{$s} Archives", 'genesis'),
            'attributes'            => __("{$s} Attributes", 'genesis'),
            'insert_into_item'      => __("Insert into {$s}", 'genesis'),
            'uploaded_to_this_item' => __("Uploaded to this {$s}", 'genesis'),
            'filter_items_list'     => __("Filter {$p} list", 'genesis'),
            'items_list_navigation' => __("{$p} list navigation", 'genesis'),
            'items_list'            => __("{$p} list", 'genesis'),
            'new_item'              => __("New {$s}", 'genesis'),
            'add_new'               => __("Add New", 'genesis'),
            'add_new_item'          => __("Add New {$s}", 'genesis'),
            'edit_item'             => __("Edit {$s}", 'genesis'),
            'view_item'             => __("View {$s}", 'genesis'),
            'view_items'            => __("View {$p}", 'genesis'),
            'search_items'          => __("Search {$p}", 'genesis'),
            'not_found'             => __("No {$p} found", 'genesis'),
            'not_found_in_trash'    => __("No {$p} found in trash", 'genesis'),
            'parent_item_colon'     => __("Parent {$s}:", 'genesis'),
            'menu_name '            => __($p, 'genesis'),
        ];

        register_post_type($this->name, array_merge(['labels' => $labels], $this->options));
    }
}
