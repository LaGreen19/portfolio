<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB directory)
 *
 *
 * @category waving-portfolio
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/init.php';
}

add_action( 'cmb2_init', 'waving_posrtfolio_register_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */
function waving_posrtfolio_register_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_waving_portfolio_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$waving_demo = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Waving Portfolio', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'object_types'  => array( 'itech_portfolio'), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
	) );

	$waving_demo->add_field( array(
		'name'         => __( 'Select gallary images', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'desc'         => __( 'Upload or add multiple images to be shown inside of your portfolio element', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'id'           => $prefix . 'file_list',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
	) );
    
    $waving_demo->add_field( array(
      'name' => __( 'Title font size', WAVING_PORTFOLIO_TEXT_DOMAIN ),
      'desc' => __( 'Sliding panel title font size', WAVING_PORTFOLIO_TEXT_DOMAIN ),
      'id'   => $prefix . 'fontsize',
      'type' => 'text_small',
      'default' => '15',
      'attributes' => array(
          'type' => 'number',
          'pattern' => '\d*',
          'min' => '8',
          'max' => '50',
      )
    ) ); 
    
    $waving_demo->add_field( array(
      'name' => __( 'Sliding panel excerpt length', WAVING_PORTFOLIO_TEXT_DOMAIN ),
      'desc' => __( 'Number of characters in excerpt', WAVING_PORTFOLIO_TEXT_DOMAIN ),
      'id'   => $prefix . 'excerpt_length',
      'type' => 'text_small',
      'default' => '20',
      'attributes' => array(
          'type' => 'number',
          'pattern' => '\d*',
          'min' => '8',
          'max' => '50',
      )
    ) ); 
    
    $waving_demo->add_field( array(
		'name' => __( 'Hyperlink', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'desc' => __( 'Portfolio item hyperlink', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'id'   => $prefix . 'hyperlink',
		'type' => 'text_url',
	) );
    
    $waving_demo->add_field( array(
		'name' => __( 'On click over portfolio item', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'desc' => __( 'Redirect to hyperlink [Check]', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'id'   => $prefix . 'hori',
		'type' => 'checkbox',
	) );
    
    $waving_demo->add_field( array(
		'name' => __( 'Open in a blank page', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'desc' => __( 'Check to open the Hyperlink in a new blank page', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'id'   => $prefix . 'blank',
		'type' => 'checkbox',
	) );
        
    $waving_demo->add_field( array(
		'name' => __( 'Enable description', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'desc' => __( 'Enable excerpt in sliding panel', WAVING_PORTFOLIO_TEXT_DOMAIN ),
		'id'   => $prefix . 'excerpt',
		'type' => 'checkbox',
	) );

}
