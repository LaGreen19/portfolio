<?php
/**
 * The template for displaying the front page.
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bizroot
 */
 if ( 'posts' == get_option( 'show_on_front' ) ) {
     include( get_home_template() );
 } else {
 }

get_header(); ?>


	<div id="primary portfolio-top" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

		<?php endwhile; ?>
		<?php wp_reset_query(); ?>

		</ul>
	</div>
</section>



		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	/**
	 * Hook - bizroot_action_sidebar.
	 *
	 * @hooked: bizroot_add_sidebar - 10
	 */
	do_action( 'bizroot_action_sidebar' );
?>


<?php get_footer(); ?>
