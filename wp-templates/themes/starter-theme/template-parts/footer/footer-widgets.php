<?php
/**
 * Template part for displaying footer widget areas.
 *
 * @package Starter_Theme
 */

if ( ! is_active_sidebar( 'footer-1' ) ) {
	return;
}
?>

<div class="footer-widgets bg-gray-900 text-gray-300 py-12">
	<div class="container mx-auto px-4">
		<div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
			<?php dynamic_sidebar( 'footer-1' ); ?>
		</div>
	</div>
</div>