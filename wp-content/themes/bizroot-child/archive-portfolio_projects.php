<?php
/**
 * The template for displaying portfolio projects pages.
 *
 * This is the template that displays portfolio project pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bizroot
 */

get_header('portfolio'); ?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

<h1 class="portfolio-gallery-title">Portfolio</h1>

<?php while ( have_posts() ) : the_post();

$size="full";
$project_type = get_field('project_type');
$project_explain = get_field('project_explain');
$project_link = get_field('project_link');
$project_date = get_field('project_date');
$main_image = get_field('main_image'); ?>

<article class="portfolio-project portfolio-page-project">

	<a href="<?php the_permalink(); ?>">
		<h2 class="portfolio-page-title"><?php the_title(); ?></h2>
	</a>

<p class="portfolio-page-excerpt"><?php echo $project_type; ?></p>

<div class="main-image">

  <?php if($main_image) { ?>
		<a href="<?php the_permalink(); ?>">
    <?php echo wp_get_attachment_image( $main_image, $size ); ?>
	</a>
  <?php } ?>

</div>

</article>
			<?php endwhile; // End of the loop. ?>

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
