<?php
/**
 * Theme widgets.
 *
 * @package Bizroot
 */

// Load widget base.
require_once get_template_directory() . '/lib/widget-base/class-widget-base.php';

if ( ! function_exists( 'bizroot_load_widgets' ) ) :

	/**
	 * Load widgets.
	 *
	 * @since 1.0.0
	 */
	function bizroot_load_widgets() {

		// Social widget.
		register_widget( 'Bizroot_Social_Widget' );

		// Latest News widget.
		register_widget( 'Bizroot_Latest_News_Widget' );

		// Call To Action widget.
		register_widget( 'Bizroot_Call_To_Action_Widget' );

		// Services widget.
		register_widget( 'Bizroot_Services_Widget' );

		// Contact widget.
		register_widget( 'Bizroot_Contact_Widget' );

	}

endif;

add_action( 'widgets_init', 'bizroot_load_widgets' );

if ( ! class_exists( 'Bizroot_Social_Widget' ) ) :

	/**
	 * Social widget Class.
	 *
	 * @since 1.0.0
	 */
	class Bizroot_Social_Widget extends Bizroot_Widget_Base {

		/**
		 * Sets up a new widget instance.
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			$opts = array(
				'classname'                   => 'bizroot_widget_social',
				'description'                 => __( 'Displays social icons.', 'bizroot' ),
				'customize_selective_refresh' => true,
				);
			$fields = array(
				'title' => array(
					'label' => __( 'Title:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'subtitle' => array(
					'label' => __( 'Subtitle:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				);

			if ( false === has_nav_menu( 'social' ) ) {
				$fields['message'] = array(
					'label' => __( 'Social menu is not set. Please create menu and assign it to Social Menu.', 'bizroot' ),
					'type'  => 'message',
					'class' => 'widefat',
					);
			}

			parent::__construct( 'bizroot-social', __( 'Bizroot: Social', 'bizroot' ), $opts, array(), $fields );

		}

		/**
		 * Outputs the content for the current widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments.
		 * @param array $instance Settings for the current widget instance.
		 */
		function widget( $args, $instance ) {

			$params = $this->get_params( $instance );

			echo $args['before_widget'];

			if ( ! empty( $params['title'] ) ) {
				echo $args['before_title'] . $params['title'] . $args['after_title'];
			}
			if ( ! empty( $params['subtitle'] ) ) {
				echo '<p class="widget-subtitle">' . esc_html( $params['subtitle'] ) . '</p>';
			}
			$nav_menu_locations = get_nav_menu_locations();
			$menu_id = 0;
			if ( isset( $nav_menu_locations['social'] ) && absint( $nav_menu_locations['social'] ) > 0 ) {
				$menu_id = absint( $nav_menu_locations['social'] );
			}
			if ( $menu_id > 0 ) {
				$menu_items = wp_get_nav_menu_items( $menu_id );
				if ( ! empty( $menu_items ) ) {
					echo '<ul class="size-medium">';
					foreach ( $menu_items as $m_key => $m ) {
						echo '<li>';
						echo '<a href="' . esc_url( $m->url ) . '" target="_blank">';
						echo '<span class="title screen-reader-text">' . esc_attr( $m->title ) . '</span>';
						echo '</a>';
						echo '</li>';
					}
					echo '</ul>';
				}
			}
			echo $args['after_widget'];

		}
	}
endif;

if ( ! class_exists( 'Bizroot_Latest_News_Widget' ) ) :

	/**
	 * Latest news widget Class.
	 *
	 * @since 1.0.0
	 */
	class Bizroot_Latest_News_Widget extends Bizroot_Widget_Base {

		/**
		 * Sets up a new widget instance.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'bizroot_widget_latest_news',
				'description'                 => __( 'Displays latest posts in grid.', 'bizroot' ),
				'customize_selective_refresh' => true,
				);
			$fields = array(
				'title' => array(
					'label' => __( 'Title:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'subtitle' => array(
					'label' => __( 'Subtitle:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'post_category' => array(
					'label'           => __( 'Select Category:', 'bizroot' ),
					'type'            => 'dropdown-taxonomies',
					'show_option_all' => __( 'All Categories', 'bizroot' ),
					),
				'post_number' => array(
					'label'   => __( 'Number of Posts:', 'bizroot' ),
					'type'    => 'number',
					'default' => 3,
					'css'     => 'max-width:60px;',
					'min'     => 1,
					'max'     => 100,
					),
				'post_column' => array(
					'label'   => __( 'Number of Columns:', 'bizroot' ),
					'type'    => 'select',
					'default' => 3,
					'options' => bizroot_get_numbers_dropdown_options( 1, 4 ),
					),
				'featured_image' => array(
					'label'   => __( 'Featured Image:', 'bizroot' ),
					'type'    => 'select',
					'default' => 'bizroot-thumb',
					'options' => bizroot_get_image_sizes_options(),
					),
				'excerpt_length' => array(
					'label'       => __( 'Excerpt Length:', 'bizroot' ),
					'description' => __( 'in words', 'bizroot' ),
					'type'        => 'number',
					'css'         => 'max-width:60px;',
					'default'     => 15,
					'min'         => 1,
					'max'         => 400,
					'adjacent'    => true,
					),
				'more_text' => array(
					'label'   => __( 'More Text:', 'bizroot' ),
					'type'    => 'text',
					'default' => __( 'Read more', 'bizroot' ),
					),
				'disable_date' => array(
					'label'   => __( 'Disable Date', 'bizroot' ),
					'type'    => 'checkbox',
					'default' => false,
					),
				'disable_excerpt' => array(
					'label'   => __( 'Disable Excerpt', 'bizroot' ),
					'type'    => 'checkbox',
					'default' => false,
					),
				'disable_more_text' => array(
					'label'   => __( 'Disable More Text', 'bizroot' ),
					'type'    => 'checkbox',
					'default' => false,
					),
				);

			parent::__construct( 'bizroot-latest-news', __( 'Bizroot: Latest News', 'bizroot' ), $opts, array(), $fields );
		}

		/**
		 * Outputs the content for the current widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments.
		 * @param array $instance Settings for the current widget instance.
		 */
		function widget( $args, $instance ) {

			$params = $this->get_params( $instance );

			echo $args['before_widget'];

			if ( ! empty( $params['title'] ) ) {
				echo $args['before_title'] . $params['title'] . $args['after_title'];
			}
			if ( ! empty( $params['subtitle'] ) ) {
				echo '<p class="widget-subtitle">' . esc_html( $params['subtitle'] ) . '</p>';
			}

			$qargs = array(
				'posts_per_page' => esc_attr( $params['post_number'] ),
				'no_found_rows'  => true,
				);
			if ( absint( $params['post_category'] ) > 0  ) {
				$qargs['category'] = absint( $params['post_category'] );
			}
			$all_posts = get_posts( $qargs );
			?>
			<?php if ( ! empty( $all_posts ) ) : ?>

				<?php global $post; ?>

				<div class="latest-news-widget latest-news-col-<?php echo esc_attr( $params['post_column'] ); ?>">

					<div class="inner-wrapper">

						<?php foreach ( $all_posts as $key => $post ) : ?>
							<?php setup_postdata( $post ); ?>

							<div class="latest-news-item">

									<?php if ( 'disable' !== $params['featured_image'] && has_post_thumbnail() ) : ?>
										<div class="latest-news-thumb">
											<a href="<?php the_permalink(); ?>">
												<?php
												$img_attributes = array( 'class' => 'aligncenter' );
												the_post_thumbnail( esc_attr( $params['featured_image'] ), $img_attributes );
												?>
											</a>
										</div><!-- .latest-news-thumb -->
									<?php endif; ?>
									<div class="latest-news-text-wrap">

										<div class="latest-news-text-content">
											<h3 class="latest-news-title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3><!-- .latest-news-title -->

											<?php if ( false === $params['disable_excerpt'] ) :  ?>
												<div class="latest-news-summary">
												<?php
												$summary = bizroot_the_excerpt( esc_attr( $params['excerpt_length'] ), $post );
												echo wpautop( $summary );
												?>
												</div><!-- .latest-news-summary -->
											<?php endif; ?>
										</div><!-- .latest-news-text-content -->

										<?php if ( false === $params['disable_date'] || false === $params['disable_more_text'] ) : ?>
											<div class="latest-news-meta">
												<ul>
													<?php if ( false === $params['disable_date'] ) :  ?>
														<li><span class="latest-news-date"><?php the_time( 'j M Y' ); ?></span></li>
													<?php endif; ?>
													<?php if ( false === $params['disable_more_text'] ) :  ?>
														<li><a href="<?php the_permalink(); ?>" class="custom-button"><?php echo esc_html( $params['more_text'] ); ?><span class="screen-reader-text">"<?php the_title(); ?>"</span>
														</a></li>
													<?php endif; ?>
												</ul>
											</div><!-- .latest-news-meta -->
										<?php endif; ?>

									</div><!-- .latest-news-text-wrap -->

							</div><!-- .latest-news-item -->

						<?php endforeach; ?>

					</div><!-- .row -->

				</div><!-- .latest-news-widget -->

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>

			<?php echo $args['after_widget'];

		}
	}
endif;

if ( ! class_exists( 'Bizroot_Call_To_Action_Widget' ) ) :

	/**
	 * Call to action widget Class.
	 *
	 * @since 1.0.0
	 */
	class Bizroot_Call_To_Action_Widget extends Bizroot_Widget_Base {

		/**
		 * Sets up a new widget instance.
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			$opts = array(
				'classname'                   => 'bizroot_widget_call_to_action',
				'description'                 => __( 'Call To Action Widget.', 'bizroot' ),
				'customize_selective_refresh' => true,
				);
			$fields = array(
				'title' => array(
					'label' => __( 'Title:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'description' => array(
					'label' => __( 'Description:', 'bizroot' ),
					'type'  => 'textarea',
					'class' => 'widefat',
					),
				'primary_button_text' => array(
					'label'   => __( 'Button Text:', 'bizroot' ),
					'default' => __( 'Learn more', 'bizroot' ),
					'type'    => 'text',
					'class'   => 'widefat',
					),
				'primary_button_url' => array(
					'label' => __( 'Button URL:', 'bizroot' ),
					'type'  => 'url',
					'class' => 'widefat',
					),
				);

			parent::__construct( 'bizroot-call-to-action', __( 'Bizroot: Call To Action', 'bizroot' ), $opts, array(), $fields );

		}

		/**
		 * Outputs the content for the current widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments.
		 * @param array $instance Settings for the current widget instance.
		 */
		function widget( $args, $instance ) {

			$params = $this->get_params( $instance );

			echo $args['before_widget'];

			if ( ! empty( $params['title'] ) ) {
				echo $args['before_title'] . $params['title'] . $args['after_title'];
			}
			?>
			<div class="call-to-action-content">
				<?php if ( ! empty( $params['description'] ) ) : ?>
				    <div class="call-to-action-description">
				        <?php echo wpautop( wp_kses_post( $params['description'] ) ); ?>
				    </div><!-- .call-to-action-description -->
				<?php endif; ?>
				<?php if ( ! empty( $params['primary_button_text'] ) ) : ?>
					<div class="call-to-action-buttons">
							<a href="<?php echo esc_url( $params['primary_button_url'] ); ?>" class="custom-button btn-call-to-action btn-call-to-primary"><?php echo esc_html( $params['primary_button_text'] ); ?></a>
					</div><!-- .call-to-action-buttons -->
				<?php endif; ?>
			</div><!-- .call-to-action-content -->
			<?php

			echo $args['after_widget'];

		}
	}
endif;


if ( ! class_exists( 'Bizroot_Services_Widget' ) ) :

	/**
	 * Social widget Class.
	 *
	 * @since 1.0.0
	 */
	class Bizroot_Services_Widget extends Bizroot_Widget_Base {

		/**
		 * Sets up a new widget instance.
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			$opts = array(
				'classname'                   => 'bizroot_widget_services',
				'description'                 => __( 'Show your services with icon and read more link.', 'bizroot' ),
				'customize_selective_refresh' => true,
				);
			$fields = array(
				'title' => array(
					'label' => __( 'Title:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'subtitle' => array(
					'label' => __( 'Subtitle:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'excerpt_length' => array(
					'label'       => __( 'Excerpt Length:', 'bizroot' ),
					'description' => __( 'in words', 'bizroot' ),
					'type'        => 'number',
					'css'         => 'max-width:60px;',
					'default'     => 15,
					'min'         => 1,
					'max'         => 400,
					'adjacent'    => true,
					),
				'disable_excerpt' => array(
					'label'   => __( 'Disable Excerpt', 'bizroot' ),
					'type'    => 'checkbox',
					'default' => false,
					),
				'more_text' => array(
					'label'   => __( 'Read More Text:', 'bizroot' ),
					'type'    => 'text',
					'default' => __( 'Read more', 'bizroot' ),
					),
				'disable_more_text' => array(
					'label'   => __( 'Disable Read More', 'bizroot' ),
					'type'    => 'checkbox',
					'default' => false,
					),
				);

			for( $i = 1; $i <= 4; $i++ ) {
				$fields[ 'block_heading_' . $i ] = array(
					'label' => __( 'Block', 'bizroot' ) . ' #' . $i,
					'type'  => 'heading',
					'class' => 'widefat',
					);
				$fields[ 'block_page_' . $i ] = array(
					'label'            => __( 'Select Page:', 'bizroot' ),
					'type'             => 'dropdown-pages',
					'show_option_none' => __( '&mdash; Select &mdash;', 'bizroot' ),
					);
				$fields[ 'block_icon_' . $i ] = array(
					'label'       => __( 'Icon:', 'bizroot' ),
					'description' => __( 'Eg: fa-cogs', 'bizroot' ),
					'type'        => 'text',
					'default'     => 'fa-cogs',
					);
			}

			parent::__construct( 'bizroot-services', __( 'Bizroot: Services', 'bizroot' ), $opts, array(), $fields );

		}

		/**
		 * Outputs the content for the current widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments.
		 * @param array $instance Settings for the current widget instance.
		 */
		function widget( $args, $instance ) {

			$params = $this->get_params( $instance );

			echo $args['before_widget'];

			if ( ! empty( $params['title'] ) ) {
				echo $args['before_title'] . $params['title'] . $args['after_title'];
			}
			if ( ! empty( $params['subtitle'] ) ) {
				echo '<p class="widget-subtitle">' . esc_html( $params['subtitle'] ) . '</p>';
			}

			$service_arr = array();
			for ( $i = 0; $i < 4 ; $i++ ) {
				$block = ( $i + 1 );
				$service_arr[ $i ] = array(
					'page' => $params[ 'block_page_' . $block ],
					'icon' => $params[ 'block_icon_' . $block ],
					);
			}
			$refined_arr = array();
			if ( ! empty( $service_arr ) ) {
				foreach ( $service_arr as $item ) {
					if ( ! empty( $item['page'] ) ) {
						$refined_arr[ $item['page'] ] = $item;
					}
				}
			}

			if ( ! empty( $refined_arr ) ) {
				$this->render_widget_content( $refined_arr, $params );
			}

			echo $args['after_widget'];

		}

		/**
		 * Render services content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $service_arr Services array.
		 * @param array $params      Parameters array.
		 */
		function render_widget_content( $service_arr, $params ) {

			$column = count( $service_arr );

			$page_ids = array_keys( $service_arr );

			$qargs = array(
				'post__in'      => $page_ids,
				'post_type'     => 'page',
				'orderby'       => 'post__in',
				'no_found_rows' => true,
				);

			$all_posts = get_posts( $qargs );
			?>
			<?php if ( ! empty( $all_posts ) ) :  ?>

				<?php global $post; ?>

				<div class="service-block-list service-col-<?php echo esc_attr( $column ); ?>">
					<div class="inner-wrapper">

						<?php foreach ( $all_posts as $post ) :  ?>
							<?php setup_postdata( $post ); ?>
							<div class="service-block-item">
								<div class="service-block-inner">
									<?php if ( isset( $service_arr[ $post->ID ]['icon'] ) && ! empty( $service_arr[ $post->ID ]['icon'] ) ) : ?>
										<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"><i class="<?php echo 'fa ' . esc_attr( $service_arr[ $post->ID ]['icon'] ); ?>"></i></a>
									<?php endif; ?>
									<div class="service-block-inner-content">
										<h3 class="service-item-title">
											<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
												<?php echo get_the_title( $post->ID ); ?>
											</a>
										</h3>
										<?php if ( true !== $params['disable_excerpt'] ) :  ?>
											<div class="service-block-item-excerpt">
												<?php
												$excerpt = bizroot_the_excerpt( $params['excerpt_length'], $post );
												echo wpautop( wp_kses_post( $excerpt ) );
												?>
											</div><!-- .service-block-item-excerpt -->
										<?php endif; ?>

										<?php if ( true !== $params['disable_more_text'] ) :  ?>
											<a href="<?php echo esc_url( get_permalink( $post -> ID ) ); ?>" class="custom-button"><?php echo esc_html( $params['more_text'] ); ?></a>
										<?php endif; ?>
									</div><!-- .service-block-inner-content -->
								</div><!-- .service-block-inner -->
							</div><!-- .service-block-item -->
						<?php endforeach; ?>

					</div><!-- .inner-wrapper -->

				</div><!-- .service-block-list -->

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>

			<?php
		}

	}
endif;

if ( ! class_exists( 'Bizroot_Contact_Widget' ) ) :

	/**
	 * Contact widget Class.
	 *
	 * @since 1.0.0
	 */
	class Bizroot_Contact_Widget extends Bizroot_Widget_Base {

		/**
		 * Sets up a new widget instance.
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			$opts = array(
				'classname'                   => 'bizroot_widget_contact',
				'description'                 => __( 'Displays contact form.', 'bizroot' ),
				'customize_selective_refresh' => true,
				);
			$fields = array(
				'title' => array(
					'label' => __( 'Title:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'subtitle' => array(
					'label' => __( 'Subtitle:', 'bizroot' ),
					'type'  => 'text',
					'class' => 'widefat',
					),
				'form_id' => array(
					'label'   => __( 'Select Contact Form:', 'bizroot' ),
					'type'    => 'select',
					'options' => bizroot_get_contact_form_options(),
					),
				'form_message' => array(
					'label' => _x( 'OR', 'Bizroot Contact', 'bizroot' ),
					'type'  => 'heading',
					),
				'form_shortcode' => array(
					'label' => __( 'Enter Form Shortcode:', 'bizroot' ),
					'type'  => 'textarea',
					'class' => 'widefat',
					),
				);

			parent::__construct( 'bizroot-contact', __( 'Bizroot: Contact', 'bizroot' ), $opts, array(), $fields );

		}

		/**
		 * Outputs the content for the current widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments.
		 * @param array $instance Settings for the current widget instance.
		 */
		function widget( $args, $instance ) {

			$params = $this->get_params( $instance );

			echo $args['before_widget'];

			if ( ! empty( $params['title'] ) ) {
				echo $args['before_title'] . $params['title'] . $args['after_title'];
			}
			if ( ! empty( $params['subtitle'] ) ) {
				echo '<p class="widget-subtitle">' . esc_html( $params['subtitle'] ) . '</p>';
			}

			$shortcode_text = null;

			if ( ! empty( $params['form_id'] ) && absint( $params['form_id'] ) > 0 && defined( 'WPCF7_VERSION' ) ) {
				$shortcode_text = '[contact-form-7 id="' . absint( $params['form_id'] ) . '"]';
			}

			if ( empty( $shortcode_text ) ) {
				$shortcode_text = wp_kses_data( $params['form_shortcode'] );
			}

			if ( ! empty( $shortcode_text ) ) {
				echo do_shortcode( $shortcode_text );
			}

			echo $args['after_widget'];

		}
	}
endif;
