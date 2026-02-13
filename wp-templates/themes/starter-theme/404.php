<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Starter_Theme
 */

get_header();
?>

<main id="main-content" class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="text-center max-w-lg">
        <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
        <p class="text-xl text-gray-600 mb-8"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'starter-theme'); ?></p>
        <p class="text-gray-500 mb-8">
            <?php esc_html_e('It looks like nothing was found at this location. Maybe try a search or go back to the homepage.', 'starter-theme'); ?>
        </p>

        <div class="mb-8">
            <?php get_search_form(); ?>
        </div>

        <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block bg-gray-900 text-white px-6 py-3 rounded hover:bg-gray-700 transition-colors">
            <?php esc_html_e('Back to Homepage', 'starter-theme'); ?>
        </a>
    </div>
</main>

<?php
get_footer();