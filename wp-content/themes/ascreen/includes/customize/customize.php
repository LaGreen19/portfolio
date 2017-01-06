<?php
//Theme Customizer
function ascreen_customize_register( $wp_customize ) {
	/*
	 * ascreen social url Set
	*/
	$wp_customize->add_section('ascreen_social_url' , array(
		'title' => __('Social URL', 'ascreen'),
		'priority' => 120,
	));
		
	//facebook_url
	$wp_customize->add_setting( 'ascreen_option[facebook_url]', array(
		'default' => '#',
		'sanitize_callback' => 'ascreen_sanitize_url',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
		//'type' => 'option'
	) );

	$wp_customize->add_control( 'ascreen_option[facebook_url]', array(
		'label'		=> __( 'Facebook URL:', 'ascreen' ),
		'section'	=> 'ascreen_social_url',
		'settings'	=> 'ascreen_option[facebook_url]',
		'type'		=> 'url',
	) );
	
	//google_plus_url
	$wp_customize->add_setting( 'ascreen_option[google_plus_url]', array(
		'default' => '#',
		'sanitize_callback' => 'ascreen_sanitize_url',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
		//'type' => 'option'
	) );


	$wp_customize->add_control( 'ascreen_option[google_plus_url]', array(
		'label'		=> __( 'Google Plus URL:', 'ascreen' ),
		'section'	=> 'ascreen_social_url',
		'settings'	=> 'ascreen_option[google_plus_url]',
		'type'		=> 'url',
	) );

	//twitter_url
	$wp_customize->add_setting( 'ascreen_option[twitter_url]', array(
		'default' => '#',
		'sanitize_callback' => 'ascreen_sanitize_url',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
		//'type' => 'option'
	) );


	$wp_customize->add_control( 'ascreen_option[twitter_url]', array(
		'label'		=> __( 'Twitter URL:', 'ascreen' ),
		'section'	=> 'ascreen_social_url',
		'settings'	=> 'ascreen_option[twitter_url]',
		'type'		=> 'url',
	) );
	
	
	//Theme Settings section
	$wp_customize->add_section( 'ascreen_settings' , array(
		'title'		=> __( 'Theme Settings', 'ascreen' ),
		'priority'	=> 40,	
         'description' => '<p class="documentation-text">' . __('1. Documentation for Ascreen can be found <a target="_blank" href="https://www.coothemes.com/doc/ascreen-manual.php">here</a>', 'ascreen') . '</p><p class="documentation-text">' . __('2. A full theme demo can be found <a target="_blank" href="https://www.coothemes.com/themes/ascreen.php">here</a>', 'ascreen') . '</p>',  			
	) );

	//Box Header Center		
	$wp_customize->add_setting( 'ascreen_option[box_header_center]', array(
		'default'       => 0,
		'capability'	=> 'edit_theme_options',
		'sanitize_callback' => 'ascreen_sanitize_num',
		'transport'		=> 'postMessage',
	) );
	$wp_customize->add_control( 'ascreen_option[box_header_center]', array(
		'label'		=> __( 'Box Header Center', 'ascreen' ),
		'section'	=> 'ascreen_settings',
		'type'      => 'checkbox',
		'priority'  => 50,
	) );

	
	//Enable Query Loader
	$wp_customize->add_setting( 'ascreen_option[enable_query_loader]', array(
		'default'       => 0,
		'capability'	=> 'edit_theme_options',
		'sanitize_callback' => 'ascreen_sanitize_num',
		'transport'		=> 'postMessage',

	) );
	$wp_customize->add_control( 'ascreen_option[enable_query_loader]', array(
		'label'		=> __( 'Enable Query Loader', 'ascreen' ),
		'section'	=> 'ascreen_settings',
		'type'      => 'checkbox',
		'priority'  => 50,
		'description' => '<p class="documentation-text">' . __('For the following options you need to save and to refresh the display change!', 'ascreen') . '</p>', 		
	) );


	//Fixed Header
	$wp_customize->add_setting( 'ascreen_option[fixed_header]', array(
		'default'       => 1,
		'capability'	=> 'edit_theme_options',
		'sanitize_callback' => 'ascreen_sanitize_num',
		'transport'		=> 'postMessage',
	) );
	$wp_customize->add_control( 'ascreen_option[fixed_header]', array(
		'label'		=> __( 'Fixed Header', 'ascreen' ),
		'section'	=> 'ascreen_settings',
		'type'      => 'checkbox',
		'priority'  => 50,
	) );	
	
	
	//Set Home section
	$wp_customize->add_section( 'ascreen_home_section' , array(
		'title'		=> __( 'Home Section Settings', 'ascreen' ),
		'priority'	=> 40,	
         'description' => '<p class="documentation-text">' . __('1. Documentation for Ascreen can be found <a target="_blank" href="https://www.coothemes.com/doc/ascreen-manual.php">here</a>', 'ascreen') . '</p><p class="documentation-text">' . __('2. A full theme demo can be found <a target="_blank" href="https://www.coothemes.com/themes/ascreen.php">here</a>', 'ascreen') . '</p>',  			
	) );

	//enable 		
	$wp_customize->add_setting( 'ascreen_option[enable_home_section]', array(
		'default'       => 1,
		//'type'			=> 'option',
		'capability'	=> 'edit_theme_options',
		'sanitize_callback' => 'ascreen_sanitize_num2',
		'transport'		=> 'postMessage',
	) );
	$wp_customize->add_control( 'ascreen_option[enable_home_section]', array(
		'label'		=> __( 'Enable Home Section', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'type'      => 'checkbox',
		'priority'  => 50,
	) );



	$wp_customize->add_setting( 'ascreen_option[video_youtube_id]', array(
		'default' => __('e1c-n1dRxwc', 'ascreen'),
		'sanitize_callback' => 'ascreen_sanitize_textarea',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'ascreen_option[video_youtube_id]', array(
		'label'		=> __( 'Youtube Id', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'settings'	=> 'ascreen_option[video_youtube_id]',
		'type'		=> 'textarea',
				'priority'  => 50,
	) );


	$wp_customize->add_setting( 'ascreen_option[video_title]', array(
		'default' => __('Awesome One Page Theme', 'ascreen'),
		'sanitize_callback' => 'ascreen_sanitize_textarea',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'ascreen_option[video_title]', array(
		'label'		=> __( 'Title', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'settings'	=> 'ascreen_option[video_title]',
		'type'		=> 'textarea',
				'priority'  => 50,
	) );


	$wp_customize->add_setting( 'ascreen_option[video_decription]', array(
		'default' => __('Ascreen supports full width home page sliders, and video background.', 'ascreen'),
		'sanitize_callback' => 'ascreen_sanitize_textarea',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'ascreen_option[video_decription]', array(
		'label'		=> __( 'Decription', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'type'		=> 'textarea',
		'priority'  => 50,
		
	) );	

	//button one
	$wp_customize->add_setting( 'ascreen_option[video_button_one]', array(
		'default' => __('Try it now!', 'ascreen'),
		'sanitize_callback' => 'ascreen_sanitize_textarea',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'ascreen_option[video_button_one]', array(
		'label'		=> __( 'Video Button One', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'type'		=> 'textarea',
		'priority'  => 50,
	) );
	
	
	$wp_customize->add_setting( 'ascreen_option[video_button_one_link]', array(
		'default' => '#',
		'sanitize_callback' => 'ascreen_sanitize_url',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );


	$wp_customize->add_control( 'ascreen_option[video_button_one_link]', array(
		'label'		=> __( 'Button One URL:', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'settings'	=> 'ascreen_option[video_button_one_link]',
		'type'		=> 'url',
		'priority'  => 50,
	) );	
	
	
	
	//button two
	$wp_customize->add_setting( 'ascreen_option[video_button_two]', array(
		'default' => __('Download Now', 'ascreen'),
		'sanitize_callback' => 'ascreen_sanitize_textarea',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'ascreen_option[video_button_two]', array(
		'label'		=> __( 'Video Button Two', 'ascreen' ),		
		'section'	=> 'ascreen_home_section',
		'settings'	=> 'ascreen_option[video_button_two]',		
		'type'		=> 'textarea',
				'priority'  => 50,
	) );
	
	
	$wp_customize->add_setting( 'ascreen_option[video_button_two_link]', array(
		'default' => '#',
		'sanitize_callback' => 'ascreen_sanitize_url',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );


	$wp_customize->add_control( 'ascreen_option[video_button_two_link]', array(
		'label'		=> __( 'Button Two URL:', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'settings'	=> 'ascreen_option[video_button_two_link]',
		'type'		=> 'url',
		'priority'  => 50,
	) );	
	
	
	//contact us
	$wp_customize->add_setting( 'ascreen_option[contact_title]', array(
		'default' => __('CONTACT US', 'ascreen'),
		'sanitize_callback' => 'ascreen_sanitize_textarea',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'ascreen_option[contact_title]', array(
		'label'		=> __( 'Contact Us Title', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'settings'	=> 'ascreen_option[contact_title]',
		'type'		=> 'textarea',
		'priority'  => 50,
	) );


	$wp_customize->add_setting( 'ascreen_option[contact_decription]', array(
		'default' => __('Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere c.Etiam ut dui eVestibulum ante ipsum primi', 'ascreen'),
		'sanitize_callback' => 'ascreen_sanitize_textarea',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'ascreen_option[contact_decription]', array(
		'label'		=> __( 'Contact Us Decription', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'settings'	=> 'ascreen_option[contact_decription]',
		'type'		=> 'textarea',
		'priority'  => 50,
		
	) );	
	

	$wp_customize->add_setting( 'ascreen_option[contact_email]', array(
		'default' => get_bloginfo( 'admin_email'),
		'sanitize_callback' => 'ascreen_sanitize_textarea',
		'capability'	=> 'edit_theme_options',
		'transport'		=> 'postMessage',
	) );

	$wp_customize->add_control( 'ascreen_option[contact_email]', array(
		'label'		=> __( 'Contact Email', 'ascreen' ),
		'section'	=> 'ascreen_home_section',
		'settings'	=> 'ascreen_option[contact_email]',
		'type'		=> 'textarea',
		'priority'  => 50,
		
	) );	
		
}
add_action( 'customize_register', 'ascreen_customize_register' );

function ascreen_sanitize_url( $value ) {
      $value = esc_url_raw( $value);
      return $value;
}

function ascreen_sanitize_num( $value) {
  if ( ! $value || is_null($value) )
	return $value;
  $value = esc_attr( $value); // clean input
  $value = (int) $value; // Force the value into integer type.
	return ( 0 < $value ) ? $value : null;
}
function ascreen_sanitize_textarea( $str ) {
	return wp_kses_post($str);
}
function ascreen_sanitize_num2( $str ) {
	return $str;
}
