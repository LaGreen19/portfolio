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
 * @package LaLaBird
 */
 if ( 'posts' == get_option( 'show_on_front' ) ) {
     include( get_home_template() );
 } else {
 }

get_header(); ?>


	<div id="primary portfolio-top" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post();
        $size="full";
        $intro_heading = get_field('intro_heading');
        $profile_image = get_field('profile_image');
        $intro_content = get_field('intro_content');
        $contact_button = get_field('contact_button');
        $service1 = get_field('service1');
        $service_icon1 = get_field('service_icon1');
        $service_excerpt1 = get_field('service_excerpt1');
        $service2 = get_field('service2');
        $service_icon2 = get_field('service_icon2');
        $service_excerpt2 = get_field('service_excerpt2');
        $service3 = get_field('service3');
        $service_icon3 = get_field('service_icon3');
        $service_excerpt3 = get_field('service_excerpt3');
        $service4 = get_field('service4');
        $service_icon4 = get_field('service_icon4');
        $service_excerpt4 = get_field('service_excerpt4');
    ?>

    <div class="intro_content">
        <div class="image_wrapper">
          <?php echo wp_get_attachment_image( $profile_image, $size ); ?>
        </div>

        <h1 class="intro_heading"><?php echo $intro_heading; ?></h1>
        <h3 class="intro_p"><?php echo $intro_content; ?></h3>

      </div>

      <div class="contact-button"><?php echo $contact_button; ?></div>

    <div class="services-block">

        <h1>Services</h1>
        <div class="service">
          <h2 class="service_item-title"><?php echo $service1; ?></h2>
          <figure class="service_icon"><?php echo $service_icon1; ?></figure>
          <h5 class="service_excerpt"><?php echo $service_excerpt1; ?></h5>
        </div>

        <div class="service">
          <h2 class="service_item-title"><?php echo $service2; ?></h2>
          <figure class="service_icon"><?php echo $service_icon2; ?></figure>
          <h5 class="service_excerpt"><?php echo $service_excerpt2; ?></h5>
        </div>

        <div class="service">
          <h2 class="service_item-title"><?php echo $service3; ?></h2>
          <figure class="service_icon"><?php echo $service_icon3; ?></figure>
          <h5 class="service_excerpt"><?php echo $service_excerpt3; ?></h5>
        </div>

        <div class="service">
          <h2 class="service_item-title"><?php echo $service4; ?></h2>
          <figure class="service_icon"><?php echo $service_icon4; ?></figure>
          <h5 class="service_excerpt"><?php echo $service_excerpt4; ?></h5>
        </div>
    </div>

    <div id="portfolio">
    <h1>Portfolio</h1>

       <?php query_posts('posts_per_page=4&post_type=portfolio_projects'); ?>
       			<?php while ( have_posts() ) : the_post();
       			$front_page_image = get_field("front_page_image");
       					$size = "full";
                  $project_type = get_field('project_type');
       				?>
          <div class="portfolio-home">
            <ul class="portfolio-list">
       					<li class = "portfolio-projects">


                  <figure class="image-hover portfolio-project">
       								<a href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image($front_page_image, $size); ?></a>
                      <a href="<?php the_permalink(); ?>"><div class="image-hover-text"><?php echo $project_type; ?></div></a>
                  </figure>

                </li>
            </ul>
          </div>
       		<?php endwhile; ?>
       		<?php wp_reset_query(); ?>
        </div>

    <div id="about">
        <?php get_template_part( 'template-parts/content', 'page' ); ?>
		    <?php endwhile; ?>
		<?php wp_reset_query(); ?>
  </div>
	</div>



		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
