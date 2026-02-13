<?php
/**
 * Template part for displaying page content.
 *
 * @package Starter_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title text-4xl font-bold mb-6">', '</h1>' ); ?>
    </header>

    <div class="entry-content prose max-w-none">
        <?php
        the_content();

        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'starter-theme' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>

    <?php if ( get_edit_post_link() ) : ?>
        <footer class="entry-footer mt-8">
            <?php
            edit_post_link(
                sprintf(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    wp_kses(
                        __( 'Edit <span class="screen-reader-text">%s</span>', 'starter-theme' ),
                        array( 'span' => array( 'class' => array() ) )
                    ),
                    wp_kses_post( get_the_title() )
                ),
                '<span class="edit-link text-sm text-gray-500 hover:text-gray-700">',
                '</span>'
            );
            ?>
        </footer>
    <?php endif; ?>
</article>