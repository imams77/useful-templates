<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package Starter_Theme
 */

get_header(); ?>

<main id="main-content" class="site-main">
	<div class="container mx-auto px-4 py-8">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="page-header mb-8">
					<h1 class="text-3xl font-bold"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<div class="posts-grid grid gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content', get_post_type() );
				endwhile;
				?>
			</div>

			<nav class="pagination mt-12 flex justify-center gap-4">
				<?php
				the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => '&laquo; Previous',
					'next_text' => 'Next &raquo;',
				) );
				?>
			</nav>

		<?php else : ?>

			<section class="no-results py-16 text-center">
				<h1 class="text-3xl font-bold mb-4"><?php esc_html_e( 'Nothing Found', 'starter-theme' ); ?></h1>
				<p class="text-gray-600"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'starter-theme' ); ?></p>
			</section>

		<?php endif; ?>

	</div>
</main>

<?php
get_sidebar();
get_footer();
