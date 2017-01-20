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
<a href="laura-greenberg.com" rel="designer">Proudly coded in Asheville, NC by Laura Greenberg, Freelance Wordpresser and Front-End Developer</a>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
