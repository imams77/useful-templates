<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Starter_Theme
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area w-full lg:w-1/4" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>