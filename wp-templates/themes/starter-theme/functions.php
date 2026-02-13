<?php
/**
 * Starter Theme functions and definitions.
 *
 * @package Starter_Theme
 */

// Define theme constants.
define('STARTER_THEME_VERSION', '1.0.0');
define('STARTER_THEME_DIR', get_template_directory());
define('STARTER_THEME_URI', get_template_directory_uri());

// Theme setup: add_theme_support(), register menus, image sizes, etc.
require_once STARTER_THEME_DIR . '/inc/theme-setup.php';

// Enqueue scripts and styles (Vite integration).
require_once STARTER_THEME_DIR . '/inc/enqueue.php';

// Helper / utility functions.
require_once STARTER_THEME_DIR . '/inc/helpers.php';

// Register custom post types and taxonomies.
require_once STARTER_THEME_DIR . '/inc/custom-post-types.php';