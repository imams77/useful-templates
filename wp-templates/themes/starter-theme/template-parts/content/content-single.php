<?php
/**
 * Template part for displaying single post content.
 *
 * @package Starter_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
    <header class="entry-header mb-8">
        <?php the_title('<h1 class="entry-title text-4xl font-bold mb-4">', '</h1>'); ?>

        <div class="entry-meta text-sm text-gray-500">
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date()); ?>
            </time>
            <span class="mx-2">&middot;</span>
            <span class="author"><?php the_author(); ?></span>

            <?php if (has_category()) : ?>
                <span class="mx-2">&middot;</span>
                <span class="categories"><?php the_category(', '); ?></span>
            <?php endif; ?>
        </div>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail mb-8">
            <?php the_post_thumbnail('large', ['class' => 'w-full h-auto rounded-lg']); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content prose prose-lg max-w-none">
        <?php
        the_content();

        wp_link_pages([
            'before' => '<div class="page-links mt-6">' . esc_html__('Pages:', 'starter-theme'),
            'after'  => '</div>',
        ]);
        ?>
    </div>

    <footer class="entry-footer mt-8 pt-6 border-t border-gray-200">
        <?php if (has_tag()) : ?>
            <div class="post-tags flex flex-wrap gap-2">
                <?php the_tags('<span class="text-sm text-gray-500">' . esc_html__('Tagged: ', 'starter-theme') . '</span> ', ', ', ''); ?>
            </div>
        <?php endif; ?>
    </footer>
</article>