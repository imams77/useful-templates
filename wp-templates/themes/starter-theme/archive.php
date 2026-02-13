<?php
/**
 * The template for displaying archive pages.
 *
 * @package Starter_Theme
 */

get_header();
?>

<main id="main" class="site-main container mx-auto px-4 py-8">

	<?php if ( have_posts() ) : ?>

		<header class="archive-header mb-8">
			<?php
			the_archive_title( '<h1 class="archive-title text-3xl font-bold mb-2">', '</h1>' );
			the_archive_description( '<div class="archive-description text-gray-600 mt-2">', '</div>' );
			?>
		</header>

		<div class="archive-grid grid gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/content', get_post_type() );
			endwhile;
			?>
		</div>

		<nav class="pagination mt-12 flex justify-center gap-2">
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 2,
					'prev_text' => __( '&laquo; Previous', 'starter-theme' ),
					'next_text' => __( 'Next &raquo;', 'starter-theme' ),
				)
			);
			?>
		</nav>

	<?php else : ?>

		<div class="no-results text-center py-16">
			<h1 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Nothing Found', 'starter-theme' ); ?></h1>
			<p class="text-gray-600"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'starter-theme' ); ?></p>
		</div>

	<?php endif; ?>

</main>

<?php
get_sidebar();
get_footer();