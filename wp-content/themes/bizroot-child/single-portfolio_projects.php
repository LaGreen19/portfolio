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

<?php while ( have_posts() ) : the_post();

$size="full";
$project_type = get_field('project_type');
$project_explain = get_field('project_explain');
$services = get_field('services');
$project_link = get_field('project_link');
$project_date = get_field('project_date');
$code_link = get_field('code_link');
$main_image = get_field('main_image');
$image_1 = get_field('image_1');
$image_1_explained = get_field('image_1_explained');
$image_2 = get_field('image_2');
$image_2_explained = get_field('image_2_explained');
$image_3 = get_field('image_3');
$image_3_explained = get_field('image_3_explained');
$image_4 = get_field('image_4');  ?>

<?php the_content(); ?>

<h1 class="portfolio-gallery-title project-title"><?php the_title(); ?></h1>

<figure class="main-image">

  <?php if($main_image) { ?>
    <?php echo wp_get_attachment_image( $main_image, $size ); ?>
  <?php } ?>

</figure>

<div class="project-container">


<article class="portfolio-project single-project">

	<div class="project-info">
		<h3><?php echo $project_explain; ?></h3>
		<h3><?php echo $services; ?></h3>
    <h3><?php echo $project_link; ?></h3>
	  <h3><?php echo $code_link; ?></h3>
    <h3><?php echo $project_date; ?></h3>


<div class="project-screenshots">

<div class="individual-img top-img">
  <?php if($image_1) { ?>
    <?php echo wp_get_attachment_image( $image_1, $size ); ?>
  <?php } ?>
</div>

<div class="individual-img-caption top-img-caption">
  <?php if($image_1_explained) { ?>
    <?php echo $image_1_explained; ?>
  <?php } ?>
</div>

<div class="individual-img second-img">
  <?php if($image_2) { ?>
    <?php echo wp_get_attachment_image( $image_2, $size ); ?>
  <?php } ?>
</div>

<div class="individual-img-caption second-img-caption">
  <?php if($image_2_explained) { ?>
    <?php echo $image_2_explained; ?>
  <?php } ?>
</div>

<div class="individual-img third-img">
  <?php if($image_3) { ?>
    <?php echo wp_get_attachment_image( $image_3, $size ); ?>
  <?php } ?>
</div>

<div class="individual-img-caption second-img-caption">
  <?php if($image_3_explained) { ?>
    <?php echo $image_3_explained; ?>
  <?php } ?>
</div>


</div>

<div class="bottom-project-images">

<div class="individual-img top-img">
  <?php if($image_4) { ?>
    <?php echo wp_get_attachment_image( $image_4, $size ); ?>
  <?php } ?>
</div>

<div class="individual-img second-img">
  <?php if($image_5) { ?>
    <?php echo wp_get_attachment_image( $image_5, $size ); ?>
  <?php } ?>
</div>

<div class="individual-img third-img">
  <?php if($image_6) { ?>
    <?php echo wp_get_attachment_image( $image_6, $size ); ?>
  <?php } ?>
</div>

</div>
  </div>
</div>

</article>
</div>
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
