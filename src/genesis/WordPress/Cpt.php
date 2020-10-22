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
     * The post type visibility
     *
     * @var string
     */
    protected $public = true;

    /**
     * The post type icon
     *
     * @var string
     */
    protected $icon = 'dashicons-admin-post';

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
        return Str::plural($this->name);
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
        $labels = [
            'name'                  => _x($this->plural(), 'Post type general name', 'genesis'),
            'singular_name'         => _x($this->singular(), 'Post type singular name', 'genesis'),
            'menu_name'             => _x($this->plural(), 'Admin Menu text', 'genesis'),
            'name_admin_bar'        => _x($this->singular(), 'Add New on Toolbar', 'genesis'),
            'add_new'               => __('Add New', 'genesis'),
            'add_new_item'          => __('Add New recipe', 'genesis'),
            'new_item'              => __('New recipe', 'genesis'),
            'edit_item'             => __('Edit recipe', 'genesis'),
            'view_item'             => __('View recipe', 'genesis'),
            'all_items'             => __('All recipes', 'genesis'),
            'search_items'          => __('Search recipes', 'genesis'),
            'parent_item_colon'     => __('Parent recipes:', 'genesis'),
            'not_found'             => __('No recipes found.', 'genesis'),
            'not_found_in_trash'    => __('No recipes found in Trash.', 'genesis'),
            'featured_image'        => _x('Recipe Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'genesis'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'genesis'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'genesis'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'genesis'),
            'archives'              => _x('Recipe archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'genesis'),
            'insert_into_item'      => _x('Insert into recipe', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'genesis'),
            'uploaded_to_this_item' => _x('Uploaded to this recipe', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'genesis'),
            'filter_items_list'     => _x('Filter recipes list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'genesis'),
            'items_list_navigation' => _x('Recipes list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'genesis'),
            'items_list'            => _x('Recipes list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'genesis'),
        ];

        register_post_type(
            $this->name,
            [
                'labels'             => $labels,
                'description'        => 'Recipe custom post type.',
                'public'             => $this->public,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => ['slug' => $this->name],
                'capability_type'    => 'post',
                'has_archive'        => $this->archive,
                'hierarchical'       => false,
                'menu_position'      => 20,
                'supports'           => ['title', 'editor', 'author', 'thumbnail'],
                'taxonomies'         => ['category', 'post_tag'],
                'show_in_rest'       => true,
            ]
        );
    }
}
