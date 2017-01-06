<?php
/**
 * Default theme options.
 *
 * @package Bizroot
 */

if ( ! function_exists( 'bizroot_get_default_theme_options' ) ) :

	/**
	 * Get default theme options
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function bizroot_get_default_theme_options() {

		$defaults = array();

		// Header.
		$defaults['show_title']       = true;
		$defaults['show_tagline']     = true;
		$defaults['search_in_header'] = true;

		// Layout.
		$defaults['global_layout']           = 'right-sidebar';
		$defaults['archive_layout']          = 'excerpt';
		$defaults['archive_image']           = 'large';
		$defaults['archive_image_alignment'] = 'center';
		$defaults['single_image']            = 'large';

		// Home Page.
		$defaults['home_content_status'] = true;

		// Pagination.
		$defaults['pagination_type'] = 'numeric';

		// Footer contact.
		$defaults['show_footer_contact']                    = false;
		$defaults['enable_footer_contact_background_image'] = true;
		$defaults['footer_contact_background_image']        = get_template_directory_uri() . '/images/footer-contact-bg.jpg';
		$defaults['enable_footer_contact_background_overlay'] = true;
		$defaults['footer_contact_email']                   = '';
		$defaults['footer_contact_address']                 = '';
		$defaults['footer_contact_map_url']                 = '';
		$defaults['footer_contact_phone']                   = '';

		// Footer.
		$defaults['copyright_text']        = esc_html__( 'Copyright &copy; All rights reserved.', 'bizroot' );
		$defaults['show_social_in_footer'] = false;

		// Blog.
		$defaults['blog_title']     = esc_html__( 'Blog', 'bizroot' );
		$defaults['excerpt_length'] = 40;
		$defaults['read_more_text'] = esc_html__( 'Read More ...', 'bizroot' );

		// Breadcrumb.
		$defaults['breadcrumb_type'] = 'simple';

		// Advanced.
		$defaults['custom_css'] = '';

		// Slider Options.
		$defaults['featured_slider_status']              = 'disabled';
		$defaults['featured_slider_transition_effect']   = 'fadeout';
		$defaults['featured_slider_transition_delay']    = 3;
		$defaults['featured_slider_transition_duration'] = 1;
		$defaults['featured_slider_enable_caption']      = true;
		$defaults['featured_slider_enable_arrow']        = true;
		$defaults['featured_slider_enable_pager']        = true;
		$defaults['featured_slider_enable_autoplay']     = true;
		$defaults['featured_slider_enable_overlay']      = true;
		$defaults['featured_slider_type']                = 'featured-page';
		$defaults['featured_slider_number']              = 3;
		$defaults['featured_slider_read_more_text']      = esc_html__( 'Read More', 'bizroot' );

		// Pass through filter.
		$defaults = apply_filters( 'bizroot_filter_default_theme_options', $defaults );
		return $defaults;
	}

endif;
