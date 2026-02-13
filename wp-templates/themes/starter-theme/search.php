<?php
/**
 * The template for displaying search results.
 *
 * @package Starter_Theme
 */

get_header();
?>

<main id="main-content" class="site-main py-12">
    <div class="container mx-auto px-4">

        <header class="page-header mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <?php
                printf(
                    /* translators: %s: search query */
                    esc_html__('Search Results for: %s', 'starter-theme'),
                    '<span class="text-indigo-600">' . esc_html(get_search_query()) . '</span>'
                );
                ?>
            </h1>
        </header>

        <?php if (have_posts()) : ?>

            <div class="search-results grid gap-8">
                <?php while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow p-6 border border-gray-100'); ?>>
                        <header class="entry-header mb-3">
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide text-indigo-600 mb-1">
                                <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                            </span>
                            <h2 class="text-xl font-bold">
                                <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-indigo-600 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                        </header>

                        <div class="entry-summary text-gray-600 mb-4">
                            <?php the_excerpt(); ?>
                        </div>

                        <footer class="entry-footer flex items-center gap-4 text-sm text-gray-500">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>

                            <?php if (get_post_type() === 'post' && has_category()) : ?>
                                <span class="flex items-center gap-1">
                                    <span>&middot;</span>
                                    <?php the_category(', '); ?>
                                </span>
                            <?php endif; ?>
                        </footer>
                    </article>

                <?php endwhile; ?>
            </div>

            <nav class="search-pagination mt-10 flex justify-center">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => '&larr; ' . esc_html__('Previous', 'starter-theme'),
                    'next_text' => esc_html__('Next', 'starter-theme') . ' &rarr;',
                    'class'     => 'flex items-center gap-2',
                ));
                ?>
            </nav>

        <?php else : ?>

            <div class="no-results text-center py-16">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                    <?php esc_html_e('Nothing Found', 'starter-theme'); ?>
                </h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    <?php esc_html_e('Sorry, no results were found for your search. Please try again with different keywords.', 'starter-theme'); ?>
                </p>

                <div class="search-form-wrap max-w-md mx-auto">
                    <?php get_search_form(); ?>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();