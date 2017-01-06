<?php
/**
 * Customizer partials.
 *
 * @package Bizroot
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function bizroot_customize_partial_blogname() {

	bloginfo( 'name' );

}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function bizroot_customize_partial_blogdescription() {

	bloginfo( 'description' );

}

/**
 * Partial for footer contact email.
 *
 * @since 1.0.0
 *
 * @return void
 */
function bizroot_render_partial_footer_contact_email() {

	$footer_contact_email = bizroot_get_option( 'footer_contact_email' );
	?>
	<a href="mailto:<?php echo esc_attr( $footer_contact_email ); ?>"><?php echo esc_attr( antispambot( $footer_contact_email ) ); ?></a>
	<?php

}

/**
 * Partial for footer contact phone.
 *
 * @since 1.0.0
 *
 * @return void
 */
function bizroot_render_partial_footer_contact_phone() {

	$footer_contact_phone = bizroot_get_option( 'footer_contact_phone' );
	?>
	<a href="tel:<?php echo preg_replace( '/\D+/', '', esc_attr( $footer_contact_phone ) ); ?>"><?php echo esc_attr( $footer_contact_phone ); ?></a>
	<?php
}

/**
 * Partial for footer contact address.
 *
 * @since 1.0.0
 *
 * @return void
 */
function bizroot_render_partial_footer_contact_address() {

	$footer_contact_address = bizroot_get_option( 'footer_contact_address' );
	$footer_contact_map_url = bizroot_get_option( 'footer_contact_map_url' );
	$link_open  = '';
	$link_close = '';
	if ( ! empty( $footer_contact_map_url ) ) {
		$link_open  = '<a href="' . esc_url( $footer_contact_map_url ) . '" target="_blank">';
		$link_close = '</a>';
	}
	echo $link_open . esc_html( $footer_contact_address ) . $link_close;

}

/**
 * Partial for copyright text.
 *
 * @since 1.0.0
 *
 * @return void
 */
function bizroot_render_partial_copyright_text() {

	$copyright_text = bizroot_get_option( 'copyright_text' );
	$copyright_text = apply_filters( 'bizroot_filter_copyright_text', $copyright_text );
	if ( ! empty( $copyright_text ) ) {
		$copyright_text = wp_kses_data( $copyright_text );
	}
	echo $copyright_text;

}
