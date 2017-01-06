<?php
/**
 * Helper functions related to customizer and options.
 *
 * @package Bizroot
 */

if ( ! function_exists( 'bizroot_get_global_layout_options' ) ) :

	/**
	 * Returns global layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_global_layout_options() {

		$choices = array(
			'left-sidebar'  => esc_html__( 'Primary Sidebar - Content', 'bizroot' ),
			'right-sidebar' => esc_html__( 'Content - Primary Sidebar', 'bizroot' ),
			'three-columns' => esc_html__( 'Three Columns', 'bizroot' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'bizroot' ),
		);
		$output = apply_filters( 'bizroot_filter_layout_options', $choices );
		return $output;

	}

endif;

if ( ! function_exists( 'bizroot_get_pagination_type_options' ) ) :

	/**
	 * Returns pagination type options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_pagination_type_options() {

		$choices = array(
			'default' => esc_html__( 'Default (Older / Newer Post)', 'bizroot' ),
			'numeric' => esc_html__( 'Numeric', 'bizroot' ),
		);
		return $choices;

	}

endif;

if ( ! function_exists( 'bizroot_get_breadcrumb_type_options' ) ) :

	/**
	 * Returns breadcrumb type options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_breadcrumb_type_options() {

		$choices = array(
			'disabled' => esc_html__( 'Disabled', 'bizroot' ),
			'simple'   => esc_html__( 'Simple', 'bizroot' ),
			'advanced' => esc_html__( 'Advanced', 'bizroot' ),
		);
		return $choices;

	}

endif;


if ( ! function_exists( 'bizroot_get_archive_layout_options' ) ) :

	/**
	 * Returns archive layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_archive_layout_options() {

		$choices = array(
			'full'    => esc_html__( 'Full Post', 'bizroot' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'bizroot' ),
		);
		$output = apply_filters( 'bizroot_filter_archive_layout_options', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'bizroot_get_image_sizes_options' ) ) :

	/**
	 * Returns image sizes options.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $add_disable True for adding No Image option.
	 * @param array $allowed Allowed image size options.
	 * @return array Image size options.
	 */
	function bizroot_get_image_sizes_options( $add_disable = true, $allowed = array(), $show_dimension = true ) {

		global $_wp_additional_image_sizes;
		$get_intermediate_image_sizes = get_intermediate_image_sizes();
		$choices = array();
		if ( true === $add_disable ) {
			$choices['disable'] = esc_html__( 'No Image', 'bizroot' );
		}
		$choices['thumbnail'] = esc_html__( 'Thumbnail', 'bizroot' );
		$choices['medium']    = esc_html__( 'Medium', 'bizroot' );
		$choices['large']     = esc_html__( 'Large', 'bizroot' );
		$choices['full']      = esc_html__( 'Full (original)', 'bizroot' );

		if ( true === $show_dimension ) {
			foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
				$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
			}
		}

		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key;
				if ( true === $show_dimension ){
					$choices[ $key ] .= ' ('. $size['width'] . 'x' . $size['height'] . ')';
				}
			}
		}

		if ( ! empty( $allowed ) ) {
			foreach ( $choices as $key => $value ) {
				if ( ! in_array( $key, $allowed ) ) {
					unset( $choices[ $key ] );
				}
			}
		}

		return $choices;

	}

endif;


if ( ! function_exists( 'bizroot_get_image_alignment_options' ) ) :

	/**
	 * Returns image options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_image_alignment_options() {

		$choices = array(
			'none'   => _x( 'None', 'Alignment', 'bizroot' ),
			'left'   => _x( 'Left', 'Alignment', 'bizroot' ),
			'center' => _x( 'Center', 'Alignment', 'bizroot' ),
			'right'  => _x( 'Right', 'Alignment', 'bizroot' ),
		);
		return $choices;

	}

endif;

if ( ! function_exists( 'bizroot_get_featured_slider_transition_effects' ) ) :

	/**
	 * Returns the featured slider transition effects.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_featured_slider_transition_effects() {

		$choices = array(
			'fade'       => _x( 'fade', 'Transition Effect', 'bizroot' ),
			'fadeout'    => _x( 'fadeout', 'Transition Effect', 'bizroot' ),
			'none'       => _x( 'none', 'Transition Effect', 'bizroot' ),
			'scrollHorz' => _x( 'scrollHorz', 'Transition Effect', 'bizroot' ),
		);
		$output = apply_filters( 'bizroot_filter_featured_slider_transition_effects', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'bizroot_get_featured_slider_content_options' ) ) :

	/**
	 * Returns the featured slider content options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_featured_slider_content_options() {

		$choices = array(
			'home-page' => esc_html__( 'Static Front Page Only', 'bizroot' ),
			'disabled'  => esc_html__( 'Disabled', 'bizroot' ),
		);
		$output = apply_filters( 'bizroot_filter_featured_slider_content_options', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'bizroot_get_featured_slider_type' ) ) :

	/**
	 * Returns the featured slider type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_featured_slider_type() {

		$choices = array(
			'featured-page'     => __( 'Featured Pages', 'bizroot' ),
		);
		$output = apply_filters( 'bizroot_filter_featured_slider_type', $choices );
		if ( ! empty( $output ) ) {
			ksort( $output );
		}
		return $output;

	}

endif;

if ( ! function_exists( 'bizroot_get_numbers_dropdown_options' ) ) :

	/**
	 * Returns numbers dropdown options.
	 *
	 * @since 1.0.0
	 *
	 * @param int $min Min.
	 * @param int $max Max.
	 *
	 * @return array Options array.
	 */
	function bizroot_get_numbers_dropdown_options( $min = 1, $max = 4 ) {

		$output = array();

		if ( $min <= $max ) {
			for ( $i = $min; $i <= $max; $i++ ) {
				$output[ $i ] = $i;
			}
		}

		return $output;

	}

endif;

if ( ! function_exists( 'bizroot_get_contact_form_options' ) ) :

	/**
	 * Returns the contact form options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function bizroot_get_contact_form_options() {

		$output = array();
		$output[] = __( '&mdash; Select &mdash;', 'bizroot' );

		if ( defined( 'WPCF7_VERSION' ) ) {
			$qargs = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => 100,
				'no_found_rows'  => true,
				);
			$all_posts = get_posts( $qargs );
			if ( ! empty( $all_posts ) ) {
				foreach ( $all_posts as $p ) {
					$output[ $p->ID ] = esc_html( $p->post_title );
				}
			}
		}
		return $output;

	}

endif;
