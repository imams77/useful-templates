<?php
/**
 * Template part for displaying posts in archive/index views.
 *
 * @package Starter_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-gray-200 last:border-b-0'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="mb-4">
            <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
                <?php the_post_thumbnail('large', ['class' => 'w-full h-auto object-cover transition-transform duration-300 hover:scale-105']); ?>
            </a>
        </div>
    <?php endif; ?>

    <header class="mb-3">
        <?php if ('post' === get_post_type()) : ?>
            <div class="text-sm text-gray-500 mb-2">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                <?php if (has_category()) : ?>
                    <span class="mx-1">&middot;</span>
                    <?php the_category(', '); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <h2 class="text-2xl font-bold leading-tight">
            <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-blue-600 transition-colors duration-200">
                <?php the_title(); ?>
            </a>
        </h2>
    </header>

    <div class="text-gray-600 leading-relaxed mb-4">
        <?php the_excerpt(); ?>
    </div>

    <footer>
        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
            <?php esc_html_e('Read More', 'starter-theme'); ?>
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </footer>
</article>