<?php
/**
 * Helper / utility functions.
 *
 * Generic utilities for the theme. Vite-specific asset helpers live in
 * inc/enqueue.php to avoid duplication.
 *
 * @package Starter_Theme
 */

defined('ABSPATH') || exit;

/**
 * Return the current page/post slug, or an empty string if unavailable.
 *
 * Useful for conditionally loading per-page assets or adding body classes.
 *
 * @return string
 */
function starter_theme_current_slug(): string {
    $post = get_post();
    return $post ? $post->post_name : '';
}

/**
 * Return the name of the current page template file (without path).
 *
 * e.g. "anaya-page.php" when using templates/anaya-page.php.
 *
 * @return string
 */
function starter_theme_current_template(): string {
    $template = get_page_template_slug();
    return $template ? basename($template) : '';
}

/**
 * Wrapper for get_template_part() that also extracts variables into scope.
 *
 * Usage:
 *   starter_theme_partial('template-parts/content/card', ['title' => 'Hello']);
 *
 * Inside card.php you can use $args['title'] directly.
 *
 * @param string               $slug  Template slug (without .php).
 * @param array<string, mixed> $args  Variables to pass to the template.
 */
function starter_theme_partial(string $slug, array $args = []): void {
    if (! empty($args)) {
        // WP 5.5+ supports the $args parameter natively.
        get_template_part($slug, null, $args);
    } else {
        get_template_part($slug);
    }
}

/**
 * Truncate a string to a given number of words.
 *
 * @param string $text  The text to truncate.
 * @param int    $limit Maximum number of words.
 * @param string $more  String appended when truncated.
 * @return string
 */
function starter_theme_truncate_words(string $text, int $limit = 20, string $more = '&hellip;'): string {
    return wp_trim_words($text, $limit, $more);
}

/**
 * Return an SVG icon from the theme's assets/src/images directory.
 *
 * Falls back to an empty string if the file doesn't exist.
 *
 * @param string $name  The SVG filename (without extension).
 * @param string $class Optional CSS class(es) to add to the SVG wrapper.
 * @return string       The inline SVG markup.
 */
function starter_theme_svg_icon(string $name, string $class = ''): string {
    $path = get_template_directory() . '/assets/src/images/' . $name . '.svg';

    if (! file_exists($path)) {
        return '';
    }

    $svg = file_get_contents($path);

    if ($class && $svg) {
        $svg = '<span class="' . esc_attr($class) . '">' . $svg . '</span>';
    }

    return $svg ?: '';
}