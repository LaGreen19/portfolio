<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package LaLaBird
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">

		<div id="footerwidgets">
			<div id="footer-left">
				<ul>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer 1') ) : ?>
						<li>
							<?php endif; ?>
				</ul>
			</div>

		<div id="footer-middle">
			<ul>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer 2') ) : ?>
					<li>
						<?php endif; ?>
					</ul>
		</div>

		<div id="footer-right">
			<ul>
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer 3') ) : ?>
					<li>
					<?php endif; ?>
			</ul>
		</div>

		</div>
		<br>
		<br clear="all" />


		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'lalabird' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'lalabird' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'lalabird' ), 'lalabird', '<a href="https://automattic.com/" rel="designer">Underscores.me</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
