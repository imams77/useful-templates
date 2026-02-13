<?php
/**
 * Theme setup: add_theme_support(), register menus, image sizes, etc.
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function starter_theme_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Switch default core markup to output valid HTML5.
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add support for responsive embeds.
    add_theme_support( 'responsive-embeds' );

    // Add support for custom logo.
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Register navigation menus.
    register_nav_menus( array(
        'primary'  => __( 'Primary Menu', 'starter-theme' ),
        'footer'   => __( 'Footer Menu', 'starter-theme' ),
    ) );

    // Add custom image sizes.
    // add_image_size( 'starter-featured', 1200, 630, true );
}
add_action( 'after_setup_theme', 'starter_theme_setup' );

/**
 * Register widget areas.
 */
function starter_theme_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'starter-theme' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in the sidebar.', 'starter-theme' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Widgets', 'starter-theme' ),
        'id'            => 'footer-1',
        'description'   => __( 'Add widgets here to appear in the footer.', 'starter-theme' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'starter_theme_widgets_init' );