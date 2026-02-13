<?php
/**
 * The template for displaying single posts.
 *
 * @package Starter_Theme
 */

get_header();
?>

<main id="main-content" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post max-w-3xl mx-auto px-4 py-8' ); ?>>
			<header class="entry-header mb-8">
				<h1 class="entry-title text-4xl font-bold mb-4"><?php the_title(); ?></h1>

				<div class="entry-meta text-sm text-gray-500 flex flex-wrap gap-4">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( get_the_date() ); ?>
					</time>

					<span class="entry-author">
						<?php esc_html_e( 'By', 'starter-theme' ); ?>
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="hover:underline">
							<?php the_author(); ?>
						</a>
					</span>

					<?php if ( has_category() ) : ?>
						<span class="entry-categories">
							<?php the_category( ', ' ); ?>
						</span>
					<?php endif; ?>
				</div>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-thumbnail mb-8">
					<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
				</div>
			<?php endif; ?>

			<div class="entry-content prose prose-lg max-w-none">
				<?php the_content(); ?>

				<?php
				wp_link_pages(
					array(
						'before' => '<div class="page-links mt-6">' . esc_html__( 'Pages:', 'starter-theme' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>

			<?php if ( has_tag() ) : ?>
				<footer class="entry-footer mt-8 pt-6 border-t border-gray-200">
					<div class="entry-tags flex flex-wrap gap-2">
						<?php the_tags( '<span class="text-sm text-gray-500">' . esc_html__( 'Tags:', 'starter-theme' ) . '</span> ', ', ', '' ); ?>
					</div>
				</footer>
			<?php endif; ?>
		</article>

		<?php
		// Previous / Next post navigation.
		the_post_navigation(
			array(
				'prev_text' => '<span class="text-sm text-gray-500">' . esc_html__( 'Previous Post', 'starter-theme' ) . '</span><br><span class="font-medium">%title</span>',
				'next_text' => '<span class="text-sm text-gray-500">' . esc_html__( 'Next Post', 'starter-theme' ) . '</span><br><span class="font-medium">%title</span>',
				'class'     => 'mt-8 pt-6 border-t border-gray-200',
			)
		);
		?>

		<?php
		// If comments are open or there is at least one comment, load up the comments template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>

	<?php endwhile; ?>
</main>

<?php
get_sidebar();
get_footer();