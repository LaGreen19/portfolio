<?php
/**
 * TGM implementation.
 *
 * @package Bizroot
 */

require_once get_template_directory() . '/lib/tgm/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'bizroot_register_recommended_plugins' );

/**
 * Register recommended plugins.
 *
 * @since 1.0.0
 */
function bizroot_register_recommended_plugins() {

	// Plugins.
	$plugins = array(
		array(
			'name' => __( 'Contact Form 7', 'bizroot' ),
			'slug' => 'contact-form-7',
		),
	);

	// TGM configurations.
	$config = array(
	);

	// Register now.
	tgmpa( $plugins, $config );

}

