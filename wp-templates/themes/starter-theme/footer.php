<?php
/**
 * The footer for the theme.
 *
 * @package Starter_Theme
 */
?>

	</div><!-- #content -->

	<footer id="site-footer" class="site-footer" role="contentinfo">
		<?php get_template_part( 'template-parts/footer/footer', 'widgets' ); ?>

		<div class="footer-bottom">
			<div class="container mx-auto px-4 py-6 text-center text-sm text-gray-500">
				<p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'starter-theme' ); ?></p>
			</div>
		</div>
	</footer><!-- #site-footer -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>