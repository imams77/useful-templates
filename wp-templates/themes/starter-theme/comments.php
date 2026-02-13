<?php
/**
 * The template for displaying comments.
 *
 * @package Starter_Theme
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<h2 class="comments-title text-2xl font-bold mb-6">
			<?php
			$starter_theme_comment_count = get_comments_number();
			if ( '1' === $starter_theme_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'starter-theme' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $starter_theme_comment_count, 'comments title', 'starter-theme' ) ),
					number_format_i18n( $starter_theme_comment_count ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list space-y-6 list-none pl-0">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, leave a note.
		if ( ! comments_open() ) :
			?>
			<p class="no-comments text-gray-500 italic mt-6">
				<?php esc_html_e( 'Comments are closed.', 'starter-theme' ); ?>
			</p>
		<?php endif; ?>

	<?php endif; // have_comments(). ?>

	<?php
	comment_form(
		array(
			'class_form'    => 'comment-form space-y-4',
			'class_submit'  => 'submit bg-gray-900 text-white px-6 py-2 rounded hover:bg-gray-700 transition-colors cursor-pointer',
			'title_reply'   => esc_html__( 'Leave a Reply', 'starter-theme' ),
		)
	);
	?>

</div><!-- #comments -->