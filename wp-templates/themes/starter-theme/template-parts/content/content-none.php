<?php
/**
 * Template part for displaying a message when no content is found.
 *
 * @package Starter_Theme
 */
?>

<section class="no-results not-found py-16 text-center">
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl font-bold text-gray-800">
            <?php if ( is_search() ) : ?>
                <?php
                printf(
                    /* translators: %s: search query */
                    esc_html__( 'No results found for: %s', 'starter-theme' ),
                    '<span class="text-indigo-600">' . get_search_query() . '</span>'
                );
                ?>
            <?php else : ?>
                <?php esc_html_e( 'Nothing Found', 'starter-theme' ); ?>
            <?php endif; ?>
        </h1>
    </header>

    <div class="page-content max-w-xl mx-auto text-gray-600">
        <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
            <p>
                <?php
                printf(
                    wp_kses(
                        /* translators: %1$s: link to new post */
                        __( 'Ready to publish your first post? <a href="%1$s" class="text-indigo-600 underline hover:text-indigo-800">Get started here</a>.', 'starter-theme' ),
                        array(
                            'a' => array(
                                'href'  => array(),
                                'class' => array(),
                            ),
                        )
                    ),
                    esc_url( admin_url( 'post-new.php' ) )
                );
                ?>
            </p>
        <?php elseif ( is_search() ) : ?>
            <p class="mb-6"><?php esc_html_e( 'Sorry, nothing matched your search terms. Please try again with different keywords.', 'starter-theme' ); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p class="mb-6"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps a search might help.', 'starter-theme' ); ?></p>
            <?php get_search_form(); ?>
        <?php endif; ?>
    </div>
</section>