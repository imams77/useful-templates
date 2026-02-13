<?php
/**
 * Register custom post types and taxonomies.
 *
 * @package Starter_Theme
 */

defined('ABSPATH') || exit;

/**
 * Register custom post types.
 *
 * Usage example:
 *
 *   function starter_theme_register_post_types() {
 *       register_post_type('portfolio', [
 *           'labels' => [
 *               'name'               => __('Portfolio', 'starter-theme'),
 *               'singular_name'      => __('Project', 'starter-theme'),
 *               'add_new'            => __('Add New Project', 'starter-theme'),
 *               'add_new_item'       => __('Add New Project', 'starter-theme'),
 *               'edit_item'          => __('Edit Project', 'starter-theme'),
 *               'new_item'           => __('New Project', 'starter-theme'),
 *               'view_item'          => __('View Project', 'starter-theme'),
 *               'search_items'       => __('Search Projects', 'starter-theme'),
 *               'not_found'          => __('No projects found', 'starter-theme'),
 *               'not_found_in_trash' => __('No projects found in Trash', 'starter-theme'),
 *           ],
 *           'public'       => true,
 *           'has_archive'  => true,
 *           'rewrite'      => ['slug' => 'portfolio'],
 *           'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
 *           'menu_icon'    => 'dashicons-portfolio',
 *           'show_in_rest' => true,
 *       ]);
 *   }
 *   add_action('init', 'starter_theme_register_post_types');
 *
 * Register custom taxonomies.
 *
 * Usage example:
 *
 *   function starter_theme_register_taxonomies() {
 *       register_taxonomy('project_category', 'portfolio', [
 *           'labels' => [
 *               'name'          => __('Project Categories', 'starter-theme'),
 *               'singular_name' => __('Project Category', 'starter-theme'),
 *           ],
 *           'public'       => true,
 *           'hierarchical' => true,
 *           'rewrite'      => ['slug' => 'project-category'],
 *           'show_in_rest' => true,
 *       ]);
 *   }
 *   add_action('init', 'starter_theme_register_taxonomies');
 */