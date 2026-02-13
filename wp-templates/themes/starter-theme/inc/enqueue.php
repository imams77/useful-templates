<?php
/**
 * Enqueue scripts and styles.
 *
 * Handles Vite integration for both development (HMR dev server)
 * and production (reading from manifest.json).
 *
 * @package Starter_Theme
 */

defined('ABSPATH') || exit;

/**
 * Check if the Vite dev server is running.
 *
 * @return bool
 */
function starter_theme_is_vite_dev_server_running(): bool
{
    if (defined('STARTER_THEME_VITE_DEV') && STARTER_THEME_VITE_DEV) {
        return true;
    }

    // Check for a .vite-running flag file (created by dev workflow) or env variable.
    if (getenv('VITE_DEV_SERVER') === 'true') {
        return true;
    }

    // Attempt to connect to the dev server.
    static $is_running = null;

    if ($is_running !== null) {
        return $is_running;
    }

    $dev_server_url = starter_theme_vite_dev_server_url();

    $response = @file_get_contents($dev_server_url . '/@vite/client', false, stream_context_create([
        'http' => [
            'timeout' => 0.5,
            'method'  => 'HEAD',
        ],
        'ssl' => [
            'verify_peer'      => false,
            'verify_peer_name' => false,
        ],
    ]));

    // Also check with wp_remote_head as fallback.
    if ($response === false) {
        $response = wp_remote_head($dev_server_url . '/@vite/client', [
            'timeout'   => 1,
            'sslverify' => false,
        ]);
        $is_running = !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    } else {
        $is_running = true;
    }

    return $is_running;
}

/**
 * Get the Vite dev server URL.
 *
 * @return string
 */
function starter_theme_vite_dev_server_url(): string
{
    $default = 'http://localhost:5173';

    if (defined('STARTER_THEME_VITE_DEV_SERVER_URL')) {
        return rtrim(STARTER_THEME_VITE_DEV_SERVER_URL, '/');
    }

    return $default;
}

/**
 * Get the production manifest data.
 *
 * @return array|false
 */
function starter_theme_get_vite_manifest()
{
    static $manifest = null;

    if ($manifest !== null) {
        return $manifest;
    }

    $manifest_path = get_template_directory() . '/assets/dist/.vite/manifest.json';

    if (!file_exists($manifest_path)) {
        $manifest = false;
        return $manifest;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $manifest = false;
    }

    return $manifest;
}

/**
 * Resolve a manifest entry and return its dist URL.
 *
 * @param string $entry_key The entry key in the manifest (e.g. "js/main.js").
 * @return string|false
 */
function starter_theme_vite_asset_url(string $entry_key)
{
    $manifest = starter_theme_get_vite_manifest();

    if (!$manifest || !isset($manifest[$entry_key])) {
        return false;
    }

    return get_template_directory_uri() . '/assets/dist/' . $manifest[$entry_key]['file'];
}

/**
 * Enqueue Vite assets for development (HMR).
 *
 * @return void
 */
function starter_theme_enqueue_vite_dev(): void
{
    $dev_url = starter_theme_vite_dev_server_url();

    // Vite client for HMR.
    // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
    wp_enqueue_script('vite-client', $dev_url . '/@vite/client', [], null, false);

    // Main entry point.
    // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
    wp_enqueue_script('starter-theme-main', $dev_url . '/js/main.js', [], null, true);

    // Set module type for Vite scripts.
    add_filter('script_loader_tag', function (string $tag, string $handle) {
        if (in_array($handle, ['vite-client', 'starter-theme-main'], true)) {
            $tag = str_replace(' src=', ' type="module" src=', $tag);
        }
        return $tag;
    }, 10, 2);
}

/**
 * Enqueue Vite assets for production (from manifest).
 *
 * @return void
 */
function starter_theme_enqueue_vite_production(): void
{
    $manifest = starter_theme_get_vite_manifest();

    if (!$manifest) {
        // No manifest found — assets not built yet.
        if (current_user_can('manage_options')) {
            add_action('wp_footer', function () {
                echo '<div style="position:fixed;bottom:0;left:0;right:0;padding:12px;background:#dc2626;color:#fff;text-align:center;z-index:99999;font-family:sans-serif;">';
                echo '⚠️ Vite assets not built. Run <code style="background:rgba(0,0,0,.2);padding:2px 6px;border-radius:3px;">npm run build</code> in the theme directory.';
                echo '</div>';
            });
        }
        return;
    }

    $entry_key = 'js/main.js';

    if (!isset($manifest[$entry_key])) {
        return;
    }

    $entry   = $manifest[$entry_key];
    $dist_uri = get_template_directory_uri() . '/assets/dist/';
    $theme_version = wp_get_theme()->get('Version');

    // Enqueue the main JS file.
    wp_enqueue_script(
        'starter-theme-main',
        $dist_uri . $entry['file'],
        [],
        $theme_version,
        true
    );

    // Set module type for the main script.
    add_filter('script_loader_tag', function (string $tag, string $handle) {
        if ($handle === 'starter-theme-main') {
            $tag = str_replace(' src=', ' type="module" src=', $tag);
        }
        return $tag;
    }, 10, 2);

    // Enqueue CSS files extracted from the entry.
    if (!empty($entry['css'])) {
        foreach ($entry['css'] as $index => $css_file) {
            wp_enqueue_style(
                'starter-theme-style-' . $index,
                $dist_uri . $css_file,
                [],
                $theme_version
            );
        }
    }

    // Preload imported chunks for better performance.
    if (!empty($entry['imports'])) {
        add_action('wp_head', function () use ($manifest, $dist_uri, $entry) {
            foreach ($entry['imports'] as $import_key) {
                if (isset($manifest[$import_key])) {
                    $import_file = $manifest[$import_key]['file'];
                    printf(
                        '<link rel="modulepreload" href="%s">' . "\n",
                        esc_url($dist_uri . $import_file)
                    );
                }
            }
        }, 5);
    }
}

/**
 * Main enqueue hook.
 *
 * @return void
 */
function starter_theme_enqueue_assets(): void
{
    // Enqueue WordPress theme style.css (metadata only, minimal styles).
    wp_enqueue_style(
        'starter-theme-base',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );

    // Enqueue Vite assets based on environment.
    if (starter_theme_is_vite_dev_server_running()) {
        starter_theme_enqueue_vite_dev();
    } else {
        starter_theme_enqueue_vite_production();
    }
}
add_action('wp_enqueue_scripts', 'starter_theme_enqueue_assets');

/**
 * Preconnect to the Vite dev server during development for faster loading.
 *
 * @return void
 */
function starter_theme_vite_preconnect(): void
{
    if (starter_theme_is_vite_dev_server_running()) {
        $dev_url = starter_theme_vite_dev_server_url();
        printf('<link rel="preconnect" href="%s" crossorigin>' . "\n", esc_url($dev_url));
    }
}
add_action('wp_head', 'starter_theme_vite_preconnect', 1);