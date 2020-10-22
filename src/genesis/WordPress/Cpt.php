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
            'name'                  => _x($p, 'Post type general name', 'genesis'),
            'singular_name'         => _x($s, 'Post type singular name', 'genesis'),
            'menu_name'             => _x($p, 'Admin Menu text', 'genesis'),
            'name_admin_bar'        => _x($s, 'Add New on Toolbar', 'genesis'),
            'add_new'               => __("Add New", 'genesis'),
            'add_new_item'          => __("Add New {$s}", 'genesis'),
            'new_item'              => __("New {$s}", 'genesis'),
            'edit_item'             => __("Edit {$s}", 'genesis'),
            'view_item'             => __("View {$s}", 'genesis'),
            'all_items'             => __("All {$p}", 'genesis'),
            'search_items'          => __("Search {$p}", 'genesis'),
            'parent_item_colon'     => __("Parent {$p}:", 'genesis'),
            'not_found'             => __("No {$p} found.", 'genesis'),
            'not_found_in_trash'    => __("No {$p} found in Trash.", 'genesis'),
            'featured_image'        => _x("{$s} Cover Image", 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'genesis'),
            'set_featured_image'    => _x("Set cover image", 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'genesis'),
            'remove_featured_image' => _x("Remove cover image", 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'genesis'),
            'use_featured_image'    => _x("Use as cover image", 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'genesis'),
            'archives'              => _x("{$s} archives", 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'genesis'),
            'insert_into_item'      => _x("Insert into {$s}", 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'genesis'),
            'uploaded_to_this_item' => _x("Uploaded to this {$s}", 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'genesis'),
            'filter_items_list'     => _x("Filter {$p} list", 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'genesis'),
            'items_list_navigation' => _x("{$p} list navigation", 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'genesis'),
            'items_list'            => _x("{$p} list", 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'genesis'),
        ];

        register_post_type($this->name, array_merge(['labels' => $labels], $this->options));
    }
}
